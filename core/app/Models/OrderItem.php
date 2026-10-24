<?php

namespace App\Models;

use App\Attributes\Guarded;
use App\Attributes\Table;
use App\Constants\Status;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

#[Table('order_items', key: 'id')]
#[Guarded(['id'])]
class OrderItem extends Model {
    use HasModelAttributes;

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function scopePaid($query) {
        return $query->whereHas('order', function ($query) {
            $query->where('payment_status', 1);
        });
    }

    public function scopeRefunded($query) {
        return $query->where('is_refunded', Status::YES);
    }

    public function buyer() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function licenseBadge(): Attribute {
        return new Attribute(function () {
            return '<span class="badge badge--success"><i class="las la-certificate me-1"></i>' . trans('Lifetime License') . '</span>';
        });
    }

    public function getLicenseKeyAttribute($value) {
        if (!empty($value)) {
            return $value;
        }
        $key = generateLicenseKey();
        $this->attributes['license_key'] = $key;
        if ($this->exists) {
            $this->saveQuietly();
        }
        return $key;
    }

    public function getCertificateNumberAttribute($value) {
        if (!empty($value)) {
            return $value;
        }
        $cert = generateCertificateNumber();
        $this->attributes['certificate_number'] = $cert;
        if ($this->exists) {
            $this->saveQuietly();
        }
        return $cert;
    }

    public function getCertificateUrlAttribute() {
        return route('user.certificate', $this->certificate_number ?: $this->purchase_code);
    }

    public function refundedBadge(): Attribute {
        return new Attribute(function () {
            $html = '';
            if ($this->is_refunded == Status::YES) {
                $html = '<span class="badge badge--danger">' . trans('Yes') . '</span>';
            } else {
                $html = '<span class="badge badge--success">' . trans('No') . '</span>';
            }
            return $html;
        });
    }

}
