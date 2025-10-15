<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Term;

class TermTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $terms = ['First Term', 'Second Term','Third Term'];
        foreach ($terms as $term) {
            Term::firstOrCreate(['name'=>$term]);
        }
    }
}
