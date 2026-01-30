<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $subjects = [
            'National Values',
            'Basic Science & Technology',
            'Pre-Vocational', 
        ];

        foreach ($subjects as $subjectName) {
            \App\Models\Subject::firstOrCreate(['name' => $subjectName]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
