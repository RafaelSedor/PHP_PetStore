<?php

namespace Tests\UserModels;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        $_POST = [];

        User::deleteByEmail('rafael.sedor@petstoreTest.com');
        User::deleteByEmail('rafael.sedor@petstoreTestUpdate.com');
        User::deleteByEmail('rafael.updated@petstore.com');
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        $_POST = [];
        User::deleteByEmail('rafael.sedor@petstoreTest.com');
        User::deleteByEmail('rafael.sedor@petstoreTestUpdate.com');
        User::deleteByEmail('rafael.updated@petstore.com');
        parent::tearDown();
    }

    public function test_create_user()
    {
        $user = new User();
        $user->name = 'Rafael Sedor';
        $user->email = 'rafael.sedor@petstoreTest.com';
        $user->password = password_hash('securepassword', PASSWORD_DEFAULT);
        $user->role = 'user';
        $result = $user->save();

        $this->assertTrue($result, 'Usuário deveria ser criado com sucesso.');

        $retrievedUser = User::findByEmail('rafael.sedor@petstoreTest.com');
        $this->assertNotNull($retrievedUser, 'Usuário deveria ser encontrado pelo e-mail.');
        $this->assertEquals('Rafael Sedor', $retrievedUser->name);
        $this->assertEquals('user', $retrievedUser->role);
    }

    public function test_update_user()
    {
        $user = new User();
        $user->name = 'Rafael Sedor';
        $user->email = 'rafael.sedor@petstoreTestUpdate.com';
        $user->password = password_hash('securepassword', PASSWORD_DEFAULT);
        $user->role = 'user';
        $user->save();

        $user->name = 'Rafael Updated';
        $user->email = 'rafael.updated@petstore.com';
        $user->password = password_hash('newpassword', PASSWORD_DEFAULT);
        $user->role = 'admin';
        $result = $user->save();

        $this->assertTrue($result, 'Usuário deveria ser atualizado com sucesso.');

        $updatedUser = User::findById($user->id);
        $this->assertNotNull($updatedUser, 'Usuário deveria ser encontrado pelo ID após atualização.');
        $this->assertEquals('Rafael Updated', $updatedUser->name);
        $this->assertEquals('rafael.updated@petstore.com', $updatedUser->email);
        $this->assertTrue(password_verify('newpassword', $updatedUser->password), 'A senha deveria ser atualizada.');
        $this->assertEquals('admin', $updatedUser->role);
    }

    public function test_delete_user()
    {
        $user = new User();
        $user->name = 'Rafael Sedor';
        $user->email = 'rafael.sedor@petstoreTestDelete.com';
        $user->password = password_hash('securepassword', PASSWORD_DEFAULT);
        $user->role = 'user';
        $user->save();

        $result = $user->delete();

        $this->assertTrue($result, 'Usuário deveria ser excluído com sucesso.');

        $deletedUser = User::findById($user->id);
        $this->assertNull($deletedUser, 'Usuário não deveria ser encontrado após exclusão.');
    }
}
