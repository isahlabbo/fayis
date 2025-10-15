<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectTeacherTermlyUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_teacher_termly_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_class_subject_teacher_id');
            $table->foreignId('term_id');
            $table->foreignId('academic_session_term_id');
            $table->string('average')->nullable();
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
        Schema::dropIfExists('subject_teacher_termly_uploads');
    }
}
