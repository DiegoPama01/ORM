<?php

use ORM\Database\DbContext;
use ORM\Models\Product;

$context = new DbContext($host,$db,$user,$pass);

$repository = new Product\ProductRepository($context);

$newItem = new Product\Product("Prueba",40);

$repository->insert($newItem);

$item = $repository->getById(12);

$item->price++;

$repository->update($item);

$repository->delete($item->price);

$rows = $repository->getAll();

foreach($rows as $row){
    echo $row;
}
