<?php
namespace ORM\Models\Home;

use ORM\Database\DbContext;
use ORM\Models\Repository;
use PDO;

class HomeRepository extends Repository
{
    public function __construct(DbContext $context)
    {
        parent::__construct($context, Home::class);
    }

    public function getAll($entityClass=null){
        return parent::getAll($entityClass);
    }

}
