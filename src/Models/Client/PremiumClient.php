<?php

namespace ORM\Models\Client;

use ORM\Models\Entity;


/**
 * @ORM\Table(name="premium_clients")
 */
class PremiumClient extends Entity
{
    /**
     * @ORM\Column(type="integer", name="idClientFK", references="clients(id)")
     */
    protected ?int $idClientFK;

    /**
     * @ORM\Column(type="string", name="password")
     */
    protected ?string $password;

    public function __construct($password=null)
    {
        $this->password = $password;
        $this->idClientFK = null;
    }
}
