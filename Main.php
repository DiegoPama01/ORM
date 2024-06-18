<?php

$host = 'localhost';
$db = 'test_repositories';
$user = 'root';
$pass = '***********';

require_once "Database/DbContext.php";

$context = new DbContext($host, $db, $user, $pass);

require_once "D:/Proyectos/ORM/Models/Product/ProductRepository.php";
require_once 'D:/Proyectos/ORM/Models/User/UserRepository.php';

$userRepository = new UserRepository($context);
$productRepository = new ProductRepository($context);


$productRepository->insert(new Product("Avellanas", 120));

$usersByAge = $userRepository->getUsersByAge(30);
foreach ($usersByAge as $user) {
    echo "User by age 30: " . $user->name . ", " . $user->age . "\n";
}

$usersByNameLike = $userRepository->getUsersByNameLike('Al');
foreach ($usersByNameLike as $user) {
    echo "User by name like 'Al': " . $user->name . ", " . $user->age . "\n";
}

$productsByPriceRange = $productRepository->getProductsByPriceRange(100, 200);
foreach ($productsByPriceRange as $product) {
    echo "Product in price range 100-200: " . $product->productName . ", " . $product->price . "\n";
}
$avellanas= $productRepository->getById(4);
$avellanas->price = 200;
$productRepository->update($avellanas);
echo var_dump($productRepository->getById(4));
