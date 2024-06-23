<?php

require_once __DIR__ . '/vendor/autoload.php';

use ORM\Database\DbContext;
use ORM\Models\Product;

$host = 'localhost';
$db = 'test_repositories';
$user = 'root';
$pass = '***';

$context = new DbContext($host,$db,$user,$pass);

$repository = new Product\ProductRepository($context);

$newItem = new Product\Product("Prueba",40);

$repository->insert($newItem);

$item = $repository->getById(1);

$item->price++;

$repository->update($item);

$repository->delete($item->price);

$rows = $repository->getAll();

foreach($rows as $row){
    echo $row;
}
