<?php

namespace ORM\Models\Product;

use ORM\Database\DbContext;
use ORM\Models\Repository;
use PDO;

class ProductRepository extends Repository
{
    public function __construct(DbContext $context)
    {
        parent::__construct($context, Product::class);
    }

    public function getProductsByPriceRange($minPrice, $maxPrice)
    {
        $stmt = $this->context->getConnection()->prepare("SELECT * FROM $this->table->name WHERE price BETWEEN :minPrice AND :maxPrice");
        $stmt->bindParam(':minPrice', $minPrice, PDO::PARAM_STR);
        $stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt-> fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->className);
    }
}
