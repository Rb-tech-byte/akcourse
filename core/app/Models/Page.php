<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Table;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('pages', key: 'id')]
#[Casts([
    'seo_content' => 'object',
])]
class Page extends Model
{
    use HasModelAttributes;
}
