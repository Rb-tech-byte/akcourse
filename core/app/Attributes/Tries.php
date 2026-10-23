<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Tries
{
    /**
     * Create a new Tries attribute instance.
     *
     * @param int $tries Maximum attempt count
     */
    public function __construct(
        public int $tries
    ) {}
}
