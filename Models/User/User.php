<?php
include_once "../ORM/Database/Entity.php";
class User extends Entity
{
    public string | null $name;
    public int | null $age;

    public function __construct($name=null, int $age=null)
    {
        $this->name = $name;
        $this->age = $age;
    }
}