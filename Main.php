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

$rows = $repository->getAll();

$item = $repository->getById(1);

$item->price = 10;

$repository->update($item);

foreach($rows as $row){
    echo $row;
}
