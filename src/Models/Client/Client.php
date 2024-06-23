<?php

namespace ORM\Models\Client;

use ORM\Models\Entity;

/**
 * @ORM\Table(name="clients")
 */
class Client extends Entity
{
    /**
     * @ORM\Column(type="string", name="name")
     */
    protected ?string $name;
    /**
     * @ORM\Column(type="integer", name="age")
     */
    protected ?int $age;

    /**
     * @ORM\Column(type="integer", name="wage")
     */
    protected ?int $wage;

    public function __construct($name = null, int $age = null, int $wage = null)
    {
        $this->name = $name;
        $this->age = $age;
        $this->wage = $wage;
    }
}

