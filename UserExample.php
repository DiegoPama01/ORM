<?php

use ORM\Database\DbContext;
use ORM\Models\User;

$context = new DbContext($host,$db,$user,$pass);

$repository = new User\UserRepository($context);

$newItem = new User\User("Diego", 10);

$repository->insert($newItem);

$item = $repository->getById(37);

$item->age++;

$repository->update($item);

$repository->delete($item->age);

$rows = $repository->getAll();

foreach($rows as $row){
    echo $row;
}
