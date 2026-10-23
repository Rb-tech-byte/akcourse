<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Fillable
{
    /**
     * Create a new Fillable attribute instance.
     *
     * @param array<int, string> $fillable Mass-assignable attributes
     */
    public function __construct(
        public array $fillable = []
    ) {}
}
