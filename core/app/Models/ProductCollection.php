<?php

namespace App\Models;

use App\Attributes\Table;
use App\Constants\Status;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('users', key: 'id')]
class ProductCollection extends Model
{
    use HasModelAttributes;

    public function newQuery()
    {
        return parent::newQuery()->whereRaw('1 = 0');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product');
    }

    public function scopePublic($query) {
        return $query->where('is_public', Status::YES);
    }
}
