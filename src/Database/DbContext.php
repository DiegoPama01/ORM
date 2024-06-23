<?php

namespace ORM\Database;

use ORM\Models\Entity;
use ORM\Annotations\AnnotationManager;
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

    public function createEntity($data, $className, $columns)
    {
        $entity = new $className;

        $properties = $entity->getProperties();

        foreach ($properties as $property => $value) {
            $entity->$property = $data[$columns[$property]->name];
        }

        return $entity;
    }

    public function getAll($className, $table, $columns)
    {
        $tableName = $table->name;
        $sql = "SELECT * FROM `$tableName`";
        $stmt = $this->executeQuery($sql);
        $instances = [];

        while ($instance = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $entity = $this->createEntity($instance, $className, $columns);
            $instances[] = $entity;
        }

        return $instances;
    }

    public function getById($id, $className, $table, $columns)
    {
        $tableName = $table->name;
        $idColumnName = $columns['id']->name ?? 'id';

        $sql = "SELECT * FROM `$tableName` WHERE `$idColumnName` = :id";
        $stmt = $this->executeQuery($sql, [':id' => $id]);

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
            $columnNameSQL = $columns[$columnName]->name ?? $columnName;
            $columnNamesSQL[] = "`$columnNameSQL`";
            $placeholders[] = ":$columnName";
            $values[":$columnName"] = $entity->$columnName;
        }

        $sql = "INSERT INTO `$tableName` (" . implode(", ", $columnNamesSQL) . ") VALUES (" . implode(", ", $placeholders) . ")";
        $this->executeQuery($sql, $values);

        try {
            $entity->id = $this->connection->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException('Error inserting entity: ' . $e->getMessage());
        }
    }

    public function update(Entity $entity, $table, $columns)
    {
        $tableName = $table->name;
        $idColumnName = $columns['id']->name ?? 'id';

        $columnNames = array_keys($columns);
        $setClause = [];
        $values = [];

        foreach ($columnNames as $columnName) {
            $columnNameSQL = $columns[$columnName]->name ?? $columnName;
            $setClause[] = "`$columnNameSQL` = :$columnName";
            $values[":$columnName"] = $entity->$columnName;
        }

        $sql = "UPDATE `$tableName` SET " . implode(", ", $setClause) . " WHERE `$idColumnName` = :id";
        $values[':id'] = $entity->id;
        $this->executeQuery($sql, $values);
    }

    public function delete($id, $table, $columns)
    {
        $tableName = $table->name;
        $idColumnName = $columns['id']->name ?? 'id';
        $sql = "DELETE FROM `$tableName` WHERE `$idColumnName` = :id";
        $this->executeQuery($sql, [':id' => $id]);
    }

    private function executeQuery($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);

        foreach ($params as $param => $value) {
            $paramType = PDO::PARAM_STR;

            if (is_int($value)) {
                $paramType = PDO::PARAM_INT;
            } elseif (is_bool($value)) {
                $paramType = PDO::PARAM_BOOL;
            }

            $stmt->bindValue($param, $value, $paramType);
        }

        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            throw new PDOException('Error executing query: ' . $e->getMessage());
        }
    }
}
