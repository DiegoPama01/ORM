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

        $annotations = AnnotationManager::getClassAnnotations($entityClass);
        $propertyAnnotations = $annotations['columns'];

        foreach ($data as $column => $value) {
            if (property_exists($entity, $column)) {
                $entity->$column = $value;
            } elseif (isset($propertyAnnotations[$column])) {
                $columnInfo = $propertyAnnotations[$column];
                if (isset($columnInfo['name'])) {
                    $propertyName = $columnInfo['name'];
                    $entity->$propertyName = $value;
                }
            }
        }

        return $entity;
    }

    public function getAll($entityClass)
    {
        $annotations = AnnotationManager::getClassAnnotations($entityClass);
        $tableName = $annotations['table'];
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
        $annotations = AnnotationManager::getClassAnnotations($entityClass);
        $tableName = $annotations['table'];

        $idColumnName = isset($annotations['columns']['id']['name']) ? $annotations['columns']['id']['name'] : 'id';

        $sql = "SELECT * FROM `$tableName` WHERE `$idColumnName` = :id";
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
        $annotations = AnnotationManager::getClassAnnotations($class);
        $tableName = $annotations['table'];
        $columnsAnnotations = $annotations['columns'];

        $columns = array_keys($columnsAnnotations);

        $columnNames = [];
        $placeholders = [];
        $values = [];

        foreach ($columns as $column) {
            $columnAnnotations = $columnsAnnotations[$column];

            $columnName = $columnAnnotations['name'] ?? $column;
            $columnNames[] = "`$columnName`";
            $placeholders[] = ":$column";
            $values[":$column"] = $entity->$column;
        }

        $sql = "INSERT INTO `$tableName` (" . implode(", ", $columnNames) . ") VALUES (" . implode(", ", $placeholders) . ")";
        $stmt = $this->connection->prepare($sql);



        foreach ($values as $param => $value) {
            $paramType = PDO::PARAM_STR;

            if (isset($propertyAnnotations[substr($param, 1)]['type'])) {
                switch ($propertyAnnotations[substr($param, 1)]['type']) {
                    case 'integer':
                        $paramType = PDO::PARAM_INT;
                        break;
                    case 'boolean':
                        $paramType = PDO::PARAM_BOOL;
                        break;
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

    public function update($entity)
    {
        $class = get_class($entity);
        $annotations = AnnotationManager::getClassAnnotations($class);
        $tableName = $annotations['table'];

        $columnsAnnotations = $annotations['columns'];

        $setClause = [];
        $values = [];

        foreach ($columnsAnnotations as $propertyName => $columnAnnotations) {
            $columnName = $columnAnnotations['name'] ?? $propertyName;
            $setClause[] = "`$columnName` = :$propertyName";
            $values[":$propertyName"] = $entity->$propertyName;
        }

        $idColumn = isset($columnsAnnotations['id']['name']) ? $columnsAnnotations['id']['name'] : 'id';

        $sql = "UPDATE `$tableName` SET " . implode(", ", $setClause) . " WHERE $idColumn = :id";
        $stmt = $this->connection->prepare($sql);

        foreach ($values as $placeholder => $value) {
            if (is_bool($value)) {
                $stmt->bindValue($placeholder, $value, PDO::PARAM_BOOL);
            } else {
                $stmt->bindValue($placeholder, $value);
            }
        }

        $stmt->bindValue(':id', $entity->id, PDO::PARAM_INT);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Error updating entity: ' . $e->getMessage();
            exit;
        }
    }

    public function delete($id, $entityClass)
    {
        $annotations = AnnotationManager::getClassAnnotations($entityClass);
        $tableName = $annotations['table'];

        $idColumnName = isset($annotations['columns']['id']['name']) ? $annotations['columns']['id']['name'] : 'id';

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
