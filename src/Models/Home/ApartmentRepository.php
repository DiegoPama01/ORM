<?php
namespace ORM\Models\Home;

use ORM\Database\DbContext;
use ORM\Models\Repository;

class ApartmentRepository extends Repository
{
    public function __construct(DbContext $context)
    {
        parent::__construct($context, Apartment::class);
    }
}
