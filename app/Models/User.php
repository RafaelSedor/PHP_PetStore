<?php

namespace App\Models;

use Core\Database\Database;
use PDO;

class User
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $created_at;
    public $updated_at;
    public $pets;

    public static function findById(int $id): ?self
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return self::mapDataToUser($data);
        }

        return null;
    }

    public static function findByEmail(string $email): ?self
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return self::mapDataToUser($data);
        }

        return null;
    }

    public static function all(): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->query("SELECT * FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($data) => self::mapDataToUser($data), $users);
    }

    public function save(): bool
    {
        $db = Database::getDatabaseConn();

        if (isset($this->id)) {
            $sql = "UPDATE users SET name = :name, email = :email, password = :password, role = :role, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                ':id' => $this->id,
                ':name' => $this->name,
                ':email' => $this->email,
                ':password' => $this->password,
                ':role' => $this->role,
            ]);
        } else {
            $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([
                ':name' => $this->name,
                ':email' => $this->email,
                ':password' => $this->password,
                ':role' => $this->role,
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
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function deleteByEmail(string $email): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("DELETE FROM users WHERE email = :email");
        return $stmt->execute([':email' => $email]);
    }

    private static function mapDataToUser(array $data): self
    {
        $user = new self();
        $user->id = $data['id'];
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->role = $data['role'];
        $user->created_at = $data['created_at'];
        $user->updated_at = $data['updated_at'];

        return $user;
    }

    public static function create(array $data): int
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("
        INSERT INTO users (name, email, password, role, created_at, updated_at) 
        VALUES (:name, :email, :password, :role, NOW(), NOW())");

        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':role' => $data['role'] ?? 'user',
        ]);

        return (int) $db->lastInsertId();
    }

    public function getPets(): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("SELECT * FROM pets WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $this->id]);
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
}
