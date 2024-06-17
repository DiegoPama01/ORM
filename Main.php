<?php

$host = 'localhost';
$db = 'test_db';
$user = 'root';
$pass = '';

$context = new DbContext($host, $db, $user, $pass);

$userRepository = new UserRepository($context);
$productRepository = new ProductRepository($context);

$usersByAge = $userRepository->getUsersByAge(30);
foreach ($usersByAge as $user) {
    echo "User by age 30: " . $user->name . ", " . $user->age . "\n";
}

$usersByNameLike = $userRepository->getUsersByNameLike('Al');
foreach ($usersByNameLike as $user) {
    echo "User by name like 'Al': " . $user->name . ", " . $user->age . "\n";
}

$productsByPriceRange = $productRepository->getProductsByPriceRange(10, 100);
foreach ($productsByPriceRange as $product) {
    echo "Product in price range 10-100: " . $product->productName . ", " . $product->price . "\n";
}
