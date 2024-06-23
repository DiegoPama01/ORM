<?php

use ORM\Database\DbContext;
use ORM\Models\Home;
use ORM\Models\Home\Apartment;

$context = new DbContext($host,$db,$user,$pass);

$repository = new Home\ApartmentRepository($context);

$newItem = new Apartment("Street", 10, false);

$repository->insert($newItem);

$item = $repository->getById(221);

$item->postalCode++;

$repository->update($item);

$repository->delete($item->postalCode);

$rows = $repository->getAll();

foreach($rows as $row){
    echo $row;
}
