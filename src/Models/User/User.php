<?php
namespace ORM\Models\User;
use ORM\Models\Entity;
/**
 * @ORM\Table(name="users")
 */
class User extends Entity
{
    /**
     * @ORM\Column(type="int", name="id", isPrimaryKey="true")
     */
    protected $id;
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

    public function __toString()
    {
        return "Name: $this->name" . PHP_EOL . "Age: $this->age" . PHP_EOL;
    }
}
