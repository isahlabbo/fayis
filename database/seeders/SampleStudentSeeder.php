<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\Guardian;
use App\Models\SectionClass;
use App\Models\Student;
use Illuminate\Database\Seeder;

/** Populates a handful of active students across every existing class, for exercising screens like Material Collection. */
class SampleStudentSeeder extends Seeder
{
    private const FIRST_NAMES = ['Amina', 'Bello', 'Chika', 'Danjuma', 'Efe', 'Fatima', 'Gozie', 'Halima', 'Ibrahim', 'Jumoke'];
    private const LAST_NAMES = ['Abdullahi', 'Chukwu', 'Danladi', 'Eze', 'Garba', 'Hassan', 'Ibe', 'Junaid', 'Kalu', 'Lawal'];
    private const STUDENTS_PER_CLASS = 6;

    public function run()
    {
        $session = AcademicSession::where('status', 'Active')->first() ?? AcademicSession::first();

        if (!$session) {
            $this->command->error('No academic session found. Run the base DatabaseSeeder first.');
            return;
        }

        $classes = SectionClass::with('section')->get();

        if ($classes->isEmpty()) {
            $this->command->error('No section classes found. Run the base DatabaseSeeder first.');
            return;
        }

        $serial = Student::max('id') + 1;
        $created = 0;

        foreach ($classes as $sectionClass) {
            for ($i = 1; $i <= self::STUDENTS_PER_CLASS; $i++) {
                $name = self::FIRST_NAMES[array_rand(self::FIRST_NAMES)].' '.self::LAST_NAMES[array_rand(self::LAST_NAMES)];

                $guardian = Guardian::create([
                    'name' => 'Guardian of '.$name,
                    'phone' => '0803'.str_pad($serial, 7, '0', STR_PAD_LEFT),
                    'address' => 'Sample Address '.$serial,
                    'email' => 'guardian'.$serial.'@fayis-sample.ng',
                ]);

                $student = $guardian->students()->create([
                    'name' => $name,
                    'academic_session_id' => $session->id,
                    'date_of_birth' => '2015-06-15',
                    'admission_no' => 'FAY/SAMPLE/'.str_pad($serial, 4, '0', STR_PAD_LEFT),
                    'gender_id' => rand(1, 2),
                ]);

                $student->assignToThisClass($sectionClass->id, 'Active', $session);

                $serial++;
                $created++;
            }
        }

        $this->command->info("Created {$created} sample students across {$classes->count()} classes.");
    }
}
