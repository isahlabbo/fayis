<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_letters', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->default("LETTER OF PROVISSIONAL ADMISSION IN TO");
            $table->string('introduction_start')->default("I write this letter to inform you that your child application to");
            $table->string('introduction_contenue')->default("class of");
            $table->string('introduction_end')->default("Years has been successfully offer you 
            a provisional admission with admission no");
            $table->string('payment_note_start')->default("You are expected to pay non-refundable sum of");
            $table->string('payment_note_contenue')->default("Naira, Also the instalmental payment of at least ");
            $table->string('payment_note_end')->default("and above also accepted by the administration. Additionally, your child is expected to obey the school rules and regulations, 
            where by late coming to the school would not be entertain.");
            $table->string('congratulatory_note')->default("once again, we wish your child success in your study and enjoy 
            you stay.");
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
        Schema::dropIfExists('admission_letters');
    }
}
