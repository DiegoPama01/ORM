<?php
include_once "../ORM/Database/Entity.php";
abstract class Home extends Entity
{
    public ?string $direction;
    public ?int $postalCode;

    public function __construct($direction = null, int $postalCode = null)
    {
        $this->direction = $direction;
        $this->postalCode = $postalCode;
    }
}

class Apartment extends Home
{
    protected static string $table = 'apartments';
    public bool $allowPets;

    public function __construct($direction = null, int $postalCode = null, $allowPets=false)
    {
        parent::__construct($direction, $postalCode);
        $this->allowPets = $allowPets;
    }
}

class Bungalow extends Home{
    protected static string $table = 'bungalows';
    public int $numFloors;
    public function __construct($direction = null, int $postalCode = null, $numFloors=1)
    {
        parent::__construct($direction, $postalCode);
        $this->numFloors = $numFloors;
    }
}
