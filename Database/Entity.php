<?php
abstract class Entity
{
    private static string $table;
    public $id;

    public static function getTable() : string {
        return static::$table;
    }
}

