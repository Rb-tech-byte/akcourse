<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Hidden;
use App\Attributes\Table;
use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('gateways', key: 'id')]
#[Hidden([
    'gateway_parameters',
    'extra'
])]
#[Casts([
    'code' => 'string',
    'extra' => 'object',
    'input_form' => 'object',
    'supported_currencies' => 'object'
])]
class Gateway extends Model
{
    use GlobalStatus, HasModelAttributes;

    public function currencies()
    {
        return $this->hasMany(GatewayCurrency::class, 'method_code', 'code');
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function singleCurrency()
    {
        return $this->hasOne(GatewayCurrency::class, 'method_code', 'code')->orderBy('id','desc');
    }

    public function scopeCrypto()
    {
        return $this->crypto == Status::ENABLE ? 'crypto' : 'fiat';
    }

    public function scopeAutomatic($query)
    {
        return $query->where('code', '<', 1000);
    }

    public function scopeManual($query)
    {
        return $query->where('code', '>=', 1000);
    }

    /**
     * Get associated composer package dependencies for this gateway
     */
    public function getPackages()
    {
        $alias = strtolower($this->alias ?? '');
        $known = [
            'authorize'     => ['authorizenet/authorizenet'],
            'btcpay'        => ['btcpayserver/btcpayserver-greenfield-php'],
            'coingate'      => ['coingate/coingate-php'],
            'mollie'        => ['mollie/laravel-mollie'],
            'razorpay'      => ['razorpay/razorpay'],
            'stripe'        => ['stripe/stripe-php'],
            'stripejs'      => ['stripe/stripe-php'],
            'stripev3'      => ['stripe/stripe-php'],
        ];

        $pkgs = $known[$alias] ?? [];
        if (!empty($this->extra->packages)) {
            $extraPkgs = is_array($this->extra->packages) ? $this->extra->packages : explode(',', $this->extra->packages);
            $pkgs = array_unique(array_merge($pkgs, array_filter(array_map('trim', $extraPkgs))));
        }
        return array_values($pkgs);
    }

    /**
     * Get associated custom database tables for this gateway
     */
    public function getCustomTables()
    {
        if (!empty($this->extra->custom_tables)) {
            $tables = is_array($this->extra->custom_tables) ? $this->extra->custom_tables : explode(',', $this->extra->custom_tables);
            return array_values(array_filter(array_map('trim', $tables)));
        }
        return [];
    }

    /**
     * Get controller directory path for this gateway
     */
    public function getControllerDir()
    {
        $studly = \Illuminate\Support\Str::studly($this->alias);
        return app_path("Http/Controllers/Gateway/{$studly}");
    }

    /**
     * Get dedicated payment view path if it exists
     */
    public function getViewFile()
    {
        $studly = \Illuminate\Support\Str::studly($this->alias);
        return resource_path("views/templates/basic/user/payment/{$studly}.blade.php");
    }
}
