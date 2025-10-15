<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionClassStudentRepeatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_class_student_repeatings', function (Blueprint $table) {
            $table->id();
            $table->integer('section_class_student_id')
            ->unsign()
            ->nullable()
            ->foreign()
            ->refrencies('id')
            ->on('section_class_students');
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
        Schema::dropIfExists('section_class_student_repeatings');
    }
}
