<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradeScale;
use App\Models\RemarkScale;

class GradeRemarkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = [
            ['grade'=>'A','from'=>70,'to'=>100],
            ['grade'=>'B','from'=>60,'to'=>69],
            ['grade'=>'C','from'=>50,'to'=>59],
            ['grade'=>'D','from'=>45,'to'=>49],
            ['grade'=>'E','from'=>40,'to'=>44],
            ['grade'=>'F','from'=>0,'to'=>39]
        ];
        foreach($grades as $grade){
            GradeScale::firstOrCreate($grade);
        }

        $scales = [
            ['percent'=>90,'grade'=>'A','remark'=>'Distinction','scale'=>5],
            ['percent'=>70,'grade'=>'B','remark'=>'Excellent','scale'=>4],
            ['percent'=>60,'grade'=>'C','remark'=>'Very Godd','scale'=>3],
            ['percent'=>50,'grade'=>'D','remark'=>'Pass','scale'=>2],
            ['percent'=>40,'grade'=>'E','remark'=>'Fair','scale'=>1],
            ['percent'=>0,'grade'=>'F','remark'=>'Poor','scale'=>0],
        ];

        foreach ($scales as $scale) {
            RemarkScale::firstOrCreate($scale);
        }
    }
}
