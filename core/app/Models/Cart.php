<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model {
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function campaignProduct() {
        return $this->belongsTo(CampaignProduct::class, 'id', 'product_id')->where('status', Status::CAMPAIGN_PRODUCT_APPROVED);
    }

}
