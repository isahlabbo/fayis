<?php

namespace Tests\Feature;

use App\Http\Livewire\Finance\Payments\Collect;
use App\Models\AdvancePayment;
use App\Models\FinanceActivityLog;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\SectionClassStudent;
use App\Models\User;
use App\Services\Finance\ApplyAdvancePayments;
use App\Services\Finance\RecordFeePayment;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class FinanceUnpaidReportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'unpaid_test', 'database.connections.unpaid_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
        ]]);
        foreach (['users', 'fees', 'sections', 'terms', 'students', 'section_classes', 'academic_sessions',
            'academic_session_terms', 'section_class_students', 'section_class_fees', 'section_class_fee_items'] as $name) {
            Schema::create($name, function (Blueprint $table) use ($name) {
                $table->id(); $table->string('name')->nullable(); $table->timestamps();
                if ($name === 'students') { $table->string('admission_no'); $table->integer('gender_id'); }
                if ($name === 'section_classes') $table->integer('section_id');
                if (in_array($name, ['academic_sessions', 'academic_session_terms', 'section_class_students'])) $table->string('status')->default('Active');
                if ($name === 'section_class_students') {
                    $table->integer('student_id'); $table->integer('section_class_id'); $table->integer('academic_session_id');
                }
                if ($name === 'academic_session_terms') { $table->integer('academic_session_id'); $table->integer('term_id'); }
                if ($name === 'section_class_fees') { $table->integer('section_class_id'); $table->integer('fee_id'); }
                if ($name === 'section_class_fee_items') {
                    $table->integer('section_class_fee_id'); $table->integer('term_id');
                    $table->integer('gender_id')->nullable(); $table->decimal('amount', 16, 2);
                }
            });
        }
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); $table->integer('section_class_student_id'); $table->integer('section_class_fee_id');
            $table->integer('academic_session_id'); $table->integer('term_id'); $table->integer('user_id');
            $table->string('mode'); $table->string('date'); $table->string('amount');
            $table->string('receipt_group')->nullable(); $table->timestamps();
        });
        Schema::create('finance_activity_logs', function (Blueprint $table) {
            $table->id(); $table->integer('user_id')->nullable(); $table->string('activity_type');
            $table->string('reference_type')->nullable(); $table->integer('reference_id')->nullable();
            $table->string('description'); $table->decimal('amount', 16, 2); $table->text('metadata'); $table->timestamps();
        });
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id(); $table->integer('student_id'); $table->integer('academic_session_id');
            $table->integer('section_class_id'); $table->integer('fee_id'); $table->integer('term_id');
            $table->integer('user_id'); $table->integer('applied_payment_id')->nullable();
            $table->decimal('amount', 16, 2); $table->decimal('applied_amount', 16, 2)->default(0);
            $table->string('status')->default('Pending'); $table->string('mode'); $table->date('date');
            $table->string('receipt_group'); $table->timestamps();
        });
        $user = new User();
        $user->forceFill(['id' => 1, 'name' => 'Finance Officer', 'email' => 'finance@example.com',
            'role' => 'finance_officer', 'status' => 'Active', 'email_verified_at' => now()]);
        $user->setRelation('accessRoles', collect());
        $user->setRelation('directPermissions', collect([new Permission(['slug' => 'manage-payments'])]));
        $this->actingAs($user);
        DB::table('users')->insert(['id' => 1, 'name' => 'Finance Officer']);
        DB::table('sections')->insert(['id' => 1, 'name' => 'Primary']);
        DB::table('section_classes')->insert(['id' => 1, 'name' => 'Primary One', 'section_id' => 1]);
        DB::table('academic_sessions')->insert(['id' => 1, 'name' => '2026/2027']);
        foreach ([1 => 'First Term', 2 => 'Second Term'] as $id => $name) {
            DB::table('terms')->insert(compact('id', 'name'));
            DB::table('academic_session_terms')->insert(['academic_session_id' => 1, 'term_id' => $id, 'status' => $id === 1 ? 'Active' : 'Not Active']);
        }
        DB::table('students')->insert(['id' => 1, 'name' => 'Test Student', 'admission_no' => 'ADM-1', 'gender_id' => 1]);
        DB::table('section_class_students')->insert(['id' => 1, 'student_id' => 1, 'section_class_id' => 1, 'academic_session_id' => 1]);
        foreach ([1 => 'School Fee', 2 => 'PTA'] as $id => $name) {
            DB::table('fees')->insert(compact('id', 'name'));
            DB::table('section_class_fees')->insert(['id' => $id, 'section_class_id' => 1, 'fee_id' => $id]);
        }
        foreach ([[1, 1, null, 1000], [1, 1, 1, 250], [1, 1, 2, 999], [1, 2, null, 1000], [2, 1, null, 500]] as [$fee, $term, $gender, $amount]) {
            DB::table('section_class_fee_items')->insert(['section_class_fee_id' => $fee, 'term_id' => $term, 'gender_id' => $gender, 'amount' => $amount]);
        }
    }

    protected function tearDown(): void
    {
        DB::purge('unpaid_test');
        parent::tearDown();
    }

    private function record($amount, $terms = [1], $fee = 1)
    {
        return app(RecordFeePayment::class)->handle(1, 1, $fee, $terms, $amount, 'Cash', '2026-09-24');
    }

    private function assertBalance($amount, array $filters = [])
    {
        return $this->get(route('finance.payments.unpaid', $filters))->assertOk()
            ->assertViewHas('totals', ['count' => $amount > 0 ? 1 : 0, 'amount' => $amount]);
    }

    public function test_report_includes_students_without_invoices_and_matches_gender_specific_fees()
    {
        $this->assertFalse(Schema::hasTable('invoices'));
        $this->assertBalance(1750)->assertSee('Test Student');
        $this->assertBalance(1250, ['session' => 1, 'term' => 1, 'fee' => 1]);
        $this->assertBalance(0, ['search' => 'missing']);
        $this->assertBalance(0, ['section' => 99]);
        $this->assertBalance(0, ['section_class' => 99]);
    }

    public function test_partial_payment_records_exact_amount_and_reduces_unpaid_balance()
    {
        $payment = $this->record(400);
        $this->assertEquals(400, $payment->amount);
        $this->assertEquals(400, FinanceActivityLog::sum('amount'));
        $this->assertBalance(850, ['term' => 1, 'fee' => 1])->assertSee('Partial');
        $this->record(850);
        $this->assertBalance(0, ['term' => 1, 'fee' => 1]);
        $this->assertBalance(500);
    }

    public function test_multi_term_payment_allocates_only_the_received_amount_in_term_order()
    {
        $this->record(1500, [2, 1]);
        $this->assertEquals([1250, 250], Payment::orderBy('term_id')->pluck('amount')->map(fn($v) => (float) $v)->all());
        $this->assertCount(1, Payment::pluck('receipt_group')->unique());
        DB::table('academic_session_terms')->where('term_id', 1)->update(['status' => 'Not Active']);
        DB::table('academic_session_terms')->where('term_id', 2)->update(['status' => 'Active']);
        $this->assertBalance(750, ['fee' => 1]);
    }

    public function test_overpayment_is_rejected_without_writing_any_payment()
    {
        try {
            $this->record(1250.01);
            $this->fail('Overpayment should fail.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('amount', $exception->errors());
        }
        $this->assertEquals(0, Payment::count());
        $this->assertEquals(0, FinanceActivityLog::count());
    }

    public function test_other_session_payments_do_not_reduce_current_session_balance()
    {
        $payment = $this->record(400);
        $payment->update(['academic_session_id' => 2]);
        $this->assertBalance(1250, ['fee' => 1, 'term' => 1]);
    }

    public function test_csv_and_pdf_use_the_same_filtered_balances()
    {
        $this->record(400);
        $filters = ['search' => 'ADM-1', 'session' => 1, 'term' => 1, 'fee' => 1, 'section' => 1, 'section_class' => 1];
        $response = $this->get(route('finance.payments.unpaid.csv', $filters))->assertOk();
        $rows = array_map('str_getcsv', explode("\n", trim($response->streamedContent())));
        $this->assertCount(2, $rows);
        $this->assertEquals(['Test Student', 'ADM-1', 'Primary One', 'Primary', '2026/2027', 'First Term', 'School Fee', '1,250.00', '400.00', '850.00', 'Partial'], $rows[1]);
        $pdf = $this->get(route('finance.payments.unpaid.pdf', $filters))->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $pdf->getContent());
    }

    public function test_collection_screen_can_record_more_after_a_partial_payment_and_cancel_it()
    {
        $payment = $this->record(400);
        Livewire::test(Collect::class, ['feeId' => 1])->assertSee('Record payment')
            ->call('selectStudent', 1)->set('amount', 300)->call('recordPayment')->assertHasNoErrors();
        $this->assertEquals(700, Payment::sum('amount'));
        Livewire::test(Collect::class, ['feeId' => 1])->call('cancelRecordedPayment', $payment->id)->assertHasNoErrors();
        $this->assertBalance(950, ['fee' => 1, 'term' => 1]);
    }

    public function test_pending_advance_does_not_reduce_balance_until_applied_and_cannot_be_cancelled_as_cash()
    {
        AdvancePayment::create(['student_id' => 1, 'academic_session_id' => 1, 'section_class_id' => 1,
            'fee_id' => 1, 'term_id' => 1, 'user_id' => 1, 'amount' => 400, 'date' => '2026-09-24',
            'mode' => 'Cash', 'receipt_group' => 'advance-group']);
        $this->assertBalance(1250, ['fee' => 1, 'term' => 1]);
        app(ApplyAdvancePayments::class)->handle(SectionClassStudent::findOrFail(1));
        $this->assertBalance(850, ['fee' => 1, 'term' => 1]);
        Livewire::test(Collect::class, ['feeId' => 1])->call('cancelRecordedPayment', Payment::first()->id)->assertHasErrors('payment');
        $this->assertEquals(400, Payment::sum('amount'));
    }

    public function test_recording_revalidates_stale_balances_and_duplicate_terms()
    {
        $screen = Livewire::test(Collect::class, ['feeId' => 1])->call('selectStudent', 1);
        $this->record(1000);
        $screen->call('recordPayment')->assertHasErrors('amount');
        $this->assertEquals(1000, Payment::sum('amount'));
        $screen->set('selectedTerms', [1, 1])->set('amount', 250)
            ->call('recordPayment')->assertHasErrors('selectedTerms.0');
        $this->assertEquals(1000, Payment::sum('amount'));
    }

    public function test_receipt_shows_actual_grouped_payments_and_full_payment_clears_the_fee()
    {
        $payment = $this->record(2250, [1, 2]);
        $this->get(route('finance.payments.receipt', $payment->id))->assertOk()
            ->assertSee('2,250.00')->assertSee('1,250.00')->assertSee('1,000.00');
        $this->assertBalance(0, ['fee' => 1]);
        $this->assertBalance(500, ['fee' => 2]);
    }

    public function test_legacy_invoice_migration_creates_missing_table_and_preserves_records()
    {
        require_once database_path('migrations/2026_09_24_000000_create_missing_invoices_table.php');
        $migration = new \CreateMissingInvoicesTable();
        $migration->up();
        \App\Models\Invoice::create(['amount' => 123.45]);
        $migration->up();
        $migration->down();
        $this->assertEquals(123.45, \App\Models\Invoice::sum('amount'));
    }

    public function test_current_period_cannot_be_overridden_by_url_filters()
    {
        DB::table('academic_sessions')->insert(['id' => 2, 'name' => '2025/2026', 'status' => 'Not Active']);
        DB::table('academic_session_terms')->insert(['academic_session_id' => 2, 'term_id' => 1]);
        DB::table('section_class_students')->insert(['student_id' => 1, 'section_class_id' => 1, 'academic_session_id' => 2]);
        $filters = ['session' => 2, 'term' => 2];
        $this->assertBalance(1750, $filters)->assertDontSee('name="session"', false)->assertDontSee('name="term"', false);
        $response = $this->get(route('finance.payments.unpaid.csv', $filters))->assertOk();
        $rows = array_map('str_getcsv', explode("\n", trim($response->streamedContent())));
        $this->assertCount(3, $rows);
        foreach (array_slice($rows, 1) as $row) {
            $this->assertEquals('2026/2027', $row[4]);
            $this->assertEquals('First Term', $row[5]);
        }

        $controller = \Mockery::mock(\App\Http\Controllers\Finance\PaymentReportController::class)
            ->makePartial()->shouldAllowMockingProtectedMethods();
        $controller->shouldReceive('renderPdfView')->once()->withArgs(function ($view, $data, $filename) {
            $this->assertEquals('2026/2027', $data['filters']['Session']);
            $this->assertEquals('First Term', $data['filters']['Term']);
            $this->assertEquals([1], $data['unpaidStudents']->pluck('academic_session_id')->unique()->values()->all());
            $this->assertEquals([1], $data['unpaidStudents']->flatMap->outstandingFees->pluck('term_id')->unique()->values()->all());
            return true;
        })->andReturn(response('Current term PDF'));
        $this->app->instance(\App\Http\Controllers\Finance\PaymentReportController::class, $controller);
        $this->get(route('finance.payments.unpaid.pdf', $filters))->assertOk();
    }

    public function test_report_and_exports_require_a_current_period()
    {
        DB::table('academic_session_terms')->update(['status' => 'Not Active']);
        foreach (['unpaid', 'unpaid.csv', 'unpaid.pdf'] as $route) {
            $this->get(route('finance.payments.'.$route))->assertStatus(422);
        }
        DB::table('academic_session_terms')->where('term_id', 1)->update(['status' => 'Active']);
        DB::table('academic_sessions')->update(['status' => 'Not Active']);
        $this->get(route('finance.payments.unpaid'))->assertStatus(422);
    }
}
