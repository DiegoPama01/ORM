<?php
include_once "../ORM/Database/Entity.php";
class Product extends Entity
{
    public string | null $productName;
    public int | null $price;

    public function __construct($productName=null, int $price=null)
    {
        $this->productName = $productName;
        $this->price = $price;
    }
}
