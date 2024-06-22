<?php

require_once __DIR__ . '/vendor/autoload.php';

use ORM\Database\DbContext;
use ORM\Models\Client;
use ORM\Models\Home;
use ORM\Annotations\AnnotationManager;

$host = 'localhost';
$db = 'test_repositories';
$user = 'root';
$pass = '***';

$context = new DbContext($host,$db,$user,$pass);

$repository = new Client\ClientRepository($context);

$client = $repository->getById(2,Client\Client::class);

print_r($client);

$client -> wage = 1100;

$repository->update($client);

// print_r($repository->getById(72,Client\Client::class));

// print_r(AnnotationManager::getClassAnnotations(Client\Client::class));
// print_r(AnnotationManager::getClassAnnotations(Client\PremiumClient::class));
// print_r(AnnotationManager::getClassAnnotations(Home\Home::class));
// print_r(AnnotationManager::getClassAnnotations(Home\Apartment::class));
// print_r(AnnotationManager::getClassAnnotations(Home\Bungalow::class));


