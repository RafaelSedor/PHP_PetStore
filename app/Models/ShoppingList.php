<?php

namespace App\Models;

use Core\Database\Database;
use PDO;

class ShoppingList
{
    public $id;
    public $user_id;
    public $created_at;
    public $status;
    public $user;
    public $items;

    public static function findByUser(int $userId): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM shopping_lists WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $shoppingLists = $stmt->fetchAll(PDO::FETCH_CLASS, static::class);

        foreach ($shoppingLists as $list) {
            $list->user = User::findById($list->user_id);
            $list->items = self::findItemsByListId($list->id);
        }

        return $shoppingLists;
    }

    public static function findAll(): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->query("SELECT * FROM shopping_lists");
        $shoppingLists = $stmt->fetchAll(PDO::FETCH_CLASS, static::class);

        foreach ($shoppingLists as $list) {
            $list->user = User::findById($list->user_id);
            $list->items = self::findItemsByListId($list->id);
        }

        return $shoppingLists;
    }

    public static function findById(int $id): ?self
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM shopping_lists WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $shoppingList = $stmt->fetchObject(static::class);

        if ($shoppingList) {
            $shoppingList->user = User::findById($shoppingList->user_id);
            $shoppingList->items = self::findItemsByListId($shoppingList->id);
        }

        return $shoppingList ?: null;
    }

    public static function create(array $data): int
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("INSERT INTO shopping_lists (user_id, created_at, status) VALUES (:user_id, NOW(), 'open')");
        $stmt->execute([
            ':user_id' => $data['user_id'],
        ]);

        return (int) $db->lastInsertId();
    }

    public static function delete(int $id): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("DELETE FROM shopping_lists WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function findItemsByListId(int $listId): array
    {
        $db = Database::getDatabaseConn();

        $query = "
        SELECT 
            sli.id, 
            sli.quantity, 
            p.id AS product_id, 
            p.name AS product_name, 
            p.price AS product_price 
        FROM shopping_list_items sli
        INNER JOIN products p ON sli.product_id = p.id
        WHERE sli.shopping_list_id = :list_id";

        $stmt = $db->prepare($query);
        $stmt->execute([':list_id' => $listId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addItem(int $listId, int $productId, int $quantity): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("
            INSERT INTO shopping_list_items (shopping_list_id, product_id, quantity, created_at)
            VALUES (:list_id, :product_id, :quantity, NOW())
        ");
        return $stmt->execute([
            ':list_id' => $listId,
            ':product_id' => $productId,
            ':quantity' => $quantity,
        ]);
    }

    public static function deleteItem(int $listId, int $productId): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("
            DELETE FROM shopping_list_items 
            WHERE shopping_list_id = :list_id AND product_id = :product_id
        ");
        return $stmt->execute([
            ':list_id' => $listId,
            ':product_id' => $productId,
        ]);
    }

    public function updateStatus(string $status): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("UPDATE shopping_lists SET status = :status WHERE id = :id");
        return $stmt->execute([
            ':status' => $status,
            ':id' => $this->id,
        ]);
    }

    public static function findByStatus(int $userId, string $status): array
    {
        $db = Database::getDatabaseConn();

        $query = "
        SELECT * 
        FROM shopping_lists
        WHERE user_id = :user_id AND status = :status";

        $stmt = $db->prepare($query);
        $stmt->execute([
            ':user_id' => $userId,
            ':status' => $status,
        ]);

        $shoppingLists = $stmt->fetchAll(PDO::FETCH_CLASS, static::class);

        foreach ($shoppingLists as $list) {
            $list->user = User::findById($list->user_id);
            $list->items = self::findItemsByListId($list->id);
        }

        return $shoppingLists;
    }

    public static function filter(?string $status, ?string $userName): array
    {
        $db = Database::getDatabaseConn();

        $query = "
        SELECT sl.* 
        FROM shopping_lists sl
        INNER JOIN users u ON sl.user_id = u.id
        WHERE 1 = 1";

        $params = [];

        if ($status) {
            $query .= " AND sl.status = :status";
            $params[':status'] = $status;
        }

        if ($userName) {
            $query .= " AND u.name LIKE :user_name";
            $params[':user_name'] = '%' . $userName . '%';
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);

        $shoppingLists = $stmt->fetchAll(PDO::FETCH_CLASS, static::class);

        foreach ($shoppingLists as $list) {
            $list->user = User::findById($list->user_id);
        }

        return $shoppingLists;
    }
}
