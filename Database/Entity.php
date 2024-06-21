<?php
abstract class Entity
{
    /**
     * @ORM\Column(type="int", name="id")
     * @ORM\GeneratedValue()
     * @ORM\Id()
     */
    private $id;

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __toString()
    {
        return get_class($this) . " (ID: " . $this->id . ")" . PHP_EOL;
    }
}
