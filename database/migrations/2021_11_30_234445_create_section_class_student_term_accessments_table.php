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
            $table->integer('section_class_student_term_id')
            ->unsigned()
            ->nullable()
            ->foreign()
            ->references('id')
            ->on('section_class_student_terms')
            ->delete('restrict')
            ->update('cascade');
            $table->integer('teacher_comment_id')
            ->unsigned()
            ->nullable()
            ->foreign()
            ->references('id')
            ->on('teacher_comments')
            ->delete('restrict')
            ->update('cascade');
            $table->integer('head_teacher_comment_id')
            ->unsigned()
            ->nullable()
            ->foreign()
            ->references('id')
            ->on('head_teacher_comments')
            ->delete('restrict')
            ->update('cascade');
            $table->integer('punctuality');
            $table->integer('Attendance');
            $table->integer('reliability');
            $table->integer('neatness');
            $table->integer('politeness');
            $table->integer('honesty');
            $table->integer('relationship_with_pupils');
            $table->integer('self_control');
            $table->integer('attentiveness');
            $table->integer('perseverance');
            $table->integer('handwriting');
            $table->integer('games');
            $table->integer('sports');
            $table->integer('drawing_and_painting');
            $table->integer('crafts');
            $table->integer('days_school_open');
            $table->integer('days_present');
            $table->integer('days_absent');
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
