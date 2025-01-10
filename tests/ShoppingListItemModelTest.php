<?php

use PHPUnit\Framework\TestCase;
use App\Models\ShoppingListItem;
use App\Models\Product;
use App\Models\ShoppingList;

class ShoppingListItemModelTest extends TestCase
{
    private $shoppingListId;
    private $productId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shoppingListId = ShoppingList::create(['user_id' => 1]);
        $product = new Product();
        $product->name = "Temporary Product";
        $product->description = "Temporary description";
        $product->price = 10.99;
        $product->category_id = 1;
        $product->image_url = "https://down-br.img.susercontent.com/file/32e7b64738c4936f67cade4301210821";
        $product->save();
        $this->productId = $product->id;
    }

    protected function tearDown(): void
    {
        ShoppingList::deleteItem($this->shoppingListId, $this->productId);
        ShoppingList::delete($this->shoppingListId);
        $product = Product::findById($this->productId);
        $product->delete();

        parent::tearDown();
    }

    public function testCreateShoppingListItem()
    {
        $data = [
            'shopping_list_id' => $this->shoppingListId,
            'product_id' => $this->productId,
            'quantity' => 2,
        ];

        $result = ShoppingListItem::create($data);

        $this->assertTrue($result, "Falha ao criar um item na lista de compras.");
    }
}
