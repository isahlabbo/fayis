<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SectionClass;

class SectionAndClassTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects =[
            "Islamic Religious Studies",
            "Mathematics",
            "English Studies",
            "Nursery Science",
            "Writing",
            "Al-Quar'an",
            "Al-Adhkaar",
            "Al-Arabiyyah",
            "Al-Huruuf",
            "Al-Kitabah",
            "Basic Science And Technology",
            "Religion and National Value",
            "Hausa",
            "Business Studies",
            "Pre-vocational Studies",
            "Computer",
            "At-tahdheeb",
            "Al-Qiraah",
            "An-Nahw",
            "Al-Tajweed",
            "Al-khatt"
        ];
        
        foreach($subjects as $subject){
            {
                $newSubject = Subject::firstOrCreate(['name'=>strtoupper($subject)]);
            }
        }

        $sections = [
            [
                'name'=>'Nursery',
                'level'=>1,
                'duration'=>2,
                'class_tag'=>'NU',
            ], 
            [
                'name'=>'Lower Basic',
                'level'=>2,
                'duration'=>3,
                'class_tag'=>'LB',
            ],
            [
                'name'=>'Middle Basic',
                'level'=>3,
                'duration'=>3,
                'class_tag'=>'MB',
            ],
            [
                'name'=>'Upper Basic',
                'level'=>4,
                'duration'=>3,
                'class_tag'=>'UB',
            ]
        ];

        foreach($sections as $section){
            $newSection = Section::create($section);
          
            for($arm = 1; $arm<=$section['duration']; $arm++){
                foreach(['A','B','C'] as $class){
                    $className = $section['class_tag'].' '.$arm.$class;
                    SectionClass::create([
                        'section_id'=>$newSection->id,
                        'name'=>$className,
                        'year_sequence'=>$arm,
                        'code'=>$arm.$class,
                    ]);
                }
            }
        }
    }
}
