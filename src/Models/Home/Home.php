<?php

namespace ORM\Models\Home;

use ORM\Models\Entity;

abstract class Home extends Entity
{
    /**
     * @ORM\Column(type="string", name="address")
     */
    protected ?string $address;
    /**
     * @ORM\Column(type="integer", name="postalCode")
     */
    protected ?int $postalCode;

    public function __construct($address = null, int $postalCode = null)
    {
        $this->address = $address;
        $this->postalCode = $postalCode;
    }

    public function __toString()
    {
        return parent::__toString() . "Address: $this->address" . PHP_EOL . "Postal code: $this->postalCode" . PHP_EOL;
    }
}




