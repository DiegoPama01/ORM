<?php

use Random\Engine;

class Repository
{
    protected $context;
    protected $entityClass;

    /**
     * #TODO Añadir una propiedad tableName y un array asociativo con las propiedades. 
     * Así la DBContext solo necesita utilizar los datos que le pasa el repository y no tiene que crear objetos cada vez que es utilizada.
     */

    //tableName???
    //PropertyMapping???

    public function __construct(DbContext $context, $entityClass)
    {
        $this->context = $context;
        $this->entityClass = $entityClass;
    }

    public function getAll($entityClass = null)
    {
        if ($entityClass == null) $entityClass = $this->entityClass;

        return $this->context->getAll($this->entityClass);
    }

    public function getById($id, $entityClass = null)
    {
        if ($entityClass == null) $entityClass = $this->entityClass;

        if (!is_int($id) || $id <= 0) {
            throw new InvalidArgumentException('ID must be a positive integer');
        }
        return $this->context->getById($entityClass, $id);
    }

    public function insert(Entity $entity)
    {
        if (!is_object($entity) || !($entity instanceof $this->entityClass)) {
            throw new InvalidArgumentException('The entity must be from the expected type');
        }
        $this->context->insert($entity);
    }

    public function update(Entity $entity)
    {
        if (!is_object($entity) || !($entity instanceof $this->entityClass)) {
            throw new InvalidArgumentException('The entity must be from the expected type');
        }
        $this->context->update($entity);
    }

    public function delete($id, $entityClass = null)
    {
        if ($entityClass == null) $entityClass = $this->entityClass;

        if (!is_int($id) || $id <= 0) {
            throw new InvalidArgumentException('ID must be a positive integer');
        }

        $this->context->delete($id, $entityClass);
    }
}
