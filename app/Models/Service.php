<?php

namespace App\Models;

use Core\Database\Database;
use PDO;

class Service
{
    public $id;
    public $name;
    public $description;
    public $price;
    public $created_at;
    public $updated_at;
    public $image_url;

    public static function all(): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->query("SELECT * FROM services");
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function findById(int $id): ?self
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM services WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchObject(self::class) ?: null;
    }

    public function save(): bool
    {
        $db = Database::getDatabaseConn();

        if (isset($this->id)) {
            $sql = "UPDATE services SET name = :name, description = :description, price = :price, image_url = :image_url WHERE id = :id";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                ':id' => $this->id,
                ':name' => $this->name,
                ':description' => $this->description,
                ':price' => $this->price,
                ':image_url' => $this->image_url,
            ]);
        } else {
            $sql = "INSERT INTO services (name, description, price, image_url, created_at) VALUES (:name, :description, :price, :image_url, NOW())";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':name' => $this->name,
                ':description' => $this->description,
                ':price' => $this->price,
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
        $stmt = $db->prepare("DELETE FROM services WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function findByUserId(int $userId): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("
        SELECT s.*, p.name AS pet_name, a.status, a.updated_at
        FROM services s
        INNER JOIN appointments a ON s.id = a.service_id
        INNER JOIN pets p ON a.pet_id = p.id
        WHERE a.user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
