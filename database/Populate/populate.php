<?php

require_once __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;

$db = Database::getDatabaseConn();

$passwordAdmin = password_hash('adminpassword', PASSWORD_DEFAULT);
$passwordUser = password_hash('userpassword', PASSWORD_DEFAULT);

$sqlAdmin = "INSERT INTO users (name, email, password, role) VALUES 
            (:adminName, :adminEmail, :adminPassword, 'admin')";
$stmtAdmin = $db->prepare($sqlAdmin);
$stmtAdmin->execute([
    ':adminName' => 'Admin User',
    ':adminEmail' => 'admin@petstore.com',
    ':adminPassword' => $passwordAdmin,
]);

$sqlUser = "INSERT INTO users (name, email, password, role) VALUES 
            (:userName, :userEmail, :userPassword, 'user')";
$stmtUser = $db->prepare($sqlUser);
$stmtUser->execute([
    ':userName' => 'Default User',
    ':userEmail' => 'user@petstore.com',
    ':userPassword' => $passwordUser,
]);

echo "Banco de dados populado com sucesso.";
