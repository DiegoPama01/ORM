<?php

class ProductRepository extends Repository
{
    public function __construct($context)
    {
        parent::__construct($context, 'Product');
    }

    public function getProductsByPriceRange($minPrice, $maxPrice)
    {
        $stmt = $this->context->connection->prepare("SELECT * FROM products WHERE price BETWEEN :minPrice AND :maxPrice");
        $stmt->bindParam(':minPrice', $minPrice, PDO::PARAM_STR);
        $stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
    }
}
