<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvancePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('applied_payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->uuid('receipt_group')->index();
            $table->decimal('amount', 16, 2);
            $table->decimal('applied_amount', 16, 2)->default(0);
            $table->string('mode');
            $table->date('date');
            $table->string('status')->default('Pending');
            $table->timestamps();
            $table->index(['student_id', 'academic_session_id', 'section_class_id', 'status'], 'advance_matching_index');
        });
    }

    public function down()
    {
        Schema::dropIfExists('advance_payments');
    }
}
