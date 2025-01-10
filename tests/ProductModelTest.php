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

        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 100.00,
            'image_url' => 'https://down-br.img.susercontent.com/file/32e7b64738c4936f67cade4301210821',
            'categories' => [$this->categoryId],
        ];

        $product = new Product();
        $product->name = $productData['name'];
        $product->description = $productData['description'];
        $product->price = $productData['price'];
        $product->image_url = $productData['image_url'];
        $product->categories = $productData['categories'];
        $product->save();

        $this->productId = $product->id;
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
            $category = Category::findById($this->categoryId);
            if ($category) {
                $category->delete($this->categoryId);
            }
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

        $product = $products[0];
        $this->assertEquals($this->productId, $product->id);
        $this->assertContains($this->categoryId, array_column($product->categories, 'id'));
    }

    public function testSave()
    {
        $product = Product::findById($this->productId);
        $product->name = 'Updated Test Product';
        $product->categories = [$this->categoryId];
        $result = $product->save();

        $this->assertTrue($result);

        $updatedProduct = Product::findById($this->productId);
        $this->assertEquals('Updated Test Product', $updatedProduct->name);
        $this->assertContains($this->categoryId, array_column($updatedProduct->categories, 'id'));
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
            'image_url' => 'https://down-br.img.susercontent.com/file/32e7b64738c4936f67cade4301210821',
            'categories' => [$this->categoryId],
        ];

        $product = new Product();
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->image_url = $data['image_url'];
        $product->categories = $data['categories'];
        $result = $product->save();

        $this->assertTrue($result);

        $newProduct = Product::findById($product->id);
        $this->assertInstanceOf(Product::class, $newProduct);
        $this->assertEquals($data['name'], $newProduct->name);
        $this->assertEquals($data['description'], $newProduct->description);
        $this->assertEquals($data['price'], $newProduct->price);
        $this->assertEquals($data['image_url'], $newProduct->image_url);
        $this->assertContains($this->categoryId, array_column($newProduct->categories, 'id'));

        $newProduct->delete();
    }
}
