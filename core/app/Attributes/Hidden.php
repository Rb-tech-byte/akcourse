<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Hidden
{
    /**
     * Create a new Hidden attribute instance.
     *
     * @param array<int, string> $hidden Attributes hidden from serialization
     */
    public function __construct(
        public array $hidden = []
    ) {}
}
