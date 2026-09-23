<?php

namespace Tests\Feature;

use App\Http\Livewire\Finance\Sanitary\Items;
use App\Http\Livewire\Finance\Sanitary\Stock;
use App\Http\Livewire\Finance\Sanitary\Usage;
use App\Models\FinanceActivityLog;
use App\Models\SanitaryItem;
use App\Models\SanitaryStock;
use App\Models\SanitaryUsage;
use App\Models\User;
use App\Services\Finance\SanitaryMaterials;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class SanitaryMaterialReadinessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sanitary_test', 'database.connections.sanitary_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
        ]]);
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->string('name');
        });
        DB::table('users')->insert(['id' => 1, 'name' => 'Storekeeper']);
        Schema::create('finance_activity_logs', function (Blueprint $table) {
            $table->id(); $table->integer('user_id')->nullable(); $table->string('activity_type');
            $table->string('reference_type')->nullable(); $table->integer('reference_id')->nullable();
            $table->string('description'); $table->decimal('amount', 16, 2); $table->text('metadata'); $table->timestamps();
        });
        require_once database_path('migrations/2026_09_23_000000_create_sanitary_material_tables.php');
        (new \CreateSanitaryMaterialTables())->up();
        $this->loginWithPermission(true);
    }

    private function loginWithPermission($allowed)
    {
        $user = \Mockery::mock(User::class)->makePartial();
        $user->forceFill(['id' => 1, 'name' => 'Storekeeper', 'email' => 'store@example.com',
            'role' => $allowed ? 'admin' : 'staff', 'status' => 'Active', 'password_updated' => 1, 'email_verified_at' => now()]);
        $user->setRelation('accessRoles', collect());
        $user->setRelation('directPermissions', collect());
        $user->shouldReceive('isSuperAdmin')->andReturn(false);
        $user->shouldReceive('hasPermission')->andReturnUsing(fn($permission) => $allowed && $permission === 'manage-inventory');
        $user->shouldReceive('hasAnyPermission')->andReturn($allowed);
        $user->shouldReceive('hasAnyAccessRole')->andReturn(false);
        $this->actingAs($user);
    }

    protected function tearDown(): void
    {
        DB::purge('sanitary_test');
        parent::tearDown();
    }

    private function item($name = 'Liquid soap')
    {
        return SanitaryItem::create(['name' => $name, 'unit' => 'bottle', 'reorder_level' => 2]);
    }

    private function stockData($item, array $changes = [])
    {
        return array_merge(['sanitary_item_id' => $item->id, 'quantity' => 10, 'unit_cost' => 25.50,
            'received_date' => now()->subDay()->toDateString(), 'supplier' => 'Local supplier',
            'reference' => 'INV-100', 'notes' => 'Initial batch'], $changes);
    }

    private function usageData($stock, array $changes = [])
    {
        return array_merge(['sanitary_item_id' => $stock->sanitary_item_id, 'sanitary_stock_id' => $stock->id,
            'quantity' => 3, 'usage_date' => now()->toDateString(), 'used_by' => 'Cleaning team',
            'location' => 'Hostel', 'notes' => 'Daily cleaning'], $changes);
    }

    private function assertInvalid($field, callable $action)
    {
        try {
            $action();
            $this->fail('Expected validation error for '.$field);
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey($field, $exception->errors());
        }
    }

    public function test_all_three_pages_render_with_the_dropdown_and_correct_links()
    {
        foreach (['items', 'stock', 'usage'] as $page) {
            $this->get(route('finance.sanitary.'.$page))->assertOk()
                ->assertSee('Sanitary Material')->assertSee(route('finance.sanitary.items'))
                ->assertSee(route('finance.sanitary.stock'))->assertSee(route('finance.sanitary.usage'));
        }
    }

    public function test_guest_and_unauthorized_users_cannot_access_pages_or_livewire_actions()
    {
        auth()->logout();
        $this->get(route('finance.sanitary.items'))->assertRedirect(route('login'));
        $this->loginWithPermission(false);
        foreach (['items', 'stock', 'usage'] as $page) {
            $this->get(route('finance.sanitary.'.$page))->assertRedirect(route('access.restricted'));
        }
        foreach ([Items::class, Stock::class, Usage::class] as $screen) {
            Livewire::test($screen)->assertForbidden();
        }
    }

    public function test_items_can_be_created_edited_searched_and_deleted()
    {
        $screen = Livewire::test(Items::class)->set('name', 'Liquid soap')->set('sku', 'SOAP')
            ->set('unit', 'bottle')->set('reorder_level', 5)->call('saveItem')->assertHasNoErrors()->assertSee('Liquid soap');
        $item = SanitaryItem::firstOrFail();
        $screen->call('editItem', $item->id)->set('name', 'Hand wash')->call('saveItem')->assertHasNoErrors();
        $this->assertEquals('Hand wash', $item->fresh()->name);
        $screen->set('search', 'missing')->assertSee('No sanitary items found')
            ->set('search', 'SOAP')->assertSee('Hand wash')->call('deleteItem', $item->id)->assertHasNoErrors();
        $this->assertNull($item->fresh());
    }

    public function test_item_validation_and_protection_of_stock_history()
    {
        $item = $this->item();
        Livewire::test(Items::class)->call('saveItem')->assertHasErrors(['name', 'unit'])
            ->set('name', $item->name)->set('unit', 'bottle')->call('saveItem')->assertHasErrors(['name']);
        app(SanitaryMaterials::class)->saveStock($this->stockData($item));
        Livewire::test(Items::class)->call('deleteItem', $item->id)->assertHasErrors(['item_delete']);
        $this->assertNotNull($item->fresh());
    }

    public function test_stock_screen_creates_edits_and_deletes_an_unused_batch()
    {
        $item = $this->item();
        $screen = Livewire::test(Stock::class);
        foreach ($this->stockData($item) as $key => $value) $screen->set($key, $value);
        $screen->call('saveStock')->assertHasNoErrors()->assertSee('Local supplier');
        $stock = SanitaryStock::firstOrFail();
        $this->assertEquals(10, $stock->remaining_quantity);
        $screen->call('editStock', $stock->id)->set('quantity', 15)->call('saveStock')->assertHasNoErrors();
        $this->assertEquals(15, $stock->fresh()->remaining_quantity);
        $screen->call('deleteStock', $stock->id)->assertHasNoErrors();
        $this->assertNull($stock->fresh());
    }

    public function test_usage_screen_deducts_corrects_and_restores_the_selected_batch_only()
    {
        $service = app(SanitaryMaterials::class);
        $item = $this->item();
        $stock = $service->saveStock($this->stockData($item));
        $other = $service->saveStock($this->stockData($item));
        $screen = Livewire::test(Usage::class);
        foreach ($this->usageData($stock) as $key => $value) $screen->set($key, $value);
        $screen->call('saveUsage')->assertHasNoErrors()->assertSee('Cleaning team');
        $usage = SanitaryUsage::firstOrFail();
        $this->assertEquals(7, $stock->fresh()->remaining_quantity);
        $this->assertEquals(25.50, $usage->unit_cost);
        $screen->call('editUsage', $usage->id)->set('quantity', 5)->call('saveUsage')->assertHasNoErrors();
        $this->assertEquals(5, $stock->fresh()->remaining_quantity);
        $screen->call('deleteUsage', $usage->id)->assertHasNoErrors();
        $this->assertEquals(10, $stock->fresh()->remaining_quantity);
        $this->assertEquals(10, $other->fresh()->remaining_quantity);
        $this->assertNull($usage->fresh());
        $this->assertEquals(1, FinanceActivityLog::where('activity_type', 'sanitary_usage_deleted')->count());
    }

    public function test_insufficient_stock_and_invalid_item_batch_pair_do_not_change_balances()
    {
        $service = app(SanitaryMaterials::class);
        $stock = $service->saveStock($this->stockData($this->item()));
        $other = $this->item('Mop');
        $this->assertInvalid('quantity', fn() => $service->saveUsage($this->usageData($stock, ['quantity' => 11])));
        $this->assertInvalid('sanitary_stock_id', fn() => $service->saveUsage($this->usageData($stock, ['sanitary_item_id' => $other->id])));
        $this->assertEquals(10, $stock->fresh()->remaining_quantity);
        $this->assertEquals(0, SanitaryUsage::count());
    }

    public function test_used_stock_cannot_be_deleted_or_reduced_below_consumption()
    {
        $service = app(SanitaryMaterials::class);
        $item = $this->item();
        $stock = $service->saveStock($this->stockData($item));
        $usage = $service->saveUsage($this->usageData($stock));
        $this->assertInvalid('stock_delete', fn() => $service->deleteStock($stock->id));
        $this->assertInvalid('quantity', fn() => $service->saveStock($this->stockData($item, ['quantity' => 2]), $stock->id));
        $service->saveStock($this->stockData($item, ['quantity' => 12, 'unit_cost' => 30]), $stock->id);
        $this->assertEquals(9, $stock->fresh()->remaining_quantity);
        $this->assertEquals(30, $usage->fresh()->unit_cost);
    }

    public function test_dates_and_positive_whole_quantities_are_validated()
    {
        $service = app(SanitaryMaterials::class);
        $item = $this->item();
        $stock = $service->saveStock($this->stockData($item));
        foreach ([0, -1, 1.5] as $quantity) {
            $this->assertInvalid('quantity', fn() => $service->saveUsage($this->usageData($stock, ['quantity' => $quantity])));
            $this->assertInvalid('quantity', fn() => $service->saveStock($this->stockData($item, ['quantity' => $quantity])));
        }
        $this->assertInvalid('usage_date', fn() => $service->saveUsage($this->usageData($stock, ['usage_date' => now()->subDays(2)->toDateString()])));
        $this->assertInvalid('usage_date', fn() => $service->saveUsage($this->usageData($stock, ['usage_date' => now()->addDay()->toDateString()])));
        $this->assertInvalid('unit_cost', fn() => $service->saveStock($this->stockData($item, ['unit_cost' => -1])));
    }

    public function test_fully_consumed_batch_can_be_corrected_and_overuse_on_edit_is_blocked()
    {
        $service = app(SanitaryMaterials::class);
        $stock = $service->saveStock($this->stockData($this->item()));
        $usage = $service->saveUsage($this->usageData($stock, ['quantity' => 10]));
        $this->assertEquals(0, $stock->fresh()->remaining_quantity);
        Livewire::test(Usage::class)->call('editUsage', $usage->id)->assertSee('0 remaining')
            ->set('quantity', 11)->call('saveUsage')->assertHasErrors(['quantity'])
            ->set('quantity', 4)->call('saveUsage')->assertHasNoErrors();
        $this->assertEquals(6, $stock->fresh()->remaining_quantity);
    }

    public function test_filters_and_summaries_reflect_selected_item_and_day()
    {
        $service = app(SanitaryMaterials::class);
        $stock = $service->saveStock($this->stockData($this->item()));
        $other = $service->saveStock($this->stockData($this->item('Mop')));
        $service->saveUsage($this->usageData($stock));
        $service->saveUsage($this->usageData($other, ['used_by' => 'Other team']));
        Livewire::test(Usage::class)->set('filterItem', $stock->sanitary_item_id)
            ->set('fromDate', now()->toDateString())->set('toDate', now()->toDateString())
            ->assertSee('Cleaning team')->assertDontSee('Other team')
            ->assertViewHas('summary', fn($summary) => $summary->quantity == 3 && $summary->value == 76.50)
            ->set('search', 'No matching person')->assertSee('No usage records match');
        Livewire::test(Stock::class)->set('filterItem', $stock->sanitary_item_id)
            ->assertViewHas('summary', fn($summary) => $summary->available == 7 && $summary->value == 178.50);
    }

    public function test_audit_failure_rolls_back_usage_and_stock_changes()
    {
        $service = app(SanitaryMaterials::class);
        $stock = $service->saveStock($this->stockData($this->item()));
        Schema::drop('finance_activity_logs');
        try {
            $service->saveUsage($this->usageData($stock));
            $this->fail('Expected missing audit table to reject the transaction.');
        } catch (\Illuminate\Database\QueryException $exception) {
            $this->assertEquals(10, $stock->fresh()->remaining_quantity);
            $this->assertEquals(0, SanitaryUsage::count());
        }
    }

    public function test_existing_usage_cannot_be_moved_to_another_batch_or_item()
    {
        $service = app(SanitaryMaterials::class);
        $item = $this->item();
        $stock = $service->saveStock($this->stockData($item));
        $otherItem = $this->item('Disinfectant');
        $other = $service->saveStock($this->stockData($otherItem));
        $usage = $service->saveUsage($this->usageData($stock));
        $this->assertInvalid('sanitary_stock_id', fn() => $service->saveUsage($this->usageData($other), $usage->id));
        $this->assertInvalid('sanitary_item_id', fn() => $service->saveStock($this->stockData($otherItem), $stock->id));
        $this->assertEquals(7, $stock->fresh()->remaining_quantity);
        $this->assertEquals(10, $other->fresh()->remaining_quantity);
        $this->assertEquals($stock->id, $usage->fresh()->sanitary_stock_id);
    }

    public function test_receipt_date_cannot_be_moved_after_existing_usage()
    {
        $service = app(SanitaryMaterials::class);
        $item = $this->item();
        $stock = $service->saveStock($this->stockData($item));
        $service->saveUsage($this->usageData($stock, ['usage_date' => now()->subDay()->toDateString()]));
        $this->assertInvalid('received_date', fn() => $service->saveStock(
            $this->stockData($item, ['received_date' => now()->toDateString()]), $stock->id));
        $this->assertEquals(now()->subDay()->toDateString(), $stock->fresh()->received_date->toDateString());
    }

    public function test_permission_is_rechecked_on_subsequent_livewire_requests()
    {
        $screen = Livewire::test(Items::class)->set('name', 'Soap')->set('unit', 'bottle');
        $this->loginWithPermission(false);
        $screen->call('saveItem')->assertForbidden();
        $this->assertEquals(0, SanitaryItem::count());
    }

    public function test_inactive_inventory_user_cannot_open_a_component()
    {
        auth()->user()->status = 'Inactive';
        Livewire::test(Stock::class)->assertForbidden();
    }

    /** @dataProvider monitoringRoles */
    public function test_monitoring_roles_can_view_but_cannot_manage($role, $databaseRole)
    {
        $service = app(SanitaryMaterials::class);
        $item = $this->item();
        $stock = $service->saveStock($this->stockData($item));
        $usage = $service->saveUsage($this->usageData($stock));
        $user = auth()->user();
        $user->role = $databaseRole ? 'staff' : $role;
        if ($databaseRole) {
            $user->setRelation('accessRoles', collect([(new \App\Models\Role())->forceFill(['slug' => $role])
                ->setRelation('permissions', collect())]));
        }
        foreach (['items', 'stock', 'usage'] as $page) {
            $this->get(route('finance.sanitary.'.$page))->assertOk()->assertSee('Sanitary Material')
                ->assertDontSee('wire:submit.prevent', false)->assertDontSee('>Delete</button>', false)
                ->assertDontSee('>Edit</button>', false);
        }
        Livewire::test(Usage::class)->set('filterItem', $item->id)->set('search', 'Cleaning')
            ->assertSee('Cleaning team')->assertViewHas('summary', fn($summary) => $summary->quantity == 3);
        foreach ([
            [Items::class, 'saveItem', []], [Items::class, 'editItem', [$item->id]], [Items::class, 'deleteItem', [$item->id]],
            [Stock::class, 'saveStock', []], [Stock::class, 'editStock', [$stock->id]], [Stock::class, 'deleteStock', [$stock->id]],
            [Usage::class, 'saveUsage', []], [Usage::class, 'editUsage', [$usage->id]], [Usage::class, 'deleteUsage', [$usage->id]],
        ] as [$screen, $method, $arguments]) {
            Livewire::test($screen)->call($method, ...$arguments)->assertForbidden();
        }
        foreach ([
            fn() => $service->saveStock($this->stockData($item)),
            fn() => $service->deleteStock($stock->id),
            fn() => $service->saveUsage($this->usageData($stock)),
            fn() => $service->deleteUsage($usage->id),
        ] as $action) {
            try {
                $action();
                $this->fail('Monitoring users must not be able to mutate records.');
            } catch (\Symfony\Component\HttpKernel\Exception\HttpException $exception) {
                $this->assertEquals(403, $exception->getStatusCode());
            }
        }
        $this->assertEquals(7, $stock->fresh()->remaining_quantity);
        $this->assertEquals(1, SanitaryItem::count());
        $this->assertEquals(1, SanitaryUsage::count());
    }

    public function monitoringRoles(): array
    {
        return [['head', false], ['mentor', false], ['head', true], ['mentor', true]];
    }

    public function test_inventory_permission_alone_no_longer_grants_sanitary_access()
    {
        auth()->user()->role = 'finance_officer';
        foreach (['items', 'stock', 'usage'] as $page) {
            $this->get(route('finance.sanitary.'.$page))->assertRedirect(route('access.restricted'));
        }
        Livewire::test(Items::class)->assertForbidden();
        $this->assertFalse(\App\Support\SanitaryAccess::canManage(auth()->user()));
    }

    public function test_database_assigned_administrator_can_manage_without_inventory_permission()
    {
        $this->loginWithPermission(false);
        auth()->user()->setRelation('accessRoles', collect([
            (new \App\Models\Role())->forceFill(['slug' => 'admin'])->setRelation('permissions', collect()),
        ]));
        Livewire::test(Items::class)->set('name', 'Soap')->set('unit', 'bottle')->call('saveItem')->assertHasNoErrors();
        $this->assertEquals(1, SanitaryItem::count());
        $this->get(route('finance.sanitary.items'))->assertOk()->assertSee('Add item');
    }

    /** @dataProvider excludedSuperAdminRoles */
    public function test_super_administrators_are_excluded_even_with_other_roles($legacyRole, $roles)
    {
        $user = new User();
        $user->forceFill(['id' => 1, 'name' => 'Super administrator', 'email' => 'super@example.com',
            'role' => $legacyRole, 'status' => 'Active', 'email_verified_at' => now()]);
        $user->setRelation('accessRoles', collect($roles)->map(fn($slug) =>
            (new \App\Models\Role())->forceFill(['slug' => $slug])->setRelation('permissions', collect())));
        $user->setRelation('directPermissions', collect());
        $this->actingAs($user);

        $this->assertFalse(\App\Support\SanitaryAccess::canView($user));
        $this->assertFalse(\App\Support\SanitaryAccess::canManage($user));
        foreach (['items', 'stock', 'usage'] as $page) {
            $this->get(route('finance.sanitary.'.$page))->assertRedirect(route('access.restricted'));
        }
        foreach ([Items::class, Stock::class, Usage::class] as $screen) {
            Livewire::test($screen)->assertForbidden();
        }
        $this->assertStringNotContainsString('finance.sanitary.', view('menu.superadmin-sidebar')->render());
        try {
            app(SanitaryMaterials::class)->saveStock([]);
            $this->fail('Super administrators must not manage sanitary stock.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $exception) {
            $this->assertEquals(403, $exception->getStatusCode());
        }
    }

    public function excludedSuperAdminRoles(): array
    {
        return [
            ['superadmin', []],
            ['staff', ['superadmin']],
            ['admin', ['superadmin']],
            ['head', ['superadmin']],
            ['mentor', ['superadmin']],
        ];
    }

    public function test_migration_rolls_back_only_sanitary_tables()
    {
        (new \CreateSanitaryMaterialTables())->down();
        $this->assertFalse(Schema::hasTable('sanitary_items'));
        $this->assertFalse(Schema::hasTable('sanitary_stocks'));
        $this->assertFalse(Schema::hasTable('sanitary_usages'));
        $this->assertTrue(Schema::hasTable('users'));
    }
}
