<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionClassFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_class_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_class_id');
            $table->foreignId('fee_id');
            $table->timestamps();
        });

        foreach(App\Models\SectionClass::all() as $sectionClass){

            foreach(App\Models\Fee::all() as $fee){
                $sectionClass->sectionClassFees()->firstOrCreate(['fee_id'=>$fee->id]);
            }

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section_class_fees');
    }
}
