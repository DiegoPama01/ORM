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

/**
 * @ORM\Table(name="apartments")
 */
class Apartment extends Home
{
    /**
     * @ORM\Column(type="boolean", name="allowPets")
     */
    protected bool $allowPets;

    public function __construct($array = [])
    {
        $address = $array['address'] ?? null;
        $postalCode = $array['postalCode'] ?? null;
        $allowPets = $array['allowPets'] ?? false;

        parent::__construct($address, $postalCode);
        $this->allowPets = $allowPets;
    }


    public function __toString()
    {
        $allowPetsTxt = $this->allowPets ? 'true' : 'false';
        return parent::__toString() . "Allow pets: $allowPetsTxt" . PHP_EOL;
    }
}

/**
 * @ORM\Table(name="bungalows")
 */
class Bungalow extends Home
{
    /**
     * @ORM\Column(type="integer", name="numFloors")
     */
    protected int $numFloors;

    public function __construct($address = null, int $postalCode = null, $numFloors = 1)
    {
        parent::__construct($address, $postalCode);
        $this->numFloors = $numFloors;
    }

    public function __toString()
    {
        return parent::__toString()  . "Number of floors: $this->numFloors" . PHP_EOL;
    }
}
