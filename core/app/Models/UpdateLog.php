<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Table;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('update_logs', key: 'id')]
#[Casts([
    'update_log' => 'object',
])]
class UpdateLog extends Model
{
    use HasModelAttributes;
}
