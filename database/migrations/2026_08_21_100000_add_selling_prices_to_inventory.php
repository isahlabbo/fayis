<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
class AddSellingPricesToInventory extends Migration
{
 public function up(){Schema::table('inventory_items',fn(Blueprint $t)=>$t->decimal('selling_price',14,2)->default(0)->after('unit_cost'));Schema::table('inventory_stocks',fn(Blueprint $t)=>$t->decimal('unit_selling_price',14,2)->default(0)->after('unit_cost'));Schema::table('inventory_sale_items',fn(Blueprint $t)=>$t->decimal('cost_price',14,2)->default(0)->after('unit_cost'));}
 public function down(){Schema::table('inventory_sale_items',fn(Blueprint $t)=>$t->dropColumn('cost_price'));Schema::table('inventory_stocks',fn(Blueprint $t)=>$t->dropColumn('unit_selling_price'));Schema::table('inventory_items',fn(Blueprint $t)=>$t->dropColumn('selling_price'));}
}
