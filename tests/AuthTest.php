<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Lib\Authentication\Auth;

class AuthTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_admin_login_success()
    {
        $result = Auth::attempt('admin@petstore.com', 'adminpassword');

        $this->assertTrue($result);

        $user = Auth::user();
        $this->assertNotNull($user);
        $this->assertEquals('admin', $user->role);
    }

    // public function test_user_login_success()
    // {

    //     $result = Auth::attempt('user@petstore.com', 'userpassword');

    //     $this->assertTrue($result);

    //     $user = Auth::user();
    //     $this->assertNotNull($user);
    //     $this->assertEquals('user', $user->role);
    // }

    public function test_invalid_login()
    {
        $result = Auth::attempt('invalid@petstore.com', 'wrongpassword');

        $this->assertFalse($result);

        $user = Auth::user();
        $this->assertNull($user);
    }
}
