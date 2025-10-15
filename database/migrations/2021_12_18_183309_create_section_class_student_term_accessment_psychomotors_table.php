<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionClassStudentTermAccessmentPsychomotorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_class_student_term_accessment_psychomotors', function (Blueprint $table) {
            $table->id();
            $table->integer('section_class_student_term_accessment_id')
            ->unsigned()
            ->nullable()
            ->foreign()
            ->references('id')
            ->on('section_class_student_term_accessments')
            ->delete('restrict')
            ->update('cascade');
            $table->integer('psychomotor_id')
            ->unsigned()
            ->nullable()
            ->foreign()
            ->references('id')
            ->on('psychomotors')
            ->delete('restrict')
            ->update('cascade');
            $table->integer('value');
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
        Schema::dropIfExists('section_class_student_term_accessment_psychomotors');
    }
}
