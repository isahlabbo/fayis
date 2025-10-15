<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('pass_mark')->default(50);
            $table->foreignId('section_id');
            $table->integer('capacity')->default('20');
            $table->string('year_sequence');
            $table->string('code');
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
        Schema::dropIfExists('section_classes');
    }
}
