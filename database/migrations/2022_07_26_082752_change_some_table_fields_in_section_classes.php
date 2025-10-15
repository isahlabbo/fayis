<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSomeTableFieldsInSectionClasses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section_classes', function (Blueprint $table) {
            $table->integer('result_type_id')
            ->unsign()
            ->nullable()
            ->foreign()
            ->refrencies('id')
            ->on('result_types')->change();
            $table->integer('pass_mark')->nullable()->change();
            $table->integer('capacity')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section_classes', function (Blueprint $table) {
            //
        });
    }
}
