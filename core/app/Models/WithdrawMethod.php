<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Table;
use App\Traits\GlobalStatus;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('withdraw_methods', key: 'id')]
#[Casts([
    'user_data' => 'object',
])]
class WithdrawMethod extends Model
{
    use GlobalStatus, HasModelAttributes;

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
