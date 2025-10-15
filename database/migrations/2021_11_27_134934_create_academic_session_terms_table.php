<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicSessionTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_session_terms', function (Blueprint $table) {
            $table->id();
            $table->integer('term_id')
            ->unsigned()
            ->nullable()
            ->foreign()
            ->references('id')
            ->on('terms')
            ->delete('restrict')
            ->update('cascade');
            $table->integer('academic_session_id')
            ->unsigned()
            ->nullable()
            ->foreign()
            ->references('id')
            ->on('academic_sessions')
            ->delete('restrict')
            ->update('cascade');
            $table->string('status')->default('Not Active');
            $table->string('start_at')->nullable();
            $table->string('end_at')->nullable();
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
        Schema::dropIfExists('academic_session_terms');
    }
}
