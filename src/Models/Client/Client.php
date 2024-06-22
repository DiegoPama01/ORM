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

/**
 * @ORM\Table(name="premium_clients")
 */
class PremiumClient extends Client
{
    /**
     * @ORM\Column(type="integer", name="idClientFK", references="clients(id)")
     */
    protected ?int $idClient;

    /**
     * @ORM\Column(type="string", name="password")
     */
    protected ?string $password;

    public function __construct($name = null, int $age = null, int $wage = null, $password=null)
    {
        parent::__construct($name,$age,$wage);
        $this->password = $password;
    }
}
