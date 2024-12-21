<?php

namespace Tests\PetControllers;

use App\Controllers\PetsController;
use App\Models\Pet;
use Core\Http\Request;
use PHPUnit\Framework\TestCase;

class PetControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $_SESSION = [];
        $_POST = [];
        $_REQUEST = [];
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/pet/create';

        Pet::deleteByName('PinsherTest');
        Pet::deleteByName('AgataTest');
        Pet::deleteByName('PinsherTest Updated');
    }

    protected function tearDown(): void
    {
        Pet::deleteByName('PinsherTest');
        Pet::deleteByName('AgataTest');
        Pet::deleteByName('PinsherTest Updated');

        $_SESSION = [];
        $_POST = [];
        parent::tearDown();
    }

    public function test_create_pet_successfully()
    {
        $_SESSION['user'] = ['id' => 2];

        $_POST = [
            'name' => 'PinsherTest',
            'species' => 'Cachorro',
            'age' => 4,
        ];

        $_REQUEST = $_POST;

        $request = new Request();
        $controller = new PetsController();

        try {
            ob_start();
            $controller->store($request);
            ob_end_clean();
        } catch (\Exception $e) {
            $this->assertStringContainsString('/user/', $e->getMessage());
        } finally {
            ob_end_clean();
        }

        $pet = Pet::findByName('PinsherTest');
        $this->assertNotNull($pet);
        $this->assertEquals('PinsherTest', $pet->name);
        $this->assertEquals('Cachorro', $pet->species);
        $this->assertEquals(4, $pet->age);
    }

    public function test_update_pet_successfully()
    {
        $_SESSION['user'] = ['id' => 2];

        $pet = new Pet();
        $pet->name = 'PinsherTest';
        $pet->species = 'Cachorro';
        $pet->age = 4;
        $pet->user_id = 2;
        $pet->save();

        $_POST = [
            'id' => $pet->id,
            'name' => 'PinsherTest Updated',
            'species' => 'Cachorro Grande',
            'age' => 5,
        ];

        $_REQUEST = $_POST;

        $request = new Request();
        $controller = new PetsController();

        try {
            ob_start();
            $controller->update($request);
            ob_end_clean();
        } catch (\Exception $e) {
            $this->assertStringContainsString('/user/', $e->getMessage());
        } finally {
            ob_end_clean();
        }

        $updatedPet = Pet::findById($pet->id);
        $this->assertNotNull($updatedPet);
        $this->assertEquals('PinsherTest Updated', $updatedPet->name);
        $this->assertEquals('Cachorro Grande', $updatedPet->species);
        $this->assertEquals(5, $updatedPet->age);
    }

    public function test_delete_pet_successfully()
    {
        $_SESSION['user'] = ['id' => 2];

        $pet = new Pet();
        $pet->name = 'AgataTest';
        $pet->species = 'Gato';
        $pet->age = 7;
        $pet->user_id = 2;
        $pet->save();

        $_POST = ['id' => $pet->id];
        $_REQUEST = $_POST;

        $request = new Request();
        $controller = new PetsController();

        try {
            ob_start();
            $controller->delete($request);
            ob_end_clean();
        } catch (\Exception $e) {
            $this->assertStringContainsString('/user/', $e->getMessage());
        } finally {
            ob_end_clean();
        }

        $deletedPet = Pet::findById($pet->id);
        $this->assertNull($deletedPet);
    }
}
