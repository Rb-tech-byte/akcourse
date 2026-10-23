<?php

namespace App\Models;

use App\Attributes\Fillable;
use App\Attributes\Table;
use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('coupons', key: 'id')]
#[Fillable([
    'name',
    'code',
    'discount_type',
    'amount',
    'description',
    'minimum_spend',
    'usage_limit_per_coupon',
    'usage_limit_per_user',
    'status',
    'start_date',
    'end_date',
])]
class Coupon extends Model
{
    use GlobalStatus, HasModelAttributes;

    protected $guarded = ['id'];

    public function isValid()
    {
        return $this->status == 1; 
    }

    public function getCouponTypeAttribute()
    {
        if ($this->discount_type == 1) {
            return 'Fixed';
        } else {
            return 'Percentage';
        }
    }
}
