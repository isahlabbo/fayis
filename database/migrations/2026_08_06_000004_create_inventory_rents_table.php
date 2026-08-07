<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_rents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->date('usage_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('inventory_rents');
    }
};
