<?php

namespace App\Models;

use Core\Database\Database;
use PDO;

class Pet
{
    public $id;
    public $name;
    public $species;
    public $age;
    public $user_id;
    public $created_at;

    public static function findById(int $id): ?self
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM pets WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return self::mapDataToPet($data);
        }

        return null;
    }

    public static function findByName(string $name): ?self
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM pets WHERE name = :name LIMIT 1");
        $stmt->execute([':name' => $name]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return self::mapDataToPet($data);
        }

        return null;
    }

    public static function findByUserId(int $userId): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM pets WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);

        $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($data) => self::mapDataToPet($data), $pets);
    }

    public function save(): bool
    {
        $db = Database::getDatabaseConn();

        if (isset($this->id)) {
            $sql = "UPDATE pets SET name = :name, species = :species, age = :age, user_id = :user_id WHERE id = :id";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                ':id' => $this->id,
                ':name' => $this->name,
                ':species' => $this->species,
                ':age' => $this->age,
                ':user_id' => $this->user_id,
            ]);
        } else {
            $sql = "INSERT INTO pets (name, species, age, user_id) VALUES (:name, :species, :age, :user_id)";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':name' => $this->name,
                ':species' => $this->species,
                ':age' => $this->age,
                ':user_id' => $this->user_id,
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
        $stmt = $db->prepare("DELETE FROM pets WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function deleteByName(string $name): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("DELETE FROM pets WHERE name = :name");
        return $stmt->execute([':name' => $name]);
    }

    private static function mapDataToPet(array $data): self
    {
        $pet = new self();
        $pet->id = $data['id'];
        $pet->name = $data['name'];
        $pet->species = $data['species'];
        $pet->age = $data['age'];
        $pet->user_id = $data['user_id'];
        $pet->created_at = $data['created_at'];

        return $pet;
    }


}
