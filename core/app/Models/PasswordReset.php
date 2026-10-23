<?php

namespace App\Models;

use App\Attributes\Hidden;
use App\Attributes\Table;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('password_resets')]
#[Hidden([
    'token'
])]
class PasswordReset extends Model
{
    use HasModelAttributes;

    public $timestamps = false;
}
