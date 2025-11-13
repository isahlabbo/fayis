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
            "Arabic", 
            "Basic technology",
            "Basic science", 
            "Business studies",
            "CCA",
            "Civic Education",
            "Colouring",
            "Computer",
            "English",
            "Hand Writing",
            "Hausa", 
            "Hausa Mukaranta",
            "Home Economics",
            "IRK",
            "Jolly phonics", 
            "Let's read",
            "Mathematics", 
            "PHE",
            "Poem",
            "Social studies"         
            
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
                foreach(['A','B'] as $class){
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
