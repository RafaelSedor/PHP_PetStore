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
    public $image_url;
    public $created_at;
    public $categories;

    public static function findById(int $id): ?self
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $product = self::mapDataToProduct($data);
            $product->categories = self::findCategories($id);
            return $product;
        }
        return null;
    }

    public static function findCategories(int $productId): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("
            SELECT c.* FROM categories c
            INNER JOIN product_categories pc ON c.id = pc.category_id
            WHERE pc.product_id = :product_id
        ");
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function syncCategories(): void
    {
        if (!is_array($this->categories)) {
            return;
        }

        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("DELETE FROM product_categories WHERE product_id = :product_id");
        $stmt->execute([':product_id' => $this->id]);

        foreach ($this->categories as $categoryId) {
            $stmt = $db->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (:product_id, :category_id)");
            $stmt->execute([':product_id' => $this->id, ':category_id' => $categoryId]);
        }
    }

    public static function all(): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->query("SELECT * FROM products");
        $products = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);

        foreach ($products as $product) {
            $product->categories = self::findCategories($product->id);
        }

        return $products;
    }

    public function save(): bool
    {
        $db = Database::getDatabaseConn();

        if (isset($this->id)) {
            $sql = "UPDATE products SET name = :name, description = :description, price = :price, image_url = :image_url WHERE id = :id";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':id' => $this->id,
                ':name' => $this->name,
                ':description' => $this->description,
                ':price' => $this->price,
                ':image_url' => $this->image_url,
            ]);

            if ($result) {
                $this->syncCategories();
            }

            return $result;
        } else {
            $sql = "INSERT INTO products (name, description, price, image_url) VALUES (:name, :description, :price, :image_url)";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':name' => $this->name,
                ':description' => $this->description,
                ':price' => $this->price,
                ':image_url' => $this->image_url,
            ]);

            if ($result) {
                $this->id = $db->lastInsertId();
                $this->syncCategories();
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
        $product->image_url = $data['image_url'];
        $product->created_at = $data['created_at'];
        $product->categories = self::findCategories($product->id);

        return $product;
    }

    public static function create(array $data): bool
    {
        $db = Database::getDatabaseConn();

        $sql = "INSERT INTO products (name, description, price, image_url, created_at) 
                VALUES (:name, :description, :price, :image_url, NOW())";

        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':image_url' => $data['image_url'],
        ]);

        if ($result) {
            $productId = $db->lastInsertId();
            $product = new self();
            $product->id = $productId;
            $product->categories = $data['categories'] ?? [];
            $product->syncCategories();
        }

        return $result;
    }

    public static function findByCategory(int $categoryId): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("
        SELECT p.* FROM products p
        INNER JOIN product_categories pc ON p.id = pc.product_id
        WHERE pc.category_id = :category_id");
        $stmt->execute([':category_id' => $categoryId]);
        $products = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);

        foreach ($products as $product) {
            $product->categories = self::findCategories($product->id);
        }

        return $products;
    }
}
