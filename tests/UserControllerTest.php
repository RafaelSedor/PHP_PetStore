<?php

namespace Tests\Controllers;

use App\Controllers\UserController;
use App\Models\User;
use Core\Http\Request;
use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/user/store';
        $_POST = [];
        $_REQUEST = [];

        User::deleteByEmail('rafael.sedor@petstoreTest.com');
        User::deleteByEmail('rafael.sedor@petstoreTestUpdate.com');
        User::deleteByEmail('rafael.updated@petstore.com');
    }

    protected function tearDown(): void
    {
        User::deleteByEmail('rafael.sedor@petstoreTest.com');
        User::deleteByEmail('rafael.sedor@petstoreTestUpdate.com');
        User::deleteByEmail('rafael.updated@petstore.com');

        $_SESSION = [];
        $_POST = [];
        parent::tearDown();
    }

    public function test_store_creates_user_successfully()
    {
        $_POST = [
            'name' => 'Rafael Sedor',
            'email' => 'rafael.sedor@petstoreTest.com',
            'password' => 'securepassword',
            'role' => 'user'
        ];

        $_REQUEST = $_POST;

        $request = new Request();
        $controller = new UserController();

        try {
            ob_start();
            $controller->store($request);
            ob_end_clean();
        } catch (\Exception $e) {
            $this->assertEquals('Redirecting to: /users', $e->getMessage());
        }

        $user = User::findByEmail('rafael.sedor@petstoreTest.com');
        $this->assertNotNull($user);
        $this->assertEquals('Rafael Sedor', $user->name);
        $this->assertEquals('user', $user->role);
    }

    public function test_store_fails_with_invalid_data()
    {
        $_POST = [
            'name' => '',
            'email' => '',
            'password' => '',
            'role' => 'user'
        ];

        $_REQUEST = $_POST;

        $request = new Request();
        $controller = new UserController();

        try {
            ob_start();
            $controller->store($request);
            ob_end_clean();
        } catch (\Exception $e) {
            $this->assertEquals('Redirecting back', $e->getMessage());
        }

        $user = User::findByEmail('');
        $this->assertNull($user);
    }

    public function test_update_user_successfully()
    {
        $_SESSION['user'] = ['id' => 1, 'role' => 'admin'];

        $user = new User();
        $user->name = 'Rafael Sedor';
        $user->email = 'rafael.sedor@petstoreTestUpdate.com';
        $user->password = password_hash('securepassword', PASSWORD_DEFAULT);
        $user->role = 'user';
        $user->save();

        $_POST = [
            'id' => $user->id,
            'name' => 'Rafael Updated',
            'email' => 'rafael.updated@petstore.com',
            'password' => 'newpassword',
            'role' => 'admin'
        ];

        $_REQUEST = $_POST;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/user/update';

        $request = new Request();
        $controller = new UserController();

        try {
            ob_start();
            $controller->update($request);
            ob_end_clean();
        } catch (\Exception $e) {
            $this->assertEquals('Redirecting to: /users', $e->getMessage());
        }

        $updatedUser = User::findById($user->id);
        $this->assertNotNull($updatedUser);
        $this->assertEquals('Rafael Updated', $updatedUser->name);
        $this->assertEquals('rafael.updated@petstore.com', $updatedUser->email);
        $this->assertTrue(password_verify('newpassword', $updatedUser->password));
        $this->assertEquals('admin', $updatedUser->role);
    }

    public function test_delete_user_successfully()
    {
        $_SESSION['user'] = ['id' => 1, 'role' => 'admin'];

        $user = new User();
        $user->name = 'Rafael Sedor';
        $user->email = 'rafael.updated@petstore.com';
        $user->password = password_hash('securepassword', PASSWORD_DEFAULT);
        $user->role = 'user';
        $user->save();

        $_POST = ['id' => $user->id];
        $_REQUEST = $_POST;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/user/delete';

        $request = new Request();
        $controller = new UserController();

        try {
            ob_start();
            $controller->delete($request);
            ob_end_clean();
        } catch (\Exception $e) {
            $this->assertEquals('Redirecting to: /users', $e->getMessage());
        }

        $deletedUser = User::findById($user->id);
        $this->assertNull($deletedUser);
    }
}
