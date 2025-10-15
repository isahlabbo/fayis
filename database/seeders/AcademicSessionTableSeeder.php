<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicSession;

class AcademicSessionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sessions = [
            '2025/2026',
        ];
        $count = 1;
        foreach ($sessions as $session) {
            $academicSession = AcademicSession::firstOrCreate(['name'=>$session]);
            if($count == 1){
                $academicSession->update(['status'=>'Active']);
                $count++;
            }
            foreach ([1,2,3] as $term_id) {
               if($count ==2 && $term_id == 1){
                    $academicSession->academicSessionTerms()->firstOrCreate(['term_id'=>$term_id,'status'=>'Active']);
               }else{
                    $academicSession->academicSessionTerms()->firstOrCreate(['term_id'=>$term_id]);
               } 
               $count++;
            }
        }
    }
}
