<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
class AddPaymentMethodToInventorySales extends Migration
{
 public function up(){Schema::table('inventory_sales',fn(Blueprint $t)=>$t->string('payment_method')->default('Cash')->after('total_cost'));}
 public function down(){Schema::table('inventory_sales',fn(Blueprint $t)=>$t->dropColumn('payment_method'));}
}
