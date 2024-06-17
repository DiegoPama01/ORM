<?php

class DbContext
{
    private $connection;

    public function __construct($host, $db, $user, $pass)
    {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $this->connection = new PDO($dsn, $user, $pass);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function getTableName($entityClass)
    {
        return strtolower($entityClass);
    }

    public function getAll($entityClass)
    {
        $tableName = $this->getTableName($entityClass);
        $stmt = $this->connection->query("SELECT * FROM `$tableName`");
        return $stmt->fetchAll(PDO::FETCH_CLASS, $entityClass);
    }

    public function getById($entityClass, $id)
    {
        $tableName = $this->getTableName($entityClass);
        $stmt = $this->connection->prepare("SELECT * FROM `$tableName` WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject($entityClass);
    }

    public function insert($entity)
    {
        $tableName = $this->getTableName(get_class($entity));
        $columns = array_keys(get_object_vars($entity));
        $values = array_map(function ($column) {
            return ":$column";
        }, $columns);

        $sql = "INSERT INTO `$tableName` (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")";
        $stmt = $this->connection->prepare($sql);
        foreach ($columns as $column) {
            $stmt->bindValue(":$column", $entity->$column);
        }
        $stmt->execute();
        $entity->id = $this->connection->lastInsertId();
    }

    public function update($entity)
    {
        $tableName = $this->getTableName(get_class($entity));
        $columns = array_keys(get_object_vars($entity));
        $setClause = implode(", ", array_map(function ($column) {
            return "$column = :$column";
        }, $columns));

        $sql = "UPDATE `$tableName` SET $setClause WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        foreach ($columns as $column) {
            $stmt->bindValue(":$column", $entity->$column);
        }
        $stmt->bindValue(':id', $entity->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function delete($entityClass, $id)
    {
        $tableName = $this->getTableName($entityClass);
        $stmt = $this->connection->prepare("DELETE FROM `$tableName` WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
