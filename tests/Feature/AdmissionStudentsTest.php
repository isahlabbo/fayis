<?php

namespace Tests\Feature;

use App\Http\Livewire\Admission\Students;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class AdmissionStudentsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'students_test', 'database.connections.students_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        foreach (['students', 'genders', 'guardians', 'academic_sessions', 'sections', 'section_classes', 'section_class_students'] as $name) {
            Schema::create($name, function (Blueprint $table) use ($name) {
                $table->id(); $table->string('name')->nullable(); $table->timestamps();
                if ($name === 'students') {
                    $table->string('admission_no'); $table->integer('gender_id')->nullable(); $table->integer('guardian_id')->nullable();
                }
                if ($name === 'section_classes') $table->integer('section_id');
                if (in_array($name, ['academic_sessions', 'section_class_students'])) $table->string('status');
                if ($name === 'section_class_students') {
                    $table->integer('student_id'); $table->integer('academic_session_id'); $table->integer('section_class_id');
                }
            });
        }
        Schema::create('academic_session_terms', function (Blueprint $table) {
            $table->id(); $table->integer('academic_session_id'); $table->integer('term_id'); $table->string('status');
        });
        Schema::create('section_class_student_terms', function (Blueprint $table) {
            $table->id(); $table->integer('section_class_student_id'); $table->integer('academic_session_term_id');
            $table->string('status'); $table->timestamps();
        });
        $this->mock(\App\Services\Finance\ApplyAdvancePayments::class, function ($mock) {
            $mock->shouldReceive('handle')->andReturnNull();
        });
        DB::table('academic_session_terms')->insert([
            ['id' => 1, 'academic_session_id' => 1, 'term_id' => 1, 'status' => 'Active'],
            ['id' => 2, 'academic_session_id' => 2, 'term_id' => 1, 'status' => 'Active'],
        ]);
        DB::table('section_class_student_terms')->insert(['id' => 1, 'section_class_student_id' => 1, 'academic_session_term_id' => 2, 'status' => 'Active']);
        $user = new User(['role' => 'admission_officer', 'status' => 'Active']);
        $user->id = 1;
        $user->setRelation('accessRoles', collect());
        $user->setRelation('directPermissions', collect([new Permission(['slug' => 'manage-admissions'])]));
        $this->actingAs($user);
        DB::table('genders')->insert([['id' => 7, 'name' => 'Male'], ['id' => 8, 'name' => 'Female']]);
        DB::table('academic_sessions')->insert([
            ['id' => 1, 'name' => '2025/2026', 'status' => 'Not Active'],
            ['id' => 2, 'name' => '2026/2027', 'status' => 'Active'],
        ]);
        DB::table('sections')->insert(['id' => 1, 'name' => 'Primary']);
        DB::table('section_classes')->insert(['id' => 1, 'name' => 'Primary One', 'section_id' => 1]);
        foreach ([1 => 7, 2 => 8, 3 => null] as $id => $gender) {
            DB::table('students')->insert(['id' => $id, 'name' => 'Student '.$id, 'admission_no' => 'ADM-'.$id, 'gender_id' => $gender]);
            DB::table('section_class_students')->insert(['id' => $id, 'student_id' => $id, 'academic_session_id' => 2, 'section_class_id' => 1, 'status' => $id === 3 ? 'Not Active' : 'Active']);
        }
        DB::table('section_class_students')->insert(['id' => 4, 'student_id' => 1, 'academic_session_id' => 1, 'section_class_id' => 1, 'status' => 'Not Active']);
    }

    protected function tearDown(): void
    {
        DB::purge('students_test');
        parent::tearDown();
    }

    public function test_counts_follow_filters_and_all_sessions_do_not_double_count_students()
    {
        Livewire::test(Students::class)->assertSet('sessionId', '2')->assertSet('status', 'Active')
            ->assertViewHas('statistics', ['total' => 2, 'male' => 1, 'female' => 1, 'unspecified' => 0])
            ->set('status', '')->set('sessionId', '')
            ->assertViewHas('statistics', ['total' => 3, 'male' => 1, 'female' => 1, 'unspecified' => 1])
            ->set('sessionId', '1')->assertViewHas('statistics', ['total' => 1, 'male' => 1, 'female' => 0, 'unspecified' => 0])
            ->set('status', 'Active')->assertViewHas('records', fn ($rows) => $rows->isEmpty());
    }

    public function test_selection_and_search_are_scoped_to_matching_students()
    {
        Livewire::test(Students::class)->set('selectAll', true)->assertSet('selected', ['2', '1'])
            ->set('search', 'ADM-2')->assertSet('selected', [])->assertSet('selectAll', false)
            ->assertViewHas('statistics', ['total' => 1, 'male' => 0, 'female' => 1, 'unspecified' => 0])
            ->set('selected', ['2', '4'])->assertSet('selected', ['2'])->assertSet('selectAll', true)
            ->call('clearSelection')->assertSet('selected', [])
            ->assertSee(route('admission.student.edit', 2), false)
            ->set('classId', '1')->set('sectionId', '99')->assertSet('classId', '')
            ->assertViewHas('records', fn ($rows) => $rows->isEmpty());
    }

    public function test_deleting_old_enrolment_preserves_profile_other_sessions_and_other_classes()
    {
        DB::table('section_classes')->insert(['id' => 2, 'name' => 'Other class', 'section_id' => 1]);
        DB::table('section_class_students')->insert(['id' => 5, 'student_id' => 1, 'academic_session_id' => 1, 'section_class_id' => 2, 'status' => 'Not Active']);
        DB::table('section_class_student_terms')->insert([
            ['id' => 2, 'section_class_student_id' => 4, 'academic_session_term_id' => 1, 'status' => 'Not Active'],
            ['id' => 3, 'section_class_student_id' => 5, 'academic_session_term_id' => 1, 'status' => 'Not Active'],
        ]);
        Livewire::test(Students::class)->set('sessionId', '1')->set('status', '')->set('classId', '1')
            ->set('selectAll', true)->assertSet('selected', ['4'])->call('deleteSelectedEnrolments')
            ->assertHasNoErrors()->assertSet('selected', [])->assertSet('selectAll', false);
        $this->assertDatabaseMissing('section_class_students', ['id' => 4]);
        $this->assertDatabaseMissing('section_class_student_terms', ['id' => 2]);
        $this->assertDatabaseHas('students', ['id' => 1]);
        $this->assertDatabaseHas('section_class_students', ['id' => 1, 'academic_session_id' => 2]);
        $this->assertDatabaseHas('section_class_students', ['id' => 5, 'section_class_id' => 2]);
        $this->assertDatabaseHas('section_class_student_terms', ['id' => 1]);
        $this->assertDatabaseHas('section_class_student_terms', ['id' => 3]);
    }

    public function test_deletion_requires_specific_class_session_and_current_selection()
    {
        Livewire::test(Students::class)->set('selectAll', true)->call('deleteSelectedEnrolments')->assertHasErrors('classId');
        Livewire::test(Students::class)->set('sessionId', '')->set('classId', '1')->set('selectAll', true)
            ->call('deleteSelectedEnrolments')->assertHasErrors('sessionId');
        $screen = Livewire::test(Students::class)->set('classId', '1')->set('selectAll', true);
        DB::table('section_class_students')->where('id', 2)->update(['academic_session_id' => 1]);
        $screen->call('deleteSelectedEnrolments')->assertHasErrors('selected');
        $this->assertEquals(4, DB::table('section_class_students')->count());
        $this->assertEquals(1, DB::table('section_class_student_terms')->count());
    }

    /** @dataProvider dependentEnrolmentRecords */
    public function test_linked_records_block_the_entire_deletion($table, $column)
    {
        Schema::create($table, function (Blueprint $schema) use ($column) {
            $schema->id(); $schema->integer($column);
        });
        DB::table($table)->insert([$column => 1]);
        Livewire::test(Students::class)->set('classId', '1')->set('selectAll', true)
            ->call('deleteSelectedEnrolments')->assertHasErrors('selected');
        $this->assertEquals(4, DB::table('section_class_students')->count());
        $this->assertEquals(1, DB::table('section_class_student_terms')->count());
        $this->assertEquals(1, DB::table($table)->count());
    }

    public function dependentEnrolmentRecords(): array
    {
        return [
            ['payments', 'section_class_student_id'],
            ['student_results', 'section_class_student_term_id'],
            ['inventory_sales', 'section_class_student_id'],
        ];
    }

    /** @dataProvider promotionDeletionCases */
    public function test_deletion_removes_only_linked_promotions_and_preserves_other_enrolments($blocked)
    {
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id(); $table->integer('student_id');
            $table->integer('from_enrolment_id'); $table->integer('to_enrolment_id');
        });
        DB::table('section_class_students')->insert(['id' => 5, 'student_id' => 1, 'academic_session_id' => 1, 'section_class_id' => 1, 'status' => 'Not Active']);
        DB::table('student_promotions')->insert([
            ['id' => 1, 'student_id' => 1, 'from_enrolment_id' => 4, 'to_enrolment_id' => 1],
            ['id' => 2, 'student_id' => 1, 'from_enrolment_id' => 1, 'to_enrolment_id' => 5],
            ['id' => 3, 'student_id' => 1, 'from_enrolment_id' => 4, 'to_enrolment_id' => 5],
        ]);
        if ($blocked) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id(); $table->integer('section_class_student_id');
            });
            DB::table('payments')->insert(['section_class_student_id' => 1]);
        }
        $screen = Livewire::test(Students::class)->set('classId', '1')->set('search', 'ADM-1')
            ->set('selectAll', true)->assertSet('selected', ['1'])->call('deleteSelectedEnrolments');
        if ($blocked) {
            $screen->assertHasErrors('selected');
            $this->assertEquals(3, DB::table('student_promotions')->count());
            $this->assertDatabaseHas('section_class_students', ['id' => 1]);
            $this->assertDatabaseHas('section_class_student_terms', ['id' => 1]);
        } else {
            $screen->assertHasNoErrors();
            $this->assertEquals([3], DB::table('student_promotions')->pluck('id')->all());
            $this->assertDatabaseMissing('section_class_students', ['id' => 1]);
            $this->assertDatabaseMissing('section_class_student_terms', ['id' => 1]);
        }
        $this->assertDatabaseHas('students', ['id' => 1]);
        $this->assertDatabaseHas('section_class_students', ['id' => 4, 'status' => 'Not Active']);
        $this->assertDatabaseHas('section_class_students', ['id' => 5, 'status' => 'Not Active']);
        $this->assertDatabaseHas('section_class_students', ['id' => 2]);
    }

    public function promotionDeletionCases(): array
    {
        return [[false], [true]];
    }

    public function test_student_screen_requires_admissions_permission()
    {
        $user = new User(['role' => 'staff']);
        $user->id = 2;
        $user->setRelation('accessRoles', collect());
        $user->setRelation('directPermissions', collect());
        $this->actingAs($user);
        Livewire::test(Students::class)->assertForbidden();
    }

    public function test_select_all_updates_only_displayed_enrolments_and_their_term_statuses()
    {
        Livewire::test(Students::class)->set('selectAll', true)->set('targetStatus', 'Not Active')
            ->call('updateSelected')->assertHasNoErrors()->assertSet('selected', [])->assertSet('status', 'Not Active');
        $this->assertEquals('Not Active', DB::table('section_class_students')->where('id', 1)->value('status'));
        $this->assertEquals('Not Active', DB::table('section_class_students')->where('id', 2)->value('status'));
        $this->assertEquals('Not Active', DB::table('section_class_student_terms')->where('id', 1)->value('status'));
        $this->assertEquals(4, DB::table('section_class_students')->count());
    }

    public function test_session_update_reuses_or_creates_enrolments_and_preserves_history()
    {
        Livewire::test(Students::class)->set('selectAll', true)->set('targetSessionId', '1')->set('targetStatus', 'Active')
            ->call('updateSelected')->assertHasNoErrors()->assertSet('sessionId', '1')->assertSet('selected', []);
        $this->assertEquals(5, DB::table('section_class_students')->count());
        $this->assertEquals('Active', DB::table('section_class_students')->where('id', 4)->value('status'));
        $this->assertEquals(2, DB::table('section_class_students')->where('id', 1)->value('academic_session_id'));
        $this->assertEquals('Not Active', DB::table('section_class_students')->where('id', 1)->value('status'));
        $this->assertEquals(2, DB::table('section_class_student_terms')->where('id', 1)->value('academic_session_term_id'));
        $this->assertDatabaseHas('section_class_student_terms', ['section_class_student_id' => 4, 'academic_session_term_id' => 1, 'status' => 'Active']);
        $this->assertDatabaseHas('section_class_students', ['student_id' => 2, 'section_class_id' => 1, 'academic_session_id' => 1, 'status' => 'Active']);
    }

    public function test_bulk_update_validates_selection_and_target()
    {
        Livewire::test(Students::class)->set('targetStatus', 'Not Active')->call('updateSelected')->assertHasErrors('selected');
        Livewire::test(Students::class)->set('selectAll', true)->call('updateSelected')->assertHasErrors('targetStatus');
        Livewire::test(Students::class)->set('selectAll', true)->set('targetStatus', 'invalid')->call('updateSelected')->assertHasErrors('targetStatus');
        $screen = Livewire::test(Students::class)->set('selectAll', true)->set('targetStatus', 'Not Active');
        DB::table('section_class_students')->where('id', 2)->update(['status' => 'Not Active']);
        $screen->call('updateSelected')->assertHasErrors('selected');
        $this->assertEquals('Active', DB::table('section_class_students')->where('id', 1)->value('status'));
    }
}
