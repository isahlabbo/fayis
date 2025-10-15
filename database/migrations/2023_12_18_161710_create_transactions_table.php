<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id');
            $table->foreignId('invoice_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('tx_ref')->nullable();
            $table->string('flw_ref')->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->string('amount')->nullable();
            $table->string('currency')->nullable();
            $table->string('charged_amount')->nullable();
            $table->string('app_fee')->nullable();
            $table->string('merchant_fee')->nullable();
            $table->string('processor_response')->nullable();
            $table->string('auth_model')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('narration')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('account_id')->nullable();
            $table->string('amount_settled')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
