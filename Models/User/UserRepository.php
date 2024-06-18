<?php
include_once "D:/Proyectos/ORM/Database/Repository.php";
include_once "User.php";
class UserRepository extends Repository
{
    public function __construct(DbContext $context)
    {
        parent::__construct($context, 'User', 'users');
    }

    public function getUsersByAge($age)
    {
        $stmt = $this->context->getConnection()->prepare("SELECT * FROM $this->tableName WHERE age = :age");
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->entityClass);
    }

    public function getUsersByNameLike($name)
    {
        $name = "%$name%";
        $stmt = $this->context->getConnection()->prepare("SELECT * FROM $this->tableName WHERE name LIKE :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->entityClass);
    }
}
