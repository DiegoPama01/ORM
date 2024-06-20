<?php
include_once "../ORM/Database/Entity.php";
abstract class Home extends Entity
{
    public ?string $address;
    public ?int $postalCode;

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

class Apartment extends Home
{
    protected static string $table = 'apartments';
    public bool $allowPets;

    public function __construct($address = null, int $postalCode = null, $allowPets = false)
    {
        parent::__construct($address, $postalCode);
        $this->allowPets = $allowPets;
    }
}

class Bungalow extends Home
{
    protected static string $table = 'bungalows';
    public int $numFloors;
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
