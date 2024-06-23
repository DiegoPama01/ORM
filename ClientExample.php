<?php

use ORM\Database\DbContext;
use ORM\Models\Client;

$context = new DbContext($host,$db,$user,$pass);

$repository = new Client\ClientRepository($context);

$newItem = new Client\Client("Diego", 10, 10);

$repository->insert($newItem);

$item = $repository->getById(7);

$item->age++;

$repository->update($item);

$repository->delete($item->age);

$rows = $repository->getAll();

foreach($rows as $row){
    echo $row;
}
