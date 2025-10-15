<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fee;
use App\Models\SectionClass;
use App\Models\AdmissionLetter;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SectionAndClassTableSeeder::class);
        $this->call(SectionClassGroupTableSeeder::class);        
        $this->call(SectionAccessment::class);
        $this->call(UserTableSeeder::class);
        $this->call(GenderTableSeeder::class);
        $this->call(TermTableSeeder::class);
        $this->call(AcademicSessionTableSeeder::class);
        $this->call(TeacherCommentTableSeeder::class);
        $this->call(HeadTeacherCommentTableSeeder::class);
        $this->call(PsychomotorAndAffectiveTraitTableSeeder::class);
        $this->call(SectionAccessment::class);
        $this->call(GradeRemarkTableSeeder::class);
        AdmissionLetter::firstOrCreate([]);

        foreach(SectionClass::all() as $sectionClass){

            foreach(Fee::all() as $fee){
                $sectionClass->sectionClassFees()->firstOrCreate(['fee_id'=>$fee->id]);
            }

        }
    }
}
