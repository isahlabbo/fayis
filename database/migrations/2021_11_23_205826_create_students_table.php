<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guardian_id')->nullable();
            $table->text('picture')->nullable();
            $table->foreignId('academic_session_id');
            $table->foreignId('gender_id')->nullable();
            $table->foreignId('lga_id')->nullable();
            $table->string('name');
            $table->string('date_of_birth')->nullable();
            $table->string('admission_no')->nullable();
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
        Schema::dropIfExists('students');
    }
}
