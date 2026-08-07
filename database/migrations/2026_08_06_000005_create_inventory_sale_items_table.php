<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_sale_id')->constrained('inventory_sales')->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->decimal('amount', 16, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_sale_items');
    }
};
