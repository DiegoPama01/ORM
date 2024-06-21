<?php
include_once "D:/Proyectos/ORM/Database/Repository.php";
include_once "Home.php";
class HomeRepository extends Repository
{
    public function __construct(DbContext $context)
    {
        parent::__construct($context, Home::class);
    }

    public function getAll($entityClass=null){
        return $this->context->getAll($entityClass);
    }

}
