<?php
namespace ORM\Models\User;

use ORM\Database\DbContext;
use ORM\Models\Repository;
use PDO;

class UserRepository extends Repository
{

    public function __construct(DbContext $context)
    {
        parent::__construct($context, User::class);
    }

    public function getUsersByAge($age)
    {
        $stmt = $this->context->getConnection()->prepare("SELECT * FROM $this->table->name WHERE age = :age");
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->className);
    }

    public function getUsersByNameLike($name)
    {
        $name = "%$name%";
        $stmt = $this->context->getConnection()->prepare("SELECT * FROM $this->table->name WHERE name LIKE :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->className);
    }
}
