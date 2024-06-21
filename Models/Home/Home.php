<?php
include_once "../ORM/Database/Entity.php";
abstract class Home extends Entity
{
    protected string $address;
    protected int $postalCode;

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
    protected bool $allowPets;

    public function __construct($array)
    {
        parent::__construct($array['address'], $array['postalCode']);
        $this->allowPets = $array['allowPets'] ? $array['allowPets'] : false;
    }


    public function __toString()
    {
        $allowPetsTxt = $this->allowPets ? 'true' : 'false';
        return parent::__toString() . "Allow pets: $allowPetsTxt" . PHP_EOL;
    }

}

class Bungalow extends Home
{
    protected static string $table = 'bungalows';
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
