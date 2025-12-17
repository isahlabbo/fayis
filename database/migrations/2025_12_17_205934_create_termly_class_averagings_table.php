<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermlyClassAveragingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('termly_class_averagings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->constrained()->cascadeOnDelete();

            $table->integer('students_count')->default(0);
            $table->integer('subjects_count')->default(0);

            $table->decimal('total_obtained', 10, 2)->default(0);
            $table->decimal('total_possible', 10, 2)->default(0);
            $table->decimal('class_average', 5, 2)->default(0);
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
        Schema::dropIfExists('termly_class_averagings');
    }
}
