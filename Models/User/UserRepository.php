<?php

class UserRepository extends Repository {
    public function __construct($context) {
        parent::__construct($context, 'User');
    }

    public function getUsersByAge($age) {
        $stmt = $this->context->connection->prepare("SELECT * FROM users WHERE age = :age");
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    }

    public function getUsersByNameLike($name) {
        $name = "%$name%";
        $stmt = $this->context->connection->prepare("SELECT * FROM users WHERE name LIKE :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    }
}