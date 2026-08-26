<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendFinanceWorkflows extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) { $table->uuid('receipt_group')->nullable()->index(); });
        Schema::table('inventory_rents', function (Blueprint $table) {
            $table->foreignId('academic_session_id')->nullable(); $table->integer('returned_quantity')->default(0);
            $table->date('returned_at')->nullable(); $table->string('status')->default('Rented');
        });
        Schema::create('finance_activity_logs', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->nullable(); $table->string('activity_type');
            $table->string('reference_type')->nullable(); $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description'); $table->decimal('amount',16,2)->default(0); $table->json('metadata')->nullable(); $table->timestamps();
            $table->index(['activity_type','created_at']);
        });
    }
    public function down()
    {
        Schema::dropIfExists('finance_activity_logs');
        Schema::table('inventory_rents', fn(Blueprint $table) => $table->dropColumn(['academic_session_id','returned_quantity','returned_at','status']));
        Schema::table('payments', fn(Blueprint $table) => $table->dropColumn('receipt_group'));
    }
}
