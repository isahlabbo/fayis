<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
class LinkInventorySalesToStockBatches extends Migration
{
 public function up(){Schema::table('inventory_sale_items',fn(Blueprint $t)=>$t->foreignId('inventory_stock_id')->nullable()->after('inventory_item_id')->constrained('inventory_stocks')->nullOnDelete());}
 public function down(){Schema::table('inventory_sale_items',function(Blueprint $t){$t->dropForeign(['inventory_stock_id']);$t->dropColumn('inventory_stock_id');});}
}
