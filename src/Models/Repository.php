<?php

namespace ORM\Models;

use ORM\Database\DbContext;
use ORM\Annotations\AnnotationManager;
use InvalidArgumentException;

class Repository
{
    protected $context;
    protected $className;
    protected $table;
    protected $columns;


    public function __construct(DbContext $context, $className)
    {
        $this->context = $context;
        $this->className = $className;

        $annotations = AnnotationManager::getClassAnnotations($className);

        $this->table = $annotations["table"] ?? [];
        $this->columns = $annotations["columns"] ?? [];
    }

    public function getAll($className = null)
    {
        if ($className == null) {
            $className = $this->className;
        } else {
            $annotations = AnnotationManager::getClassAnnotations($className);

            $this->table = $annotations["table"] ?? [];
            $this->columns = $annotations["columns"] ?? [];
        }

        return $this->context->getAll($className, $this->table, $this->columns);
    }

    public function getById($id, $className = null)
    {
        if ($className == null) {
            $className = $this->className;
        } else {
            $annotations = AnnotationManager::getClassAnnotations($className);

            $this->table = $annotations["table"] ?? [];
            $this->columns = $annotations["columns"] ?? [];
        }

        if (!is_int($id) || $id <= 0) {
            throw new InvalidArgumentException('ID must be a positive integer');
        }
        return $this->context->getById($id, $className, $this->table, $this->columns);
    }

    public function insert(Entity $entity)
    {
        if (!is_object($entity) || !($entity instanceof $this->className)) {
            throw new InvalidArgumentException('The entity must be from the expected type');
        }
        $annotations = AnnotationManager::getClassAnnotations(get_class($entity));

        $tableName = $annotations['table'];
        $columns = $annotations['columns'];

        $this->context->insert($entity, $tableName, $columns);
    }


    public function update(Entity $entity)
    {
        if (!is_object($entity) || !($entity instanceof $this->className)) {
            throw new InvalidArgumentException('The entity must be from the expected type');
        }
        $annotations = AnnotationManager::getClassAnnotations(get_class($entity));

        $tableName = $annotations['table'];
        $columns = $annotations['columns'];

        $this->context->update($entity, $tableName, $columns);
    }

    public function delete($id, $className = null)
    {
        if ($className == null) {
            $className = $this->className;
        } else {
            $annotations = AnnotationManager::getClassAnnotations($className);

            $this->table = $annotations["table"] ?? [];
            $this->columns = $annotations["columns"] ?? [];
        }

        if (!is_int($id) || $id <= 0) {
            throw new InvalidArgumentException('ID must be a positive integer');
        }

        $this->context->delete($id, $this->table, $this->columns);
    }
}
