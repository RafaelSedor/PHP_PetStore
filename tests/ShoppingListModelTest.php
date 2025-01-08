<?php

use PHPUnit\Framework\TestCase;
use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Models\Product;

class ShoppingListModelTest extends TestCase
{
    private $shoppingListId;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = new Product();
        $this->product->name = "Test Product";
        $this->product->description = "Temporary description";
        $this->product->price = 10.99;
        $this->product->category_id = 1;
        $this->product->image_url = "https://example.com/product.jpg";
        $this->product->save();

        $this->shoppingListId = ShoppingList::create(['user_id' => 1]);
    }

    protected function tearDown(): void
    {
        $shoppingLists = ShoppingList::findAll();
        foreach ($shoppingLists as $list) {
            $items = ShoppingList::findItemsByListId($list->id);
            foreach ($items as $item) {
                ShoppingList::deleteItem($list->id, $item['product_id']);
            }
            ShoppingList::delete($list->id);
        }

        $this->product->delete();

        parent::tearDown();
    }

    public function testCreateShoppingList()
    {
        $newShoppingListId = ShoppingList::create(['user_id' => 1]);
        $this->assertIsInt($newShoppingListId);

        $shoppingLists = ShoppingList::findByUser(1);
        $this->assertNotEmpty($shoppingLists);

        ShoppingList::delete($newShoppingListId);
    }

    public function testFindByUser()
    {
        $shoppingLists = ShoppingList::findByUser(1);

        $this->assertNotEmpty($shoppingLists, "No shopping lists found for the user");

        $this->assertCount(1, $shoppingLists, "Unexpected number of shopping lists found");

        $this->assertEquals($this->shoppingListId, $shoppingLists[0]->id, "Shopping list ID mismatch");
    }

    public function testAddItemToShoppingList()
    {
        $result = ShoppingList::addItem($this->shoppingListId, $this->product->id, 3);
        $this->assertTrue($result);

        $items = ShoppingList::findItemsByListId($this->shoppingListId);
        $this->assertNotEmpty($items);
        $this->assertEquals($this->product->id, $items[0]['product_id']);
        $this->assertEquals(3, $items[0]['quantity']);
    }

    public function testDeleteItemFromShoppingList()
    {
        ShoppingList::addItem($this->shoppingListId, $this->product->id, 2);
        $result = ShoppingList::deleteItem($this->shoppingListId, $this->product->id);
        $this->assertTrue($result);

        $items = ShoppingList::findItemsByListId($this->shoppingListId);
        $this->assertEmpty($items);
    }

    public function testDeleteShoppingList()
    {
        $this->assertNotNull($this->shoppingListId, "Shopping list ID is null");

        $addItemResult = ShoppingList::addItem($this->shoppingListId, $this->product->id, 2);
        $this->assertTrue($addItemResult, "Failed to add item to shopping list");

        $resultDeleteItem = ShoppingList::deleteItem($this->shoppingListId, $this->product->id);
        $this->assertTrue($resultDeleteItem, "Failed to delete item from shopping list");

        $resultDeleteList = ShoppingList::delete($this->shoppingListId);
        $this->assertTrue($resultDeleteList, "Failed to delete shopping list");

        $shoppingLists = ShoppingList::findByUser(1);
        $this->assertEmpty($shoppingLists, "Shopping list was not deleted successfully");

        $this->shoppingListId = null;
    }
}
