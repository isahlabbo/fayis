<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyValueFromSectionClassStudentTermAccessmentAffectiveTraits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section_class_student_term_accessment_affective_traits', function (Blueprint $table) {
            $table->integer('value')->nullable()->change();
        });
        
        Schema::table('section_class_student_term_accessment_psychomotors', function (Blueprint $table) {
            $table->integer('value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section_class_student_term_accessment_affective_traits', function (Blueprint $table) {
            //
        });
    }
}
