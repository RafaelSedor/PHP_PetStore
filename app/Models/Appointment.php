<?php

namespace App\Models;

use Core\Database\Database;
use PDO;

class Appointment
{
    public $id;
    public $user_id;
    public $user_name;
    public $pet_id;
    public $pet_name;
    public $service_id;
    public $service_name;
    public $service_price;
    public $status;
    public $created_at;
    public $updated_at;

    public static function all(): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->query("
            SELECT appointments.*, 
                   users.name AS user_name, 
                   pets.name AS pet_name, 
                   services.name AS service_name, 
                   services.price AS service_price 
            FROM appointments
            INNER JOIN users ON appointments.user_id = users.id
            INNER JOIN pets ON appointments.pet_id = pets.id
            INNER JOIN services ON appointments.service_id = services.id");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function findById(int $id): ?self
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("
            SELECT appointments.*, 
                   users.name AS user_name, 
                   pets.name AS pet_name, 
                   services.name AS service_name, 
                   services.price AS service_price 
            FROM appointments
            INNER JOIN users ON appointments.user_id = users.id
            INNER JOIN pets ON appointments.pet_id = pets.id
            INNER JOIN services ON appointments.service_id = services.id
            WHERE appointments.id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchObject(self::class) ?: null;
    }

    public static function findByUser(int $userId): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("
            SELECT appointments.*, 
                   pets.name AS pet_name, 
                   services.name AS service_name, 
                   services.price AS service_price 
            FROM appointments
            INNER JOIN pets ON appointments.pet_id = pets.id
            INNER JOIN services ON appointments.service_id = services.id
            WHERE appointments.user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function save(): bool
    {
        $db = Database::getDatabaseConn();

        if (isset($this->id)) {
            $stmt = $db->prepare("
                UPDATE appointments 
                SET user_id = :user_id, 
                    pet_id = :pet_id, 
                    service_id = :service_id, 
                    status = :status
                WHERE id = :id");
            return $stmt->execute([
                ':id' => $this->id,
                ':user_id' => $this->user_id,
                ':pet_id' => $this->pet_id,
                ':service_id' => $this->service_id,
                ':status' => $this->status,
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO appointments (user_id, pet_id, service_id, status) 
                VALUES (:user_id, :pet_id, :service_id, :status)");
            $result = $stmt->execute([
                ':user_id' => $this->user_id,
                ':pet_id' => $this->pet_id,
                ':service_id' => $this->service_id,
                ':status' => $this->status,
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
        $stmt = $db->prepare("DELETE FROM appointments WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public function updateStatus(string $status): bool
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->prepare("UPDATE appointments SET status = :status WHERE id = :id");
        return $stmt->execute([
            ':status' => $status,
            ':id' => $this->id,
        ]);
    }

    public static function getAllWithDetails(): array
    {
        $db = Database::getDatabaseConn();
        $stmt = $db->query("
            SELECT 
                a.id, 
                a.status, 
                a.created_at,
                u.name AS user_name, 
                p.name AS pet_name, 
                s.name AS service_name
            FROM appointments a
            INNER JOIN users u ON a.user_id = u.id
            INNER JOIN pets p ON a.pet_id = p.id
            INNER JOIN services s ON a.service_id = s.id
            ORDER BY a.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
