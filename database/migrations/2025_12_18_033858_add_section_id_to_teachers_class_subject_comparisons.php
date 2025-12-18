<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSectionIdToTeachersClassSubjectComparisons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachers_class_subject_comparisons', function (Blueprint $table) {
            $table->foreignId('section_id')->constrained()->cascadeOnDelete()->nullab1le()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers_class_subject_comparisons', function (Blueprint $table) {
            //
        });
    }
}
