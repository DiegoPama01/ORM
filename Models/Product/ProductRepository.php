<?php

include_once "D:/Proyectos/ORM/Database/Repository.php";
include_once "Product.php";

class ProductRepository extends Repository
{
    public function __construct(DbContext $context)
    {
        parent::__construct($context, 'Product', "products");
    }

    public function getProductsByPriceRange($minPrice, $maxPrice)
    {
        $stmt = $this->context->getConnection()->prepare("SELECT * FROM $this->tableName WHERE price BETWEEN :minPrice AND :maxPrice");
        $stmt->bindParam(':minPrice', $minPrice, PDO::PARAM_STR);
        $stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt-> fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->entityClass);
    }
}
