<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionClassTermlyExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_class_termly_exams', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_session_id')
            ->unsign()
            ->nullable()
            ->foreign()
            ->refrencies('id')
            ->on('academic_sessions');
            $table->integer('section_class_id')
            ->unsign()
            ->nullable()
            ->foreign()
            ->refrencies('id')
            ->on('section_classes');
            $table->integer('academic_session_term_id')
            ->unsign()
            ->nullable()
            ->foreign()
            ->refrencies('id')
            ->on('academic_session_terms');
            $table->timestamps();
            $table->string('status')->default('Active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section_class_termly_exams');
    }
}
