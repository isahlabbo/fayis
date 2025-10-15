<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionClassSubjectUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_class_subject_uploads', function (Blueprint $table) {
            $table->id();
            $table->integer('section_class_subject_id')
            ->unsigned()
            ->nullable()
            ->foreign()
            ->references('id')
            ->on('section_class_subjects')
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
        Schema::dropIfExists('section_class_subject_uploads');
    }
}
