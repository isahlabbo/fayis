<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdmissionWorkflowToStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('desired_section_class_id')->nullable()->after('academic_session_id');
            $table->string('admission_status')->default('Pending')->after('desired_section_class_id');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['desired_section_class_id', 'admission_status']);
        });
    }
}
