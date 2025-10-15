<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SectionClassSubject;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name'=>'Admin',
                'email'=>'admin@smmiqgs.ng',
                'password'=>Hash::make('admin'),
                'role'=>'admin',
            ],
            [
                'name'=>'Head',
                'email'=>'head@fayis.ng',
                'password'=>Hash::make('head'),
                'role'=>'head',
            ],
            [
                'name'=>'Admission Officer',
                'email'=>'admission@fayis.ng',
                'password'=>Hash::make('admission'),
                'role'=>'admission_officer',
            ],
            [
                'name'=>'Financial Officer',
                'email'=>'finance@fayis.ng',
                'password'=>Hash::make('finance'),
                'role'=>'finance_officer',
            ],
            [
                'name'=>'Exam Officer',
                'email'=>'exam@fayis.ng',
                'password'=>Hash::make('exam'),
                'role'=>'exam_officer',
            ],
        ];
        
        foreach($users as $user){
            User::create($user);
        }
        
    }
}
