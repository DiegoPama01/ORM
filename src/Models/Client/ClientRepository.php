<?php
namespace ORM\Models\Client;

use ORM\Database\DbContext;
use ORM\Models\Repository;
use PDO;

class ClientRepository extends Repository
{

    public function __construct(DbContext $context)
    {
        parent::__construct($context, Client::class);
    }
}
