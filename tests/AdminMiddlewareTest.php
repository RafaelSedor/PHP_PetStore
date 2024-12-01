<?php

namespace Tests;

use App\Middleware\AdminMiddleware;
use Core\Http\Request;
use PHPUnit\Framework\TestCase;
use Lib\Authentication\Auth;
use Core\Constants\Constants;

class AdminMiddlewareTest extends TestCase
{
    private Request $request;

    public function setUp(): void
    {
        parent::setUp();
        require Constants::rootPath()->join('config/routes.php');

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        $this->request = new Request();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($_SERVER['REQUEST_METHOD']);
        unset($_SERVER['REQUEST_URI']);
        $_SESSION = [];
    }

    public function test_example()
    {
        echo "Executando teste de exemplo\n";
        $this->assertTrue(true);
    }

    public function test_redirects_when_not_authenticated()
    {
        Auth::logout();

        $middleware = new AdminMiddleware();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Redirecionando para: /login');
        $middleware->handle($this->request);
    }

    public function test_redirects_when_not_admin()
    {
        $_SESSION['user'] = ['id' => 2, 'role' => 'user'];

        $middleware = new AdminMiddleware();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Redirecionando para: /login');

        $middleware->handle($this->request);
    }

    public function test_allows_access_for_admin()
    {
        $_SESSION['user'] = ['id' => 1, 'role' => 'admin'];

        $middleware = new AdminMiddleware();

        $middleware->handle($this->request);
        $this->assertTrue(true);
    }
}
