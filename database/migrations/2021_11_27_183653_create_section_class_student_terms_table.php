<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionClassStudentTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_class_student_terms', function (Blueprint $table) {
            $table->id();
            $table->integer('section_class_student_id')
            ->unsigned()
            ->nullable()
            ->foreaeign()
            ->references('id')
            ->on('section_class_students')
            ->delete('restrict')
            ->update('cascade');
            $table->integer('academic_session_term_id')
            ->unsigned()
            ->nullable()
            ->foreign()
            ->references('id')
            ->on('academic_session_terms')
            ->delete('restrict')
            ->update('cascade');
            $table->string('status')->default('Not Active');
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
        Schema::dropIfExists('section_class_student_terms');
    }
}
