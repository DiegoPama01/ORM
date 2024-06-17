<?php

$host = 'localhost';
$db = 'test_db';
$user = 'root';
$pass = '';

$context = new DbContext($host, $db, $user, $pass);

$userRepository = new Repository($context, 'User');
$productRepository = new Repository($context, 'Product');

$newUser = new User();
$newUser->name = 'Alice';
$newUser->age = 30;
$userRepository->insert($newUser);

$users = $userRepository->getAll();
foreach ($users as $user) {
    echo $user->name . ', ' . $user->age . "\n";
}

$user = $userRepository->getById(1);
$user->age = 31;
$userRepository->update($user);

$userRepository->delete(1);
