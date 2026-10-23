<?php

namespace App\Models;

use App\Attributes\Guarded;
use App\Attributes\Table;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Table('changelogs', key: 'id')]
#[Guarded([])]
class Changelog extends Model
{
    use HasFactory, HasModelAttributes;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
