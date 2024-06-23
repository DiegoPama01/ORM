<?php
namespace ORM\Models\Product;
use ORM\Models\Entity;

/**
 * @ORM\Table(name="products")
 */
class Product extends Entity
{
    /**
     * @ORM\Column(type="string", name="productName")
     */
    protected ?string $productName;
    
    /**
     * @ORM\Column(type="integer", name="price")
     */
    protected ?int $price;

    public function __construct($productName=null, int $price=null)
    {
        $this->productName = $productName;
        $this->price = $price;
    }

    public function __toString()
    {
        return parent::__toString()  . "Name: $this->productName" . PHP_EOL . "Price: $this->price" . PHP_EOL . PHP_EOL;
    }
}
