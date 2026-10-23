<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Connection
{
    /**
     * Create a new Connection attribute instance.
     *
     * @param string $connection Queue connection name
     */
    public function __construct(
        public string $connection
    ) {}
}
