<?php

namespace ORM\Annotations;

/**
 * @Annotation
 * @Target("TABLE")
 */
class Table
{
    public $name;

    public function __construct(array $values)
    {
        $this->name = $values['name'] ?? null;
    }
}
