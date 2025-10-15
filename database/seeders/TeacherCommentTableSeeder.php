<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeacherComment;

class TeacherCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = [
            ['name'=>'He is a well behaved and disciplined pupil. Keep it up.','gender'=>1],
            ['name'=>'She is a well behaved and disciplined pupil. Keep it up.','gender'=>2],
            ['name'=>'He is a serious, intelligent and hardworking pupil. Keep it up.','gender'=>1],
            ['name'=>'She is a serious, intelligent and hardworking pupil. Keep it up.','gender'=>2],
            ['name'=>"He uses instincts to deal with matters independently and in a positive way.",'gender'=>1],
            ['name'=>"She uses instincts to deal with matters independently and in a positive way.",'gender'=>2],
            ['name'=>"He is a neat and tidy pupil. He conducts himself in a matured manner",'gender'=>1],
            ['name'=>"She is a neat and tidy pupil. She conducts herself in a matured manner",'gender'=>2],
            ['name'=>"He exhibits a positive outlook and attitude in the classroom.",'gender'=>1],
            ['name'=>"She exhibits a positive outlook and attitude in the classroom.",'gender'=>2],
            ['name'=>"He is improving both in character and learning. However, there is room for improvement.",'gender'=>1],
            ['name'=>"She is improving both in character and learning. However, there is room for improvement.",'gender'=>2],
            ['name'=>"He always shows enthusiasm in classroom activities.",'gender'=>1],
            ['name'=>"She always shows enthusiasm in classroom activities.",'gender'=>2],
            ['name'=>"He is a good pupil, he resists the urge to be distracted by other pupils.",'gender'=>1],
            ['name'=>"She is a good pupil, she resists the urge to be distracted by other pupils.",'gender'=>2],
            ['name'=>"He is a bit playful, but with efforts from home and school he is likely to improve.",'gender'=>1],
            ['name'=>"She is a bit playful, but with efforts from home and school she is likely to improve.",'gender'=>2],
            ['name'=>"He is an intelligent child. He only needs to exhibit this intelligence in order to attain his full potentials.",'gender'=>1],
            ['name'=>"She is an intelligent child. She only needs to exhibit this intelligence in order to attain her full potentials.",'gender'=>2],
            ['name'=>"He is a good pupil with keen interest in learning.",'gender'=>1],
            ['name'=>"She is a good pupil with keen interest in learning.",'gender'=>2],
            ['name'=>"He is a bit lazy but can be hard working if the necessary motivation is given to him.",'gender'=>1],
            ['name'=>"She is a bit lazy but can be hard working if the necessary motivation is given to her.",'gender'=>2],

            ['name'=>"Has a good attitude toward school and is enthusiastic about participating.",'gender'=>3],
            ['name'=>"Shows initiative and thinks things through for himself and Takes an active role in discussions.",'gender'=>1],
            ['name'=>"Shows initiative and thinks things through for herself and Takes an active role in discussions.",'gender'=>2],
            ['name'=>"Exhibits a positive outlook and attitude in the classroom  ",'gender'=>3],
            ['name'=>"Rushes through work, does not work at an appropriate pace. He needs to improve classroom attitude.",'gender'=>1],
            ['name'=>"Rushes through work, does not work at an appropriate pace. She needs to improve classroom attitude.",'gender'=>2],
            ['name'=>"Comprehends well, but needs to work more quickly.",'gender'=>3],
            ['name'=>"Strive to reach his full potentials. He Is self-confident and has excellent manners.",'gender'=>1],
            ['name'=>"Strive to reach her full potentials. He Is self-confident and has excellent manners.",'gender'=>2],

            ['name'=>"He is self-confident and has excellent manners. He is a role model for the class with his good behavior.",'gender'=>1],
            ['name'=>"She is self-confident and has excellent manners. She is a role model for the class with her good behavior.",'gender'=>2],
            ['name'=>"Puts his (or her) best effort into homework/assignments. Uses class time wisely.",'gender'=>3],
            ['name'=>"He is honest and trustworthy in dealings with others, he is also courteous and shows good manners in the classroom.",'gender'=>1],
            ['name'=>"She is honest and trustworthy in dealings with others, she is also courteous and shows good manners in the classroom.",'gender'=>2],
            ['name'=>"He is enthusiastic about participating. He is self-confident and has excellent manners.",'gender'=>1],
            ['name'=>"She is enthusiastic about participating. She Is self-confident and has excellent manners.",'gender'=>2],
            ['name'=>"He is developing a better attitude but needs to actively participate in classroom discussion.",'gender'=>1],
            ['name'=>"She is developing a better attitude but needs to actively participate in classroom discussion.",'gender'=>2],
            ['name'=>"He is honest and trustworthy in dealings with others. He remains an active learner throughout the school day.",'gender'=>1],
            ['name'=>"She is honest and trustworthy in dealings with others. She remains an active learner throughout the school day.",'gender'=>2]
        ];

        foreach ($comments as $comment) {
            TeacherComment::firstOrCreate($comment);
        }
    }
}
