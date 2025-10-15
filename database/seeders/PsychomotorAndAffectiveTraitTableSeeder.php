<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AffectiveTrait;
use App\Models\Psychomotor;

class PsychomotorAndAffectiveTraitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $psuchomotors = [
            'Handwriting',
            'Games',
            'Sports',
            'Drawing & Painting',
            'Crafts'
        ];
        foreach ($psuchomotors as $psychomotor) {
            Psychomotor::firstOrCreate(['name'=>$psychomotor]);
        }

        $affectiveTraits = [
            'Punctuality',
            'Attendance',
            'Reliability',
            'Neatness',
            'Politeness',
            'Honesty',
            'Relationship with Pupils',
            'Self-Control',
            'Attentiveness',
            'Perseverance'
        ];
        foreach ($affectiveTraits as $affectiveTrait) {
            AffectiveTrait::firstOrCreate(['name'=>$affectiveTrait]);
        }
    }
}
