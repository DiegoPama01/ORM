<?php
include_once "../ORM/Database/Entity.php";
class Product extends Entity
{
    protected static string $table = 'products';
    public ?string $productName;
    public ?int $price;

    public function __construct($productName=null, int $price=null)
    {
        $this->productName = $productName;
        $this->price = $price;
    }
}
