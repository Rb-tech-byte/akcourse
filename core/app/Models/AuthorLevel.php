<?php

namespace App\Models;

use App\Attributes\Table;
use App\Traits\GlobalStatus;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('users', key: 'id')]
class AuthorLevel extends Model
{
    use GlobalStatus, HasModelAttributes;

    public function newQuery()
    {
        return parent::newQuery()->whereRaw('1 = 0');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
