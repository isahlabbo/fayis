<?php

namespace Tests\Feature;

use App\Models\AdvancePayment;
use App\Models\FinanceActivityLog;
use App\Models\Payment;
use App\Models\User;
use App\Services\Finance\DeleteAdvancePayment;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class DeleteAdvancePaymentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'advance_test', 'database.connections.advance_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('fee_id');
            $table->integer('applied_payment_id')->nullable();
            $table->string('receipt_group');
            $table->decimal('amount');
            $table->decimal('applied_amount')->default(0);
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount');
            $table->string('receipt_group');
            $table->timestamps();
        });
        Schema::create('finance_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('activity_type');
            $table->string('reference_type')->nullable();
            $table->integer('reference_id')->nullable();
            $table->string('description');
            $table->decimal('amount');
            $table->text('metadata');
            $table->timestamps();
        });
        $user = \Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->shouldReceive('hasPermission')->with('manage-payments')->andReturn(true);
        $this->actingAs($user);
    }

    protected function tearDown(): void
    {
        DB::purge('advance_test');
        parent::tearDown();
    }

    private function advance(array $attributes = [])
    {
        return AdvancePayment::create(array_merge([
            'fee_id' => 1, 'receipt_group' => 'shared-receipt', 'amount' => 100,
        ], $attributes));
    }

    public function test_pending_entry_is_deleted_and_audited_without_deleting_other_receipt_entries()
    {
        $advance = $this->advance();
        $other = $this->advance();

        app(DeleteAdvancePayment::class)->handle($advance->id, 1);

        $this->assertNull($advance->fresh());
        $this->assertNotNull($other->fresh());
        $log = FinanceActivityLog::where('activity_type', 'advance_payment_deleted')->firstOrFail();
        $this->assertEquals(-100, $log->amount);
        $this->assertEquals($advance->id, $log->metadata['advance_payment']['id']);
    }

    /** @dataProvider appliedStatuses */
    public function test_all_applied_payments_are_reversed_and_unrelated_payments_remain($status, $amount)
    {
        $first = Payment::create(['amount' => 30, 'receipt_group' => 'shared-receipt']);
        $last = Payment::create(['amount' => 20, 'receipt_group' => 'shared-receipt']);
        $other = Payment::create(['amount' => 75, 'receipt_group' => 'shared-receipt']);
        $advance = $this->advance(['amount' => $amount, 'applied_amount' => 50,
            'status' => $status, 'applied_payment_id' => $last->id]);
        FinanceActivityLog::record('advance_payment_applied', $advance, 'Applied', 30, ['payment_id' => $first->id]);

        app(DeleteAdvancePayment::class)->handle($advance->id);

        $this->assertNull($advance->fresh());
        $this->assertNull($first->fresh());
        $this->assertNull($last->fresh());
        $this->assertNotNull($other->fresh());
        $this->assertEquals(75, Payment::sum('amount'));
    }

    public function appliedStatuses(): array
    {
        return [['Partially Applied', 100], ['Applied', 50]];
    }

    public function test_wrong_fee_cannot_be_deleted()
    {
        $advance = $this->advance();
        try {
            app(DeleteAdvancePayment::class)->handle($advance->id, 2);
            $this->fail('Expected forbidden response.');
        } catch (HttpException $exception) {
            $this->assertEquals(403, $exception->getStatusCode());
        }
        $this->assertNotNull($advance->fresh());
        $this->assertEquals(0, FinanceActivityLog::count());
    }

    public function test_user_without_permission_cannot_delete()
    {
        $advance = $this->advance();
        $user = \Mockery::mock(User::class)->makePartial();
        $user->id = 2;
        $user->shouldReceive('hasPermission')->with('manage-payments')->andReturn(false);
        $this->actingAs($user);
        try {
            app(DeleteAdvancePayment::class)->handle($advance->id);
            $this->fail('Expected forbidden response.');
        } catch (HttpException $exception) {
            $this->assertEquals(403, $exception->getStatusCode());
        }
        $this->assertNotNull($advance->fresh());
    }
}
