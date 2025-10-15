<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionClassSubjectTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_class_subject_teachers', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id')
            ->unsigned()
            ->nullable()
            ->foreign()
            ->references('id')
            ->on('teachers')
            ->delete('restrict')
            ->update('cascade');
            $table->integer('section_class_subject_id')
            ->unsigned()
            ->nullable()
            ->foreign()
            ->references('id')
            ->on('section_class_subjects')
            ->delete('restrict')
            ->update('cascade');
            $table->string('status')->default('Active');
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
        Schema::dropIfExists('section_class_subject_teachers');
    }
}
