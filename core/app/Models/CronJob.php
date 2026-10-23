<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Table;
use App\Traits\GlobalStatus;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Model;

#[Table('cron_jobs', key: 'id')]
#[Casts([
    'action' => 'array',
])]
class CronJob extends Model
{
    use GlobalStatus, HasModelAttributes;

    public function schedule()
    {
        return $this->belongsTo(CronSchedule::class, 'cron_schedule_id');
    }

    public function logs()
    {
        return $this->hasMany(CronJobLog::class, 'cron_job_id');
    }
}
