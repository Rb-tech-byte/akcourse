<?php

namespace App\Models;

use App\Attributes\Hidden;
use App\Attributes\Table;
use App\Traits\HasModelAttributes;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Table('admins', key: 'id')]
#[Hidden([
    'password',
    'remember_token',
])]
class Admin extends Authenticatable
{
    use HasModelAttributes;
}
