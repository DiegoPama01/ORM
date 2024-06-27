<?php

namespace ORM\Models;
use ORM\Annotations\AnnotationManager;

abstract class Entity
{

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
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

    public function getProperties()
    {
        return get_object_vars($this);
    }

}
