<?php

require_once __DIR__ . '/vendor/autoload.php';

use ORM\Database\DbContext;
use ORM\Annotations\AnnotationManager;
use ORM\Models\User\UserRepository;
use ORM\Models\User\User;

$host = 'localhost';
$db = 'test_repositories';
$user = 'root';
$pass = '***';


$context = new DbContext($host, $db, $user, $pass);

$userRepository = new UserRepository($context);

$user = $userRepository->getById(37);

echo $user;

$annotations = AnnotationManager::getClassAnnotations(User::class);

echo "Annotations for table User:\n";
print_r($annotations);
