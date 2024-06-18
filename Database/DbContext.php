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

    public function getAll($entityClass, $tableName)
    {
        $sql = "SELECT * FROM `$tableName`";
        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, $entityClass);
    }

    public function getById($entityClass, $tableName, $id)
    {
        $sql = "SELECT * FROM `$tableName` WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $instance = $stmt->fetch(PDO::FETCH_ASSOC);

        //PDO to Entity conversion
        if ($instance) {
            $entity = new $entityClass();

            foreach ($instance as $column => $value) {
                if (property_exists($entity, $column)) {
                    $entity->$column = $value;
                }
            }
            return $entity;
        } else {
            return null;
        }
    }

    public function insert($tableName, $entity)
    {
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

    public function update($tableName, $entity)
    {
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
            echo 'Error while executing query' . $e->getMessage();
            exit;
        }
    }

    public function delete($tableName, $id)
    {
        $sql = "DELETE FROM `$tableName` WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
