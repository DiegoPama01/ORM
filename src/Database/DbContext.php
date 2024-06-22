<?php

namespace ORM\Database;

use ORM\Models\Entity;
use PDO;
use PDOException;

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

    public function createEntity($data, $entityClass, $columns)
    {
        $entity = new $entityClass;

        foreach ($data as $column => $value) {
            if (property_exists($entity, $column)) {
                $entity->$column = $value;
            } elseif (isset($columns[$column])) {
                $columnInfo = $columns[$column];
                if (isset($columnInfo->name)) {
                    $propertyName = $columnInfo->name;
                    $entity->$propertyName = $value;
                }
            }
        }

        return $entity;
    }

    public function getAll($className, $table, $columns)
    {
        $tableName = $table->name;
        $sql = "SELECT * FROM `$tableName`";
        $stmt = $this->connection->query($sql);
        $stmt->execute();
        $instances = [];
        while ($instance = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $instances[] = $this->createEntity($instance, $className, $columns);
        }
        return $instances;
    }

    public function getById($id, $className, $table, $columns)
    {
        $tableName = $table->name;

        $idColumnName = $columns['id']->name ?? 'id';

        $sql = "SELECT * FROM `$tableName` WHERE `$idColumnName` = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $instance = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$instance) {
            return null;
        }

        return $this->createEntity($instance, $className, $columns);
    }

    public function insert(Entity $entity, $table, $columns)
    {
        $tableName = $table->name;
        $columnNames = array_keys($columns);
        $columnNamesSQL = [];
        $placeholders = [];
        $values = [];

        foreach ($columnNames as $columnName) {
            $column = $columns[$columnName];
            $columnNameSQL = $column->name ?? $column;
            $columnNamesSQL[] = "`$columnNameSQL`";
            $placeholders[] = ":$columnName";
            $values[":$columnName"] = $entity->$columnName;
        }

        $sql = "INSERT INTO `$tableName` (" . implode(", ", $columnNamesSQL) . ") VALUES (" . implode(", ", $placeholders) . ")";
        $stmt = $this->connection->prepare($sql);

        foreach ($values as $param => $value) {
            if (isset($columns[substr($param, 1)]->type)) {
                switch ($columns[substr($param, 1)]->type) {
                    case 'integer':
                        $paramType = PDO::PARAM_INT;
                        break;
                    case 'boolean':
                        $paramType = PDO::PARAM_BOOL;
                        break;
                    default:
                        $paramType = PDO::PARAM_STR;
                }
            }

            $stmt->bindValue($param, $value, $paramType);
        }

        try {
            $stmt->execute();
            $entity->id = $this->connection->lastInsertId();
        } catch (PDOException $e) {
            echo 'Error inserting entity: ' . $e->getMessage();
            exit;
        }
    }

    public function update(Entity $entity, $table, $columns)
    {
        $tableName = $table->name;
        $columnNames = array_keys($columns);
        $setClause = [];
        $values = [];

        foreach ($columnNames as $columnName) {
            $column = $columns[$columnName];
            $columnNameSQL = $column->name ?? $column;
            $setClause[] = "`$columnNameSQL` = :$columnName";
            $values[":$columnName"] = $entity->$columnName;
        }

        $idColumnName = $columns['id']->name ?? 'id';

        $sql = "UPDATE `$tableName` SET " . implode(", ", $setClause) . " WHERE `$idColumnName` = :id";
        $stmt = $this->connection->prepare($sql);

        foreach ($values as $placeholder => $value) {
            $paramType = PDO::PARAM_STR;

            // Determine the parameter type based on column annotations
            if ($columns[substr($placeholder, 1)]->type === 'integer') {
                $paramType = PDO::PARAM_INT;
            } elseif ($columns[substr($placeholder, 1)]->type === 'boolean') {
                $paramType = PDO::PARAM_BOOL;
            }

            $stmt->bindValue($placeholder, $value, $paramType);
        }

        $stmt->bindValue(':id', $entity->id, PDO::PARAM_INT);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Error updating entity: ' . $e->getMessage();
            exit;
        }
    }

    public function delete($id, $table, $columns)
    {
        $tableName = $table->name;

        $idColumnName = $columns['id']->name ?? 'id';

        $sql = "DELETE FROM `$tableName` WHERE `$idColumnName` = :id";
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
