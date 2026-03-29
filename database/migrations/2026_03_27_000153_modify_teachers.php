<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTeachers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('marital_status')->nullable();
            $table->string('date_of_appointment')->nullable();
            $table->string('appointment_grade_level')->nullable();
            $table->string('present_grade_level')->nullable();
            $table->string('comment')->nullable();
            $table->string('lga_id')->nullable();
            $table->string('trcn')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
