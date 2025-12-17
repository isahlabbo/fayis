<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermlyTeacherEffectiveIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('termly_teacher_effective_indices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->constrained()->cascadeOnDelete();
            $table->integer('total_students')->default(0);
            $table->integer('total_subjects')->default(0);
            $table->integer('total_classes')->default(0);
            $table->decimal('total_obtained', 10, 2)->default(0);
            $table->decimal('total_possible', 10, 2)->default(0);
            $table->decimal('effectiveness_index', 5, 2)->default(0);
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
        Schema::dropIfExists('termly_teacher_effective_indices');
    }
}
