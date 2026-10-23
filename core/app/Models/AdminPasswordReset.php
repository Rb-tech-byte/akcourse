<?php

namespace App\Models;

use App\Attributes\Guarded;
use App\Attributes\Table;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('admin_password_resets', key: 'id')]
#[Guarded(['id'])]
class AdminPasswordReset extends Model
{
    use HasModelAttributes;

    public $timestamps = false;
}
