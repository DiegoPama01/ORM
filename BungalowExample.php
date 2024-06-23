<?php

use ORM\Database\DbContext;
use ORM\Models\Home;

$context = new DbContext($host,$db,$user,$pass);

$repository = new Home\BungalowRepository($context);

$newItem = new Home\Bungalow("Street", 10, 10);

$repository->insert($newItem);

$item = $repository->getById(1);

$item->postalCode++;

$repository->update($item);

$repository->delete($item->postalCode);

$rows = $repository->getAll();

foreach($rows as $row){
    echo $row;
}
