<?php

namespace Tests\Feature;

use App\Http\Livewire\Admission\Applications;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class AdmissionApplicationsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'applications_test', 'database.connections.applications_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        foreach (['students', 'guardians', 'genders', 'sections', 'section_classes', 'academic_sessions'] as $name) {
            Schema::create($name, function (Blueprint $table) use ($name) {
                $table->id(); $table->string('name'); $table->timestamps();
                if ($name === 'guardians') $table->string('phone');
                if ($name === 'section_classes') $table->integer('section_id');
                if ($name === 'students') {
                    $table->integer('guardian_id')->nullable(); $table->integer('gender_id')->nullable();
                    $table->integer('desired_section_class_id')->nullable(); $table->integer('academic_session_id');
                    $table->string('admission_status');
                }
            });
        }
        $user = new User(['role' => 'admission_officer', 'status' => 'Active']);
        $user->id = 1;
        $user->setRelation('accessRoles', collect());
        $user->setRelation('directPermissions', collect([new Permission(['slug' => 'manage-admissions'])]));
        $this->actingAs($user);
        DB::table('genders')->insert([['id' => 7, 'name' => 'Male'], ['id' => 8, 'name' => 'Female']]);
        DB::table('guardians')->insert(['id' => 1, 'name' => 'Guardian', 'phone' => '08012345678']);
        foreach ([1, 2] as $id) {
            DB::table('academic_sessions')->insert(['id' => $id, 'name' => 'Session '.$id]);
            DB::table('sections')->insert(['id' => $id, 'name' => 'Section '.$id]);
            DB::table('section_classes')->insert(['id' => $id, 'name' => 'Class '.$id, 'section_id' => $id]);
        }
        foreach ([[1, 7, 1, 1, 'Pending'], [2, 8, 1, 1, 'Pending'], [3, null, 2, 2, 'Pending'],
            [4, 7, null, 1, 'Pending'], [5, 8, 99, 1, 'Pending'], [6, 8, 1, 1, 'Admitted']] as [$id, $gender, $class, $session, $status]) {
            DB::table('students')->insert(['id' => $id, 'name' => 'Applicant '.$id, 'guardian_id' => 1,
                'gender_id' => $gender, 'desired_section_class_id' => $class, 'academic_session_id' => $session, 'admission_status' => $status]);
        }
    }

    protected function tearDown(): void
    {
        DB::purge('applications_test');
        parent::tearDown();
    }

    public function test_statistics_and_sections_count_only_matching_pending_applications_with_valid_classes()
    {
        Livewire::test(Applications::class)
            ->assertViewHas('statistics', ['total' => 3, 'male' => 1, 'female' => 1, 'unspecified' => 1])
            ->assertViewHas('stats', fn($rows) => $rows->pluck('application_count', 'id')->all() === [1 => 2, 2 => 1])
            ->set('filterSessionId', '1')->set('filterSectionId', '1')->set('filterClassId', '1')
            ->assertViewHas('statistics', ['total' => 2, 'male' => 1, 'female' => 1, 'unspecified' => 0])
            ->set('search', 'Applicant 2')->assertViewHas('statistics', ['total' => 1, 'male' => 0, 'female' => 1, 'unspecified' => 0])
            ->assertViewHas('stats', fn($rows) => $rows->sum('application_count') === 1)
            ->set('filterSessionId', '2')->assertViewHas('applications', fn($rows) => $rows->isEmpty());
    }

    public function test_filter_changes_do_not_change_application_form_and_reset_restores_list()
    {
        Livewire::test(Applications::class)->set('classId', '1')->set('filterClassId', '1')
            ->set('filterSectionId', '2')->assertSet('filterClassId', '')->assertSet('classId', '1')
            ->assertViewHas('filterClasses', fn($rows) => $rows->pluck('id')->all() === [2])
            ->assertViewHas('statistics', ['total' => 1, 'male' => 0, 'female' => 0, 'unspecified' => 1])
            ->set('search', '08012345678')->assertViewHas('applications', fn($rows) => $rows->count() === 1)
            ->call('resetFilters')->assertSet('search', '')->assertSet('filterSectionId', '')
            ->assertSet('classId', '1')->assertViewHas('applications', fn($rows) => $rows->count() === 3);
    }
}
