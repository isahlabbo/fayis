<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySectionClassFeeItemsNullableGender extends Migration
{
    public function up()
    {
        Schema::table('section_class_fee_items', function (Blueprint $table) {
            $table->foreignId('gender_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('section_class_fee_items', function (Blueprint $table) {
            $table->foreignId('gender_id')->nullable(false)->change();
        });
    }
}
