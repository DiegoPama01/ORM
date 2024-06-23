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

    /**
     * This PHP function retrieves all records from a database table using specified parameters.
     *
     * @return The `getAll` function is returning the result of calling the `getAll` method on the
     * `context` object with the parameters `->className`, `->table`, and `->columns`.
     */
    public function getAll()
    {
        return $this->context->getAll($this->className, $this->table, $this->columns);
    }

    /**
     * The getById function retrieves an entity by its ID after validating that the ID is a positive
     * integer.
     *
     * @param id The `id` parameter is the unique identifier used to retrieve a specific entity from
     * the database. It must be a positive integer greater than zero.
     *
     * @return Entity The function `getById` is returning an Entity object.
     */
    public function getById($id): Entity
    {
        if (!is_int($id) || $id <= 0) {
            throw new InvalidArgumentException('ID must be a positive integer');
        }
        return $this->context->getById($id, $this->className, $this->table, $this->columns);
    }

    /**
     * The insert function checks if the entity is of the expected type and then inserts it into the
     * database using the provided context, table, and columns.
     *
     * @param Entity entity The `entity` parameter is an object of type `Entity` that is being passed
     * to the `insert` method. The method checks if the entity is an object and if it is an instance of
     * the class specified by the `className` property of the current object. If the entity does not
     * meet
     */
    public function insert(Entity $entity)
    {
        if (!is_object($entity) || !($entity instanceof $this->className)) {
            throw new InvalidArgumentException('The entity must be from the expected type');
        }

        $this->context->insert($entity, $this->table, $this->columns);
    }

    /**
     * The update function in PHP checks if the entity is of the expected type and then updates it in
     * the context.
     *
     * @param Entity entity The `entity` parameter is an object of type `Entity` that is passed to the
     * `update` function. The function checks if the entity is an object and if it is an instance of
     * the class specified by the `className` property of the class. If the entity does not meet these
     * conditions
     */
    public function update(Entity $entity)
    {
        if (!is_object($entity) || !($entity instanceof $this->className)) {
            throw new InvalidArgumentException('The entity must be from the expected type');
        }
        $this->context->update($entity, $this->table, $this->columns);
    }

    /**
     * The code snippet contains PHP methods for deleting a record by ID, beginning a transaction,
     * committing a transaction, and rolling back a transaction.
     *
     * @param id The `delete` function takes an `` parameter which should be a positive integer. If
     * the provided `` is not a positive integer, an `InvalidArgumentException` is thrown.
     */
    public function delete($id)
    {
        if (!is_int($id) || $id <= 0) {
            throw new InvalidArgumentException('ID must be a positive integer');
        }

        $this->context->delete($id, $this->table, $this->columns);
    }

    /**
     * The beginTransaction function initiates a transaction in PHP.
     */
    public function beginTransaction()
    {
        $this->context->beginTransaction();
    }

    /**
     * The commit function is used to save changes made in the current context.
     */
    public function commit()
    {
        $this->context->commit();
    }

    /**
     * The above function in PHP rolls back a transaction in the context.
     */
    public function rollback()
    {
        $this->context->rollBack();
    }

    /**
     * This PHP function retrieves a referred entity based on a specified property name and class name.
     *
     * @param Entity entity The `entity` parameter in the `getReferredEntity` function is an instance of
     * the `Entity` class. It represents the entity from which you want to retrieve a referred entity.
     * @param propertyName The `` parameter in the `getReferredEntity` function represents the
     * name of the property in the `Entity` class that holds the reference to another entity. This property
     * is used to retrieve the ID of the referred entity which will be used to fetch the actual entity from
     * the database.
     * @param className The `className` parameter in the `getReferredEntity` function represents the class
     * name of the entity that you want to retrieve. It is used to specify the type of entity that you are
     * referring to when fetching data from the database.
     *
     * @return Entity The `getReferredEntity` function returns an Entity object. If the condition
     * `!isset(->columns[]->references)` is met, it returns `null`. Otherwise, it
     * retrieves annotations for the specified class, extracts table and columns information from the
     * annotations, and then uses the context to fetch an entity by its ID based on the provided
     * parameters.
     */
    public function getReferredEntity(Entity $entity, $propertyName, $className): Entity
    {
        if (!isset($this->columns[$propertyName]->references)) {
            return null;
        }
        $annotations = AnnotationManager::getClassAnnotations($className);

        $table = $annotations['table'];
        $columns = $annotations['columns'];

        return $this->context->getById($entity->$propertyName, $className, $table, $columns);
    }
}
