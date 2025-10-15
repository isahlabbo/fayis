<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SectionClassGroup;
use App\Models\Section;

class SectionClassGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['A','B'] as $classGroup) {
            SectionClassGroup::firstOrCreate(['name'=>$classGroup]);
        }

        
    }
}
