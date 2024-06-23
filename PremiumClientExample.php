<?php

use ORM\Database\DbContext;
use ORM\Models\Client;

$context = new DbContext($host,$db,$user,$pass);

$premiumRepository = new Client\PremiumClientRepository($context);
$clientRepository = new Client\ClientRepository($context);

$newItem = new Client\Client("Diego", 10, 10);
$newPremium = new Client\PremiumClient("pass");

//insertamos Client

$clientRepository->insert($newItem);

//añadimos la id a la FK

$newPremium->idClientFK = $newItem->id;

$premiumRepository->insert($newPremium);

$item = $premiumRepository->getById(7);

//Obtenemos el objeto al que hace referencia una clave Foranea

$otro = $premiumRepository->getReferredEntity($item, 'idClientFK', Client\Client::class);

$item->password = "Contra cambiada";

$premiumRepository->update($item);

$premiumRepository->delete(10);

$rows = $premiumRepository->getAll();

foreach($rows as $row){
    echo $row;
}
