<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeadTeacherComment;

class HeadTeacherCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = [
            ['name'=>"An excellent performance, he has the ability to perform better if properly monitored.",'gender'=>1],
            ['name'=>"An excellent performance, she has the ability to perform better if properly monitored.",'gender'=>2],
            ['name'=>"A very good performance, He should intensify efforts to improve.",'gender'=>1],
            ['name'=>"A very good performance, She should intensify efforts to improve.",'gender'=>2],
            ['name'=>"A good result, he should intensify efforts in his/her pursuit for academic excellence.",'gender'=>1],
            ['name'=>"A good result, she should intensify efforts in his/her pursuit for academic excellence.",'gender'=>2],
            ['name'=>"A good performance, he needs encouragement in order to perform better.",'gender'=>1],
            ['name'=>"A good performance, she needs encouragement in order to perform better.",'gender'=>2],
            ['name'=>"A good result although there is room for improvement.",'gender'=>1],
            ['name'=>"A good result although there is room for improvement.",'gender'=>2],
            ['name'=>"An average performance, he should work harder next term to achieve academic excellence.",'gender'=>1],
            ['name'=>"An average performance, she should work harder next term to achieve academic excellence.",'gender'=>2],
            ['name'=>"A fair performance he has what it takes to improve. He needs to be encouraged.",'gender'=>1],
            ['name'=>"A fair performance she has what it takes to improve. She needs to be encouraged.",'gender'=>2],
            ['name'=>"A poor performance, if properly monitored and efforts intensified, he will improve academically.",'gender'=>1],
            ['name'=>"A poor performance, if properly monitored and efforts intensified, she will improve academically.",'gender'=>2]

        ];
        
        foreach ($comments as $comment) {
            HeadTeacherComment::firstOrCreate($comment);
        }
    }
}
