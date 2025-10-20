<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemaneAcademicSessionTermAcademicSessionIdInSubjectTeacherTermlyUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subject_teacher_termly_uploads', function (Blueprint $table) {
            $table->renameColumn('academic_session_term_id', 'academic_session_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subject_teacher_termly_uploads', function (Blueprint $table) {
            //
        });
    }
}
