<?php

namespace Tests\Unit;

use App\Http\Middleware\AdmissionOfficerMiddleware;
use App\Http\Middleware\ExamOfficerMiddleware;
use App\Http\Middleware\FinanceOfficerMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;

class HeadOfSchoolRbacTest extends TestCase
{
    public function test_database_head_role_takes_effect_when_legacy_role_is_staff()
    {
        $user = new User(['role' => 'staff']);
        $user->setRelation('accessRoles', collect([new Role(['slug' => 'head'])]));

        $this->assertTrue($user->usesRole('head'));
        $this->assertFalse($user->usesRole('teacher'));
    }

    /** @dataProvider permissionMiddlewareProvider */
    public function test_head_permission_allows_legacy_middleware($middleware, $permission)
    {
        $user = new class($permission) {
            public $status = 'Active';
            public $role = 'head';
            private $permission;
            public function __construct($permission) { $this->permission = $permission; }
            public function hasPermission($permission) { return $permission === $this->permission; }
        };
        Auth::shouldReceive('user')->andReturn($user);
        $response = (new $middleware())->handle(Request::create('/protected', 'GET'), fn () => response('ok'));
        $this->assertSame('ok', $response->getContent());
    }

    public function permissionMiddlewareProvider()
    {
        return [
            'inventory' => [FinanceOfficerMiddleware::class, 'manage-inventory'],
            'students' => [AdmissionOfficerMiddleware::class, 'manage-students'],
            'results' => [ExamOfficerMiddleware::class, 'manage-result-uploads'],
        ];
    }
}
