<?php
include_once "../ORM/Database/Entity.php";

use ORM\Annotations as ORM;

/**
 * @ORM\Table(name="users")
 */
class User extends Entity
{
    /**
     * @ORM\Column(type="string", name="name")
     */
    protected ?string $name;
    /**
     * @ORM\Column(type="integer", name="age")
     */
    protected ?int $age;

    public function __construct($name = null, int $age = null)
    {
        $this->name = $name;
        $this->age = $age;
    }
}
