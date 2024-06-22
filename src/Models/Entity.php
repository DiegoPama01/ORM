<?php

namespace ORM\Models;

abstract class Entity
{
    /**
     * @ORM\Column(type="int", name="id")
     * @ORM\GeneratedValue()
     * @ORM\Id()
     */
    public $id;

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

    public function getParent()
    {
        $nameParentClass = get_parent_class($this);

        if ($nameParentClass === false || $nameParentClass === Entity::class) {
            return null;
        }

        $parent = new $nameParentClass();
        $properties = get_object_vars($this);

        foreach ($properties as $property => $value) {
            if (property_exists($parent, $property)) {
                $parent->$property = $value;
            }
        }

        return $parent;
    }

    public function getProperties(){
        return get_object_vars($this);
    }
}