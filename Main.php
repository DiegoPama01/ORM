<?php

$host = 'localhost';
$db = 'test_repositories';
$user = 'root';
$pass = '***';

require_once "Database/DbContext.php";

require_once "Models/Product/ProductRepository.php";
require_once 'Models/User/UserRepository.php';
require_once 'Models/Home/HomeRepository.php';
require_once 'Annotations/AnnotationManager.php';


$context = new DbContext($host, $db, $user, $pass);

$userRepository = new UserRepository($context);

$user = new User("Diego", 23);

$userRepository->insert($user);

// var_dump($userRepository->getById(37)); 

$user = $userRepository->getById(37);

$user->name = "Diegaso";

$userRepository->update($user);

$userRepository->delete(38);


$users=$userRepository->getAll();

foreach($users as $user){
    var_dump($user);
}
//$productRepository = new ProductRepository($context);

// $homeRepository = new HomeRepository($context);

// $homeRepository->insert(new Apartment("Test Street 10", 35500, true));

// $homeRepository->insert(new Bungalow("Test Street 100", 35500, 10));
// $bungalow = $homeRepository->getById(1,Bungalow::class);
// echo $bungalow;
// $bungalow->numFloors += 2000;
// $homeRepository->update($bungalow);
// $homeRepository->delete(10, Bungalow::class);

// $bungalows = $homeRepository->getAll(Bungalow::class);

// foreach ($bungalows as $bungalow) {
//     echo $bungalow . PHP_EOL;
// }
// echo print_r($homeRepository->getById(1,"Apartment"));

// $productRepository->insert(new Product("Avellanas", 120));

// $usersByAge = $userRepository->getUsersByAge(30);
// foreach ($usersByAge as $user) {
//     echo "User by age 30: " . $user->name . ", " . $user->age . "\n";
// }

// $usersByNameLike = $userRepository->getUsersByNameLike('Al');
// foreach ($usersByNameLike as $user) {
//     echo "User by name like 'Al': " . $user->name . ", " . $user->age . "\n";
// }

// $productsByPriceRange = $productRepository->getProductsByPriceRange(100, 200);
// foreach ($productsByPriceRange as $product) {
//     echo "Product in price range 100-200: " . $product->productName . ", " . $product->price . "\n";
// }
//$avellanas= $productRepository->getById(4);
//$avellanas->price = 200;
// $productRepository->update($avellanas);
// echo var_dump($productRepository->getById(4));
// $arr = [
//     "address" => "Test Array",
//     "postalCode" => 9999,
//     "allowPets" => false
// ];

// $bungalow = new Bungalow("Test Street 100", 35500, 10);
// $apartment = new Apartment($arr);

// //$bungalow->id = 1;

// $homeRepository->insert($apartment);

//print_r($apartment->getProperties());

// $annotations = AnnotationManager::getClassAnnotations(User::class);

// echo "Annotations for class User:\n";
// print_r($annotations['class']);

// echo "\nAnnotations for properties:\n";
// print_r($annotations['properties']);