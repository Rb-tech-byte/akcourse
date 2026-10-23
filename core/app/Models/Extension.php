<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Hidden;
use App\Attributes\Table;
use App\Traits\GlobalStatus;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('extensions', key: 'id')]
#[Hidden([
    'script',
    'shortcode'
])]
#[Casts([
    'shortcode' => 'object'
])]
class Extension extends Model
{
    use GlobalStatus, HasModelAttributes;

    public function scopeGenerateScript()
    {
        $script = $this->script;
        foreach ($this->shortcode as $key => $item) {
            $script = str_replace('{{' . $key . '}}', $item->value, $script);
        }
        return $script;
    }
}
