<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissingInvoicesTable extends Migration
{
    public function up()
    {
        // Older installations may already have this table from a database import.
        if (Schema::hasTable('invoices')) {
            return;
        }

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_class_student_term_id')->nullable()->index();
            $table->foreignId('academic_session_id')->nullable()->index();
            $table->string('title')->nullable();
            $table->string('number')->nullable();
            $table->decimal('amount', 16, 2)->default(0);
            $table->string('status')->default('unpaid');
            $table->timestamps();
        });
    }

    public function down()
    {
        // Preserve financial records, including tables that predate this migration.
    }
}
