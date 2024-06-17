<?php
class Repository
{
    protected $context;
    private $entityClass;

    public function __construct($context, $entityClass)
    {
        $this->context = $context;
        $this->entityClass = $entityClass;
    }

    public function getAll()
    {
        return $this->context->getAll($this->entityClass);
    }

    public function getById($id)
    {
        return $this->context->getById($this->entityClass, $id);
    }

    public function insert($entity)
    {
        $this->context->insert($entity);
    }

    public function update($entity)
    {
        $this->context->update($entity);
    }

    public function delete($id)
    {
        $this->context->delete($this->entityClass, $id);
    }
}
