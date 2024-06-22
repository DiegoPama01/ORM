<?php

require_once __DIR__ . '/vendor/autoload.php';

use ORM\Database\DbContext;
use ORM\Models\Home;

$host = 'localhost';
$db = 'test_repositories';
$user = 'root';
$pass = '***';


$context = new DbContext($host, $db, $user, $pass);

$repository = new Home\HomeRepository($context);

$rows = $repository->getAll(Home\Apartment::class);

$product = $rows[0];

$product->address = "Example $product->id";

$repository->update($product);

$repository->delete($rows[1]->id,Home\Apartment::class);

$repository->insert(new Home\Apartment(['address' => "test", 'postalCode' => 2020, 'allowPets' => true]));

foreach($rows as $row){
    echo $row;
}
