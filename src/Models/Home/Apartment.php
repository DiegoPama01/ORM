<?php

namespace ORM\Models\Home;

/**
 * @ORM\Table(name="apartments")
 */
class Apartment extends Home
{
    /**
     * @ORM\Column(type="boolean", name="allowPets")
     */
    protected bool $allowPets;

    public function __construct($address = null, int $postalCode = null, $allowPets = false)
    {
        parent::__construct($address, $postalCode);
        $this->allowPets = $allowPets;
    }


    public function __toString()
    {
        $allowPetsTxt = $this->allowPets ? 'true' : 'false';
        return parent::__toString() . "Allow pets: $allowPetsTxt" . PHP_EOL;
    }
}
