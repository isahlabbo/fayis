<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentralAndDisperseResultMeasuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('central_and_disperse_result_measures', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('academic_session_id')->constrained()->cascadeOnDelete();
        //     $table->foreignId('term_id')->constrained()->cascadeOnDelete();
        //     $table->foreignId('section_id')->constrained()->cascadeOnDelete();
        //     $table->foreignId('section_class_id')->constrained()->cascadeOnDelete();
        //     $table->foreignId('subject_teacher_termly_upload_id')->constrained()->cascadeOnDelete();

        //     $table->integer('students_count');

        //     // Central tendency
        //     $table->decimal('mean', 5, 2);
        //     $table->decimal('median', 5, 2)->nullable();
        //     $table->decimal('mode', 5, 2)->nullable();

        //     // Dispersion
        //     $table->decimal('min_score', 5, 2);
        //     $table->decimal('max_score', 5, 2);
        //     $table->decimal('range', 5, 2);
        //     $table->decimal('variance', 8, 4)->nullable();
        //     $table->decimal('standard_deviation', 8, 4)->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('central_and_disperse_result_measures');
    }
}
