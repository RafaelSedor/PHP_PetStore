<?php

use PHPUnit\Framework\TestCase;
use App\Models\Product;
use App\Models\Category;

class ProductModelTest extends TestCase
{
    private $productId;
    private $categoryId;

    protected function setUp(): void
    {
        parent::setUp();

        $existingCategory = Category::findByName("Test Category");
        if (!$existingCategory) {
            Category::create("Test Category");
        }
        $this->categoryId = Category::findByName("Test Category")->id;

        Product::create([
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 100.00,
            'category_id' => $this->categoryId,
            'image_url' => 'https://down-br.img.susercontent.com/file/32e7b64738c4936f67cade4301210821',
        ]);
        $this->productId = Product::findByCategory($this->categoryId)[0]->id ?? null;
    }

    protected function tearDown(): void
    {
        if ($this->productId) {
            $product = Product::findById($this->productId);
            if ($product) {
                $product->delete();
            }
        }

        if ($this->categoryId) {
            Category::delete($this->categoryId);
        }

        parent::tearDown();
    }

    public function testFindById()
    {
        $product = Product::findById($this->productId);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Test Product', $product->name);
    }

    public function testFindByCategory()
    {
        $products = Product::findByCategory($this->categoryId);
        $this->assertIsArray($products);
        $this->assertNotEmpty($products);
        $this->assertEquals($this->categoryId, $products[0]->category_id);
    }

    public function testAll()
    {
        $products = Product::all();
        $this->assertIsArray($products);
        $this->assertNotEmpty($products);

        $found = false;
        foreach ($products as $product) {
            if ($product->id === $this->productId) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }

    public function testSave()
    {
        $product = Product::findById($this->productId);
        $product->name = 'Updated Test Product';
        $result = $product->save();

        $this->assertTrue($result);

        $updatedProduct = Product::findById($this->productId);
        $this->assertEquals('Updated Test Product', $updatedProduct->name);
    }

    public function testDelete()
    {
        $product = Product::findById($this->productId);
        $result = $product->delete();

        $this->assertTrue($result);

        $deletedProduct = Product::findById($this->productId);
        $this->assertNull($deletedProduct);

        $this->productId = null;
    }

    public function testCreate()
    {
        $data = [
            'name' => 'Another Test Product',
            'description' => 'Another Description',
            'price' => 150.00,
            'category_id' => $this->categoryId,
            'image_url' => 'https://down-br.img.susercontent.com/file/32e7b64738c4936f67cade4301210821',
        ];

        $result = Product::create($data);
        $this->assertTrue($result);

        $newProduct = Product::findByCategory($this->categoryId);
        $foundProduct = array_filter($newProduct, function ($product) use ($data) {
            return $product->name === $data['name'];
        });

        $this->assertNotEmpty($foundProduct, "Product was not found after creation.");
        $foundProduct = array_values($foundProduct)[0];

        $this->assertInstanceOf(Product::class, $foundProduct);
        $this->assertEquals($data['name'], $foundProduct->name);
        $this->assertEquals($data['description'], $foundProduct->description);
        $this->assertEquals($data['price'], $foundProduct->price);
        $this->assertEquals($data['category_id'], $foundProduct->category_id);
        $this->assertEquals($data['image_url'], $foundProduct->image_url);

        $foundProduct->delete();
    }
}
