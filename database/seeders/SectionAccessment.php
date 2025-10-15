<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\AffectiveTrait;
use App\Models\Psychomotor;
class SectionAccessment extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $psychomotors = Psychomotor::all();
        $affectiveTraits = AffectiveTrait::all();
        foreach (Section::all() as $section) {
            foreach ($psychomotors as $psychomotor) {
                $section->psychomotors()->firstOrCreate(['name'=>$psychomotor->name]);
            }

            foreach ($affectiveTraits as $affectiveTrait) {
                $section->affectiveTraits()->firstOrCreate(['name'=>$affectiveTrait->name]);
            }
        }
        foreach ($psychomotors as $psychomotor) {
            $psychomotor->delete();
        }
        
    }
}
