<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionStudentGraduationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_student_graduations', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id')
            ->unsign()
            ->nullable()
            ->foreign()
            ->refrencies('id')
            ->on('sections');
            $table->integer('student_id')
            ->unsign()
            ->nullable()
            ->foreign()
            ->refrencies('id')
            ->on('students');
            $table->integer('academic_session_id')
            ->unsign()
            ->nullable()
            ->foreign()
            ->refrencies('id')
            ->on('academic_sessions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section_student_graduations');
    }
}
