<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentPromotionsTable extends Migration
{
    public function up()
    {
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_enrolment_id')->constrained('section_class_students')->cascadeOnDelete();
            $table->foreignId('to_enrolment_id')->constrained('section_class_students')->cascadeOnDelete();
            $table->foreignId('promoted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->unique(['from_enrolment_id', 'to_enrolment_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_promotions');
    }
}
