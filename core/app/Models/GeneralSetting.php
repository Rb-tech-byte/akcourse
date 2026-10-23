<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Hidden;
use App\Attributes\Table;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('general_settings', key: 'id')]
#[Hidden([
    'email_template',
    'mail_config',
    'sms_config',
    'system_info'
])]
#[Casts([
    'mail_config'                 => 'object',
    'sms_config'                  => 'object',
    'global_shortcodes'           => 'object',
    'socialite_credentials'       => 'object',
    'firebase_config'             => 'object',
    'personal_license_features'   => 'array',
    'commercial_license_features' => 'array',
    'ftp'                         => 'object',
    'wasabi'                      => 'object',
    'digital_ocean'               => 'object',
    'backblaze'                   => 'object',
    'config_progress'             => 'object',
])]
class GeneralSetting extends Model {
    use HasModelAttributes;

    public function scopeSiteName($query, $pageTitle) {
        $pageTitle = empty($pageTitle) ? '' : ' - ' . $pageTitle;
        return $this->site_name . $pageTitle;
    }

    protected static function boot() {
        parent::boot();
        static::saved(function () {
            \Cache::forget('GeneralSetting');
        });
    }
}
