<?php
namespace ORM\Models\Products;
use ORM\Models\Entity;

class Product extends Entity
{
    public ?string $productName;
    public ?int $price;

    public function __construct($productName=null, int $price=null)
    {
        $this->productName = $productName;
        $this->price = $price;
    }
}
