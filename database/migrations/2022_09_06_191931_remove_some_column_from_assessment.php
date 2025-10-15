<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSomeColumnFromAssessment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section_class_student_term_accessments', function (Blueprint $table) {
            $table->dropColumn('punctuality');
            $table->dropColumn('politeness');
            $table->dropColumn('honesty');
            $table->dropColumn('relationship_with_pupils');
            $table->dropColumn('self_control');
            $table->dropColumn('attentiveness');
            $table->dropColumn('neatness');
            $table->dropColumn('reliability');
            $table->dropColumn('Attendance');
            $table->dropColumn('perseverance');
            $table->dropColumn('handwriting');
            $table->dropColumn('games');
            $table->dropColumn('sports');
            $table->dropColumn('drawing_and_painting');
            $table->dropColumn('crafts');         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment', function (Blueprint $table) {
            //
        });
    }
}
