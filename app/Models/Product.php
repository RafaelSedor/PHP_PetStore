<?php

namespace App\Models;

use Core\Database\Database;
use PDO;

class Product
{
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $image_url;
    public $created_at;
    public $category;

    public static function findById(int $id): ?self
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $product = self::mapDataToProduct($data);
            $product->category = Category::findById($product->category_id);
            return $product;
        }
        return null;
    }

    public static function findByCategory(int $categoryId): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM products WHERE category_id = :category_id");
        $stmt->execute([':category_id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function all(): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->query("SELECT * FROM products");
        $products = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);

        foreach ($products as $product) {
            $product->category = Category::findById($product->category_id);
        }

        return $products;
    }

    public function save(): bool
    {
        $db = Database::getDatabaseConn();

        if (isset($this->id)) {
            $sql = "UPDATE products SET name = :name, description = :description, price = :price, category_id = :category_id, image_url = :image_url WHERE id = :id";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                ':id' => $this->id,
                ':name' => $this->name,
                ':description' => $this->description,
                ':price' => $this->price,
                ':category_id' => $this->category_id,
                ':image_url' => $this->image_url,
            ]);
        } else {
            $sql = "INSERT INTO products (name, description, price, category_id, image_url) VALUES (:name, :description, :price, :category_id, :image_url)";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':name' => $this->name,
                ':description' => $this->description,
                ':price' => $this->price,
                ':category_id' => $this->category_id,
                ':image_url' => $this->image_url,
            ]);

            if ($result) {
                $this->id = $db->lastInsertId();
            }

            return $result;
        }
    }


    public function delete(): bool
    {
        if (!isset($this->id)) {
            return false;
        }

        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    private static function mapDataToProduct(array $data): self
    {
        $product = new self();
        $product->id = $data['id'];
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->category_id = $data['category_id'];
        $product->image_url = $data['image_url'];
        $product->created_at = $data['created_at'];
        return $product;
    }

    public static function create(array $data): bool
    {
        $db = Database::getDatabaseConn();

        $sql = "
        INSERT INTO products (name, description, price, category_id, image_url, created_at) 
        VALUES (:name, :description, :price, :category_id, :image_url, NOW())";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':category_id' => $data['category_id'],
            ':image_url' => $data['image_url'],
        ]);
    }
}
