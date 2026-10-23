<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Hidden;
use App\Attributes\Table;
use App\Constants\Status;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('gateway_currencies', key: 'id')]
#[Hidden([
    'gateway_parameter'
])]
#[Casts([
    'status' => 'boolean'
])]
class GatewayCurrency extends Model
{
    use HasModelAttributes;

    // Relation
    public function method()
    {
        return $this->belongsTo(Gateway::class, 'method_code', 'code');
    }

    public function currencyIdentifier()
    {
        return $this->name ?? $this->method->name . ' ' . $this->currency;
    }

    public function scopeBaseCurrency()
    {
        return $this->method->crypto == Status::ENABLE ? 'USD' : $this->currency;
    }

    public function scopeBaseSymbol()
    {
        return $this->method->crypto == Status::ENABLE ? '$' : $this->symbol;
    }
}
