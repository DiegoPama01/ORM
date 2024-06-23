<?php
namespace ORM\Models\Home;

use ORM\Database\DbContext;
use ORM\Models\Repository;

class BungalowRepository extends Repository
{
    public function __construct(DbContext $context)
    {
        parent::__construct($context, Bungalow::class);
    }
}

