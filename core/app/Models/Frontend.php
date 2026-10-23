<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Table;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('frontends', key: 'id')]
#[Casts([
    'data_values' => 'object',
    'seo_content' => 'object',
])]
class Frontend extends Model
{
    use HasModelAttributes;

    public static function scopeGetContent($data_keys)
    {
        return Frontend::where('data_keys', $data_keys);
    }
}
