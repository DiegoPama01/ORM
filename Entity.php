<?php
abstract class Entity
{
    public $id;
}

class User extends Entity
{
    public $name;
    public $age;
}

class Product extends Entity
{
    public $productName;
    public $price;
}
