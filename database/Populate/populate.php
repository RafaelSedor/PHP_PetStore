<?php

require_once __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;

$db = Database::getDatabaseConn();

$passwordAdmin = password_hash('adminpassword', PASSWORD_DEFAULT);
$passwordUser = password_hash('userpassword', PASSWORD_DEFAULT);

$sqlAdmin = "INSERT INTO users (name, email, password, role) VALUES (:adminName, :adminEmail, :adminPassword, 'admin')";
$stmtAdmin = $db->prepare($sqlAdmin);
$stmtAdmin->execute([
    ':adminName' => 'Admin User',
    ':adminEmail' => 'admin@petstore.com',
    ':adminPassword' => $passwordAdmin,
]);

$sqlUser = "INSERT INTO users (name, email, password, role) VALUES (:userName, :userEmail, :userPassword, 'user')";
$stmtUser = $db->prepare($sqlUser);
$stmtUser->execute([
    ':userName' => 'Default User',
    ':userEmail' => 'user@petstore.com',
    ':userPassword' => $passwordUser,
]);

$userId = $db->lastInsertId();
$pets = [
    ['name' => 'Pinsher', 'species' => 'Cachorro', 'age' => 4, 'user_id' => $userId],
    ['name' => 'Agata', 'species' => 'Gato', 'age' => 7, 'user_id' => $userId],
    ['name' => 'Maraisa', 'species' => 'Gato', 'age' => 1, 'user_id' => $userId],
    ['name' => 'Maiara', 'species' => 'Gato', 'age' => 1, 'user_id' => $userId],
];

$sqlPet = "INSERT INTO pets (name, species, age, user_id) VALUES (:name, :species, :age, :user_id)";
$stmtPet = $db->prepare($sqlPet);

foreach ($pets as $pet) {
    $stmtPet->execute([
        ':name' => $pet['name'],
        ':species' => $pet['species'],
        ':age' => $pet['age'],
        ':user_id' => $pet['user_id'],
    ]);
}

echo "Banco de dados populado com sucesso.";
