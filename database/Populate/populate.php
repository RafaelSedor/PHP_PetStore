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

$categories = [
    ['name' => 'Brinquedos'],
    ['name' => 'Alimentos'],
    ['name' => 'Acessórios'],
    ['name' => 'Higiene'],
];

$sqlCategory = "INSERT INTO categories (name) VALUES (:name)";
$stmtCategory = $db->prepare($sqlCategory);

foreach ($categories as $category) {
    $stmtCategory->execute([
        ':name' => $category['name'],
    ]);
}

$products = [
    ['name' => 'Bola para Cachorro', 'description' => 'Uma bola resistente para cachorros de todos os tamanhos.', 'price' => 29.90, 'category_id' => 1, 'image_url' => 'https://down-br.img.susercontent.com/file/32e7b64738c4936f67cade4301210821'],
    ['name' => 'Ração para Gatos', 'description' => 'Ração premium para gatos adultos.', 'price' => 89.90, 'category_id' => 2, 'image_url' => 'https://down-br.img.susercontent.com/file/32e7b64738c4936f67cade4301210821'],
    ['name' => 'Coleira Ajustável', 'description' => 'Coleira confortável e ajustável para cachorros.', 'price' => 49.90, 'category_id' => 3, 'image_url' => 'https://down-br.img.susercontent.com/file/32e7b64738c4936f67cade4301210821'],
    ['name' => 'Shampoo para Pets', 'description' => 'Shampoo especial para cuidar do pelo dos pets.', 'price' => 34.90, 'category_id' => 4, 'image_url' => 'https://down-br.img.susercontent.com/file/32e7b64738c4936f67cade4301210821'],
];

$sqlProduct = "INSERT INTO products (name, description, price, category_id, image_url) VALUES (:name, :description, :price, :category_id, :image_url)";
$stmtProduct = $db->prepare($sqlProduct);

foreach ($products as $product) {
    $stmtProduct->execute([
        ':name' => $product['name'],
        ':description' => $product['description'],
        ':price' => $product['price'],
        ':category_id' => $product['category_id'],
        ':image_url' => $product['image_url'],
    ]);
}

echo "Banco de dados populado com sucesso.";
