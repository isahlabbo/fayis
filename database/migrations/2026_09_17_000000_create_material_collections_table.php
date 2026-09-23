<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialCollectionsTable extends Migration
{
    public function up()
    {
        Schema::create('material_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_class_student_id')->constrained('section_class_students')->cascadeOnDelete();
            $table->foreignId('target_section_class_id')->constrained('section_classes')->cascadeOnDelete();
            $table->foreignId('academic_session_id')->nullable()->constrained('academic_sessions')->nullOnDelete();
            $table->string('status')->default('Pending');
            $table->string('receiver_name')->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['section_class_student_id', 'target_section_class_id', 'academic_session_id'], 'material_collections_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_collections');
    }
}
