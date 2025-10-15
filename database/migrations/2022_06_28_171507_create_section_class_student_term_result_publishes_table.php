<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionClassStudentTermResultPublishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_class_student_term_result_publishes', function (Blueprint $table) {
            $table->id();
            $table->integer('section_class_student_term_id')
            ->unsign()
            ->nullable()
            ->foreign()
            ->refrencies('id')
            ->on('section_class_student_terms');
            $table->string('class_average')->nullable();
            $table->string('student_average')->nullable();
            $table->string('total_marks')->nullable();
            $table->string('obtain_marks')->nullable();
            $table->string('position')->nullable();
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
        Schema::dropIfExists('section_class_student_term_result_publishes');
    }
}
