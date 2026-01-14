<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyReferenceOnSectionIdInCardRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_requests', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->foreignId('section_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_requests', function (Blueprint $table) {
            //
        });
    }
}
