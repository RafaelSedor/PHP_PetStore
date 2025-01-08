<?php

namespace App\Models;

use Core\Database\Database;
use PDO;

class ShoppingListItem
{
    public $id;
    public $shopping_list_id;
    public $product_id;
    public $quantity;

    public static function create(array $data): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("INSERT INTO shopping_list_items (shopping_list_id, product_id, quantity) VALUES (:list_id, :product_id, :quantity)");
        return $stmt->execute([
            ':list_id' => $data['shopping_list_id'],
            ':product_id' => $data['product_id'],
            ':quantity' => $data['quantity'],
        ]);
    }

    public static function deleteByShoppingListId(int $shoppingListId): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("DELETE FROM shopping_list_items WHERE shopping_list_id = :shopping_list_id");
        return $stmt->execute([':shopping_list_id' => $shoppingListId]);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("DELETE FROM shopping_lists WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
