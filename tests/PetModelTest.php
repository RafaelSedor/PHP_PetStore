<?php

namespace Tests\PetModels;

use App\Models\Pet;
use PHPUnit\Framework\TestCase;

class PetModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Pet::deleteByName('PinsherTest');
        Pet::deleteByName('AgataTest');
        Pet::deleteByName('PinsherTest Updated');
    }

    protected function tearDown(): void
    {
        Pet::deleteByName('PinsherTest');
        Pet::deleteByName('AgataTest');
        Pet::deleteByName('PinsherTest Updated');

        parent::tearDown();
    }

    public function test_save_pet_successfully()
    {
        $pet = new Pet();
        $pet->name = 'PinsherTest';
        $pet->species = 'Cachorro';
        $pet->age = 4;
        $pet->user_id = 2;

        $this->assertTrue($pet->save());

        $savedPet = Pet::findByName('PinsherTest');
        $this->assertNotNull($savedPet);
        $this->assertEquals('PinsherTest', $savedPet->name);
    }

    public function test_update_pet_successfully()
    {
        $pet = new Pet();
        $pet->name = 'PinsherTest';
        $pet->species = 'Cachorro';
        $pet->age = 4;
        $pet->user_id = 2;
        $pet->save();

        $pet->name = 'PinsherTest Updated';
        $pet->age = 5;
        $this->assertTrue($pet->save());

        $updatedPet = Pet::findById($pet->id);
        $this->assertEquals('PinsherTest Updated', $updatedPet->name);
        $this->assertEquals(5, $updatedPet->age);
    }

    public function test_delete_pet_successfully()
    {
        $pet = new Pet();
        $pet->name = 'AgataTest';
        $pet->species = 'Gato';
        $pet->age = 7;
        $pet->user_id = 2;
        $pet->save();

        $this->assertTrue($pet->delete());

        $deletedPet = Pet::findById($pet->id);
        $this->assertNull($deletedPet);
    }
}
