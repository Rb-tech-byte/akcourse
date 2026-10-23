<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Guarded
{
    /**
     * Create a new Guarded attribute instance.
     *
     * @param array<int, string> $guarded Attributes guarded against mass-assignment
     */
    public function __construct(
        public array $guarded = []
    ) {}
}
