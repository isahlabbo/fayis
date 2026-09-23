<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSanitaryMaterialTables extends Migration
{
    public function up()
    {
        Schema::create('sanitary_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('sku', 100)->nullable()->unique();
            $table->string('unit', 50);
            $table->unsignedInteger('reorder_level')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        Schema::create('sanitary_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanitary_item_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('remaining_quantity');
            $table->decimal('unit_cost', 16, 2);
            $table->date('received_date')->index();
            $table->string('supplier')->nullable();
            $table->string('reference', 100)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
        Schema::create('sanitary_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanitary_stock_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_cost', 16, 2);
            $table->date('usage_date')->index();
            $table->string('used_by');
            $table->string('location');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sanitary_usages');
        Schema::dropIfExists('sanitary_stocks');
        Schema::dropIfExists('sanitary_items');
    }
}
