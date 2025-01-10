<?php

use PHPUnit\Framework\TestCase;
use App\Models\Category;

class CategoryModelTest extends TestCase
{
    private $categoryId;

    protected function setUp(): void
    {
        parent::setUp();

        $existingCategory = Category::findByName("Test Category");
        if ($existingCategory) {
            Category::delete($existingCategory->id);
        }

        Category::create("Test Category");
        $this->categoryId = Category::findByName("Test Category")->id;
    }

    protected function tearDown(): void
    {
        if ($this->categoryId) {
            Category::delete($this->categoryId);
        }

        $additionalCategory = Category::findByName("Additional Test Category");
        if ($additionalCategory) {
            Category::delete($additionalCategory->id);
        }

        parent::tearDown();
    }

    public function testAllCategories()
    {
        $categories = Category::all();
        $this->assertIsArray($categories);
        $this->assertNotEmpty($categories);
        $this->assertInstanceOf(Category::class, $categories[0]);
    }

    public function testFindById()
    {
        $category = Category::findById($this->categoryId);
        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals("Test Category", $category->name);
    }

    public function testFindByName()
    {
        $category = Category::findByName("Test Category");
        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals("Test Category", $category->name);
    }

    public function testCreate()
    {
        $result = Category::create("Additional Test Category");
        $this->assertTrue($result);

        $newCategory = Category::findByName("Additional Test Category");
        $this->assertInstanceOf(Category::class, $newCategory);
        $this->assertEquals("Additional Test Category", $newCategory->name);

        Category::delete($newCategory->id);
    }

    public function testDelete()
    {
        $result = Category::delete($this->categoryId);
        $this->assertTrue($result);

        $deletedCategory = Category::findById($this->categoryId);
        $this->assertNull($deletedCategory);

        $this->categoryId = null;
    }

    public function testUpdate()
    {
        $category = Category::findById($this->categoryId);
        $this->assertNotNull($category);

        $result = $category->update("Updated Test Category");
        $this->assertTrue($result);

        $updatedCategory = Category::findById($this->categoryId);
        $this->assertEquals("Updated Test Category", $updatedCategory->name);
    }
}
