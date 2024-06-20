<?php

class DbContext
{
    private $connection;

    public function __construct($host, $db, $user, $pass)
    {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException('Connection error: ' . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function createEntity($data, $entityClass)
    {
        $entity = new $entityClass;

        foreach ($data as $column => $value) {
            if (property_exists($entity, $column)) {
                $entity->$column = $value;
            }
        }

        return $entity;
    }

    public function getAll($entityClass)
    {
        $tableName = $entityClass::getTable();
        $sql = "SELECT * FROM `$tableName`";
        $stmt = $this->connection->query($sql);
        $stmt->execute();
        $instances = [];
        while ($instance = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $instances[] = $this->createEntity($instance, $entityClass);
        }
        return $instances;
    }

    public function getById($entityClass, $id)
    {
        $tableName = $entityClass::getTable();
        $sql = "SELECT * FROM `$tableName` WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $instance = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$instance) {
            return null; 
        }

        return $this->createEntity($instance, $entityClass);
    }

    public function insert($entity)
    {
        $class = get_class($entity);
        $tableName = $class::getTable();

        $columns = array_keys(get_object_vars($entity));
        $values = array_map(function ($column) {
            return ":$column";
        }, $columns);

        $sql = "INSERT INTO `$tableName` (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")";
        $stmt = $this->connection->prepare($sql);

        foreach ($columns as $column) {
            $stmt->bindValue(":$column", $entity->$column);
        }

        try {
            $stmt->execute();
            $entity->id = $this->connection->lastInsertId();
        } catch (PDOException $e) {
            echo 'Error inserting entity: ' . $e->getMessage();
            exit;
        }
    }

    public function update($entity)
    {
        $class = get_class($entity);
        $tableName = $class::getTable();

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

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Error updating entity: ' . $e->getMessage();
            exit;
        }
    }

    public function delete($id,$entityClass)
    {
        $tableName = $entityClass::getTable();
        $sql = "DELETE FROM `$tableName` WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Error deleting entity: ' . $e->getMessage();
            exit;
        }
    }
}
