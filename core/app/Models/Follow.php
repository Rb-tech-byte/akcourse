<?php

namespace App\Models;

use App\Attributes\Fillable;
use App\Attributes\Table;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('follows', key: 'id')]
#[Fillable([
    'user_id',
    'follower_id'
])]
class Follow extends Model
{
    use HasModelAttributes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }
}
