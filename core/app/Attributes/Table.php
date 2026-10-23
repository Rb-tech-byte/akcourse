<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Table
{
    /**
     * Create a new Table attribute instance.
     *
     * @param string $name Database table name
     * @param string|null $key Primary key column name
     */
    public function __construct(
        public string $name,
        public ?string $key = null
    ) {}
}
