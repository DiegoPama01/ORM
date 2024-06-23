<?php

namespace ORM\Models\Home;

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
