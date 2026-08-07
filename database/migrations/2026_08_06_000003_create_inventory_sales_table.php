<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_class_student_id')->constrained('section_class_students')->cascadeOnDelete();
            $table->decimal('total_cost', 16, 2)->default(0);
            $table->text('evidence')->nullable();
            $table->date('usage_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_sales');
    }
};
