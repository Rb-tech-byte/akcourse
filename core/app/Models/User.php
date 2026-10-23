<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Hidden;
use App\Attributes\Table;
use App\Constants\Status;
use App\Traits\HasModelAttributes;
use App\Traits\UserNotify;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

#[Table('users', key: 'id')]
#[Hidden([
    'password',
    'remember_token',
    'ver_code',
    'balance',
    'kyc_data',
    'author_info'
])]
#[Casts([
    'email_verified_at' => 'datetime',
    'kyc_data' => 'object',
    'author_info' => 'object',
    'ver_code_send_at' => 'datetime',
    'email_settings' => 'object',
    'social_media_settings' => 'object',
])]
class User extends Authenticatable
{
    use HasApiTokens, UserNotify, HasModelAttributes;


    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id', 'desc');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'author_id');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function withdrawals()
    {
        return $this->hasMany(User::class, 'id', 'id')->whereRaw('1 = 0');
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'user_follows', 'follower_id', 'following_id');
    }

    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function submittedRefundRequests()
    {
        return $this->hasMany(RefundRequest::class, 'buyer_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function soldItems()
    {
        return $this->hasManyThrough(OrderItem::class, Product::class);
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn() => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function mobileNumber(): Attribute
    {
        return new Attribute(
            get: fn() => $this->dial_code . $this->mobile,
        );
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', Status::USER_ACTIVE)->where('ev', Status::VERIFIED)->where('sv', Status::VERIFIED);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', Status::USER_BAN);
    }

    public function scopeEmailUnverified($query)
    {
        return $query->where('ev', Status::UNVERIFIED);
    }

    public function scopeMobileUnverified($query)
    {
        return $query->where('sv', Status::UNVERIFIED);
    }

    public function scopeKycUnverified($query)
    {
        return $query->where('kv', Status::KYC_UNVERIFIED);
    }

    public function scopeKycPending($query)
    {
        return $query->where('kv', Status::KYC_PENDING);
    }

    public function scopeEmailVerified($query)
    {
        return $query->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified($query)
    {
        return $query->where('sv', Status::VERIFIED);
    }

    public function scopeWithBalance($query)
    {
        return $query->where('balance', '>', 0);
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function purchasedItems()
    {
        return $this->hasMany(OrderItem::class)->with('product');
    }

    public function downloadItems()
    {
        return $this->hasMany(Download::class, 'user_id');
    }

    public function follows()
    {
        return $this->hasMany(User::class, 'id', 'id')->whereRaw('1 = 0');
    }

    public function followers()
    {
        return $this->hasMany(User::class, 'id', 'id')->whereRaw('1 = 0');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function authorLevels()
    {
        return $this->hasMany(User::class, 'id', 'id')->whereRaw('1 = 0');
    }
    public function currentAuthorLevel()
    {
        return $this->hasMany(User::class, 'id', 'id')->whereRaw('1 = 0');
    }

    public function collections()
    {
        return $this->hasMany(User::class, 'id', 'id')->whereRaw('1 = 0');
    }

    public function comments()
    {
        return $this->hasManyThrough(Comment::class, Product::class);
    }

    public function scopeAuthor($query)
    {
        return $query->where('id', 0);
    }

    public function isAuthor()
    {
        return false;
    }

    public function apiKey()
    {
        return $this->hasOne(ApiKey::class)->where('status', Status::ACTIVE_KEY);
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'ref_by');
    }

    public function featureBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->is_author_featured == Status::NO) {
                $html = '<span class="badge badge--info">' . trans('No') . '</span>';
            } elseif ($this->is_author_featured == Status::YES) {
                $html = '<span class="badge badge--success">' . trans('Yes') . '</span>';
            }
            return $html;
        });
    }
}
