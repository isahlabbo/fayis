<?php

namespace Tests\Unit;

use App\Http\Middleware\FinanceOfficerMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FinanceOfficerMiddlewareTest extends TestCase
{
    public function test_active_patron_is_allowed_to_access_finance_routes()
    {
        $middleware = new FinanceOfficerMiddleware();
        $request = Request::create('/finance/payment', 'GET');

        $user = new \stdClass();
        $user->status = 'Active';
        $user->role = 'patron';

        Auth::shouldReceive('user')->andReturn($user);

        $response = $middleware->handle($request, function ($request) {
            return response('ok');
        });

        $this->assertSame('ok', $response->getContent());
    }

    public function test_non_finance_roles_are_redirected_to_restricted_access_page()
    {
        $middleware = new FinanceOfficerMiddleware();
        $request = Request::create('/finance/payment', 'GET');

        $user = new \stdClass();
        $user->status = 'Active';
        $user->role = 'teacher';

        Auth::shouldReceive('user')->andReturn($user);

        $response = $middleware->handle($request, function ($request) {
            return response('ok');
        });

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertSame(route('access.restricted'), $response->getTargetUrl());
    }
}
