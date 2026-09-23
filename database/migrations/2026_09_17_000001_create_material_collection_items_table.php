<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialCollectionItemsTable extends Migration
{
    public function up()
    {
        Schema::create('material_collection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_collection_id')->constrained('material_collections')->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->boolean('is_collected')->default(false);
            $table->timestamp('collected_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_collection_items');
    }
}
