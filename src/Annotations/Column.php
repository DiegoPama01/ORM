<?php

namespace ORM\Annotations;

/**
 * @Annotation
 * @Target("COLUMNS")
 */
class Column
{
    public $type;
    public $name;
    public $references;
    public bool $isPrimaryKey;

    public function __construct(array $values)
    {
        $this->type = $values['type'] ?? "string";
        $this->name = $values['name'] ?? null;
        $this->references = $values['references'] ?? null;
        $this->isPrimaryKey = $values['isPrimaryKey'] ?? false;
    }
}
