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
        $stmt = $this->executeQuery($sql);
        $instances = [];

        while ($instance = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $entity = $this->createEntity($instance, $className, $columns);
            $this->resolveReferences($entity, $columns);
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

        $entity = $this->createEntity($instance, $className, $columns);
        $this->resolveReferences($entity, $columns);

        return $entity;
    }

    public function insert(Entity $entity, $table, $columns)
    {
        $tableName = $table->name;
        $parent = $entity->getParent();

        if ($parent !== null) {
            $annotations = AnnotationManager::getClassAnnotations(get_class($parent));
            $parentColumns = array_keys($annotations['columns']);

            if ($annotations['table']->name !== null) {
                $this->insert($parent, $annotations['table'], $annotations['columns']);
                $columns = array_diff_key($columns, array_flip(array_diff($parentColumns, ['id'])));
            }
        }

        $columnNames = array_keys($columns);
        $columnNamesSQL = [];
        $placeholders = [];
        $values = [];

        foreach ($columnNames as $columnName) {
            $column = $columns[$columnName] ?? null;
            if ($column !== null && isset($column->references)) {
                preg_match('/(\w+)\((\w+)\)/', $column->references, $matches);
                $refProperty = $matches[2];
                $entity->$columnName = $parent->$refProperty;
            }

            $columnNameSQL = $column->name ?? $columnName;
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

        $parent = $entity->getParent();
        if ($parent !== null) {
            $annotations = AnnotationManager::getClassAnnotations(get_class($parent));
            $parentColumns = array_keys($annotations['columns']);

            foreach ($columns as $columnName => $column) {
                if (isset($column->references)) {
                    preg_match('/(\w+)\((\w+)\)/', $column->references, $matches);
                    $refProperty = $matches[2];
                    $parent->$refProperty = $entity->$columnName;
                }
            }

            if ($annotations['table']->name !== null) {
                $this->update($parent, $annotations['table'], $annotations['columns']);
                $columns = array_diff_key($columns, array_flip(array_diff($parentColumns, ['id'])));
            }
        }

        $columnNames = array_keys($columns);
        $setClause = [];
        $values = [];

        foreach ($columnNames as $columnName) {
            $column = $columns[$columnName];
            $columnNameSQL = $column->name ?? $columnName;
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

    private function resolveReferences($entity, $columns)
    {
        foreach ($columns as $columnName => $column) {
            if (!isset($column->references)) {
                continue;
            }
            $annotations = AnnotationManager::getClassAnnotations(get_parent_class($entity));
            $parentTable = $annotations['table'];
            $parentColumns = $annotations['columns'];
            $relatedEntity = $this->getById($entity->$columnName, get_parent_class($entity), $parentTable, $parentColumns);

            if ($relatedEntity === null) {
                continue;
            }

            $properties = $relatedEntity->getProperties();

            foreach ($properties as $property => $value) {
                if (property_exists($entity, $property) && !isset($entity->$property)) {
                    $entity->$property = $value;
                }
            }
        }
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
