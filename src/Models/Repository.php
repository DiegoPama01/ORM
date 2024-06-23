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

    public function getAll()
    {
        return $this->context->getAll($this->className, $this->table, $this->columns);
    }

    public function getById($id)
    {
        if (!is_int($id) || $id <= 0) {
            throw new InvalidArgumentException('ID must be a positive integer');
        }
        return $this->context->getById($id, $this->className, $this->table, $this->columns);
    }

    public function insert(Entity $entity)
    {
        if (!is_object($entity) || !($entity instanceof $this->className)) {
            throw new InvalidArgumentException('The entity must be from the expected type');
        }

        $this->context->insert($entity, $this->table, $this->columns);
    }

    public function update(Entity $entity)
    {
        if (!is_object($entity) || !($entity instanceof $this->className)) {
            throw new InvalidArgumentException('The entity must be from the expected type');
        }
        $this->context->update($entity, $this->table, $this->columns);
    }

    public function delete($id)
    {
        if (!is_int($id) || $id <= 0) {
            throw new InvalidArgumentException('ID must be a positive integer');
        }

        $this->context->delete($id, $this->table, $this->columns);
    }

    public function beginTransaction()
    {
        $this->context->beginTransaction();
    }

    public function commit()
    {
        $this->context->commit();
    }

    public function rollback()
    {
        $this->context->rollBack();
    }
}
