<?php

namespace App\Models;

use Core\Database\Database;
use PDO;

class Category
{
    public $id;
    public $name;
    public $created_at;

    public static function all(): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public static function findById(int $id): ?self
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchObject(static::class) ?: null;
    }

    public static function create(string $name): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("INSERT INTO categories (name) VALUES (:name)");
        return $stmt->execute([':name' => $name]);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function update(string $name): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("UPDATE categories SET name = :name WHERE id = :id");
        return $stmt->execute([':name' => $name, ':id' => $this->id]);
    }

    public static function findByName(string $name): ?self
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM categories WHERE name = :name LIMIT 1");
        $stmt->execute([':name' => $name]);

        $result = $stmt->fetchObject(static::class);
        return $result ?: null;
    }
}
