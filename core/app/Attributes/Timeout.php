<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Timeout
{
    /**
     * Create a new Timeout attribute instance.
     *
     * @param int $timeout Job timeout in seconds
     */
    public function __construct(
        public int $timeout
    ) {}
}
