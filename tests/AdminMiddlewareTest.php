<?php

namespace Tests\Unit\Middleware;

use App\Middleware\AdminMiddleware;
use Core\Http\Request;
use Lib\Authentication\Auth;
use PHPUnit\Framework\TestCase;

class AdminMiddlewareTest extends TestCase
{
    public function test_redirects_when_not_authenticated()
    {
        Auth::logout();

        $middleware = new AdminMiddleware();
        $request = new Request();

        $this->expectException(\Exception::class);
        $middleware->handle($request);
    }

    public function test_redirects_when_not_admin()
    {
        $_SESSION['user'] = ['id' => 1, 'role' => 'user'];

        $middleware = new AdminMiddleware();
        $request = new Request();

        $this->expectException(\Exception::class);
        $middleware->handle($request);
    }

    public function test_allows_access_for_admin()
    {
        $_SESSION['user'] = ['id' => 1, 'role' => 'admin'];

        $middleware = new AdminMiddleware();
        $request = new Request();

        $middleware->handle($request);
        $this->assertTrue(true);
    }
}
