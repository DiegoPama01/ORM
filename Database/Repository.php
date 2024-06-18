<?php
class Repository
{
    protected $context;
    protected $entityClass;
    protected $tableName;

    public function __construct(DbContext $context, $entityClass, $tableName)
    {
        $this->context = $context;
        $this->entityClass = $entityClass;
        $this->tableName = $tableName;
    }

    public function getAll()
    {
        return $this->context->getAll($this->entityClass, $this->tableName);
    }

    public function getById($id)
    {
        if (!is_int($id) || $id <= 0) {
            throw new InvalidArgumentException('ID must be a positive integer');
        }
        return $this->context->getById($this->entityClass,$this->tableName, $id);
    }

    public function insert($entity)
    {
        if (!is_object($entity) || !($entity instanceof $this->entityClass)) {
            throw new InvalidArgumentException('The entity must be from the expected type');
        }
        $this->context->insert($this->tableName, $entity);
    }

    public function update($entity)
    {
        if (!is_object($entity) || !($entity instanceof $this->entityClass)) {
            throw new InvalidArgumentException('The entity must be from the expected type');
        }
        $this->context->update($this->tableName,$entity);
    }

    public function delete($id)
    {
        if (!is_int($id) || $id <= 0) {
            throw new InvalidArgumentException('ID must be a positive integer');
        }

        $this->context->delete($this->tableName, $id);
    }
}
