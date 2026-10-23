<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Hidden;
use App\Attributes\Table;
use App\Constants\Status;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

#[Table('users', key: 'id')]
#[Hidden(['withdraw_information'])]
#[Casts(['withdraw_information' => 'object'])]
class Withdrawal extends Model
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

    public function scopeSuccess($query)
    {
        return $query->where('status', Status::PAYMENT_SUCCESS);
    }

    public function method()
    {
        return $this->belongsTo(WithdrawMethod::class, 'method_id');
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function(){
            $html = '';
            if($this->status == Status::PAYMENT_PENDING){
                $html = '<span class="badge badge--warning">'.trans('Pending').'</span>';
            }elseif($this->status == Status::PAYMENT_SUCCESS){
                $html = '<span><span class="badge badge--success">'.trans('Approved').'</span><br>'.diffForHumans($this->updated_at).'</span>';
            }elseif($this->status == Status::PAYMENT_REJECT){
                $html = '<span><span class="badge badge--danger">'.trans('Rejected').'</span><br>'.diffForHumans($this->updated_at).'</span>';
            }
            return $html;
        });
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::PAYMENT_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', Status::PAYMENT_SUCCESS);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', Status::PAYMENT_REJECT);
    }
}
