<?php

namespace App\Models;

use App\Attributes\Fillable;
use App\Attributes\Table;
use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('campaigns', key: 'id')]
#[Fillable([
    'name',
    'discount_min',
    'discount_max',
    'start_date',
    'end_date',
])]
class Campaign extends Model {
    use GlobalStatus, HasModelAttributes;

    public function products() {
        return $this->belongsToMany(Product::class);
    }

    public function campaignsProducts() {
        return $this->belongsToMany(CampaignProduct::class);
    }
    
    public function campaignsItem() {
        return $this->hasMany(CampaignProduct::class, 'campaign_id');
    }

    public function scopeActive($query) {
        return $query->where('status', Status::CAMPAIGN_ACTIVE);
    }

    public function scopePending($query) {
        return $query->where('status', Status::CAMPAIGN_DISABLED);
    }

    public function scopeExpired($query) {
        return $query->where('status', Status::CAMPAIGN_EXPIRED);
    }
}
