<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySomeAnalyitics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('termly_teacher_effective_indices', function (Blueprint $table) {
            $table->decimal('average_class_score', 5, 2)->default(0)->after('effectiveness_index');
            $table->foreignId('section_id')->after('term_id');
        });

        Schema::table('termly_class_averagings', function (Blueprint $table) {
            $table->decimal('average_teacher_effectiveness', 5, 2)->default(0)->after('class_average');
            $table->foreignId('section_id')->after('term_id');
        });

        Schema::table('termly_subject_evaluations', function (Blueprint $table) {
            $table->decimal('average_teacher_effectiveness', 5, 2)->default(0)->after('average');
            $table->foreignId('section_id')->after('term_id');
        });

        Schema::table('teachers_class_subject_comparisons', function (Blueprint $table) {
            $table->decimal('teacher_effectiveness_index', 5, 2)->default(0)->after('percentage');
        });

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
