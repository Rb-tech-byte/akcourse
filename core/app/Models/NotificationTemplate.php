<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Table;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('notification_templates', key: 'id')]
#[Casts([
    'shortcodes' => 'object',
])]
class NotificationTemplate extends Model
{
    use HasModelAttributes;
}
