<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('section_classes', function (Blueprint $table) {
            $table->integer('result_type_id')
            ->unsign()
            ->default(1)
            ->foreign()
            ->refrencies('id')
            ->on('result_types');
        });

        foreach(['Position', 'Remark'] as $result){
            App\Models\ResultType::firstOrCreate(['name'=>$result]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('result_types');
    }
}
