<?php
abstract class Entity
{
    private static string $table;
    public $id;

    public static function getTable() : string {
        return static::$table;
    }

    public function __toString()
    {
        return get_class($this) . " (ID: " . $this->id . ")" . PHP_EOL;
    }
}

