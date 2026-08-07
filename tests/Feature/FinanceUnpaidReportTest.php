<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceUnpaidReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_finance_users_can_view_the_unpaid_report()
    {
        $user = new User();
        $user->forceFill([
            'name' => 'Finance Officer',
            'email' => 'finance@example.com',
            'password' => bcrypt('password'),
            'role' => 'finance_officer',
            'status' => 'Active',
            'email_verified_at' => now(),
        ]);
        $user->save();

        $response = $this->actingAs($user)->get(route('finance.payments.unpaid'));

        $response->assertOk();
        $response->assertViewHas('unpaidStudents');
    }
}
