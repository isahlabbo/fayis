<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionClassStudentTermAccessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_class_student_term_accessments', function (Blueprint $table) {
            $table->id();
            $table->integer('section_class_student_term_id');
            $table->integer('teacher_comment_id');
            $table->foreignId('head_teacher_comment_id');
            $table->integer('days_school_open')->nullable();
            $table->integer('days_present')->nullable();
            $table->integer('days_absent')->nullable();
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
        Schema::dropIfExists('section_class_student_term_accessments');
    }
}
