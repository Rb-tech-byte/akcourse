<?php

namespace App\Traits;

use App\Attributes\Connection;
use App\Attributes\Timeout;
use App\Attributes\Tries;
use ReflectionClass;

trait HasJobAttributes
{
    /**
     * Configure declarative attributes on the queue job.
     */
    public function configureJobAttributes(): void
    {
        $reflection = new ReflectionClass(static::class);

        $connectionAttrs = $reflection->getAttributes(Connection::class);
        if (!empty($connectionAttrs)) {
            $this->connection = $connectionAttrs[0]->newInstance()->connection;
        }

        $triesAttrs = $reflection->getAttributes(Tries::class);
        if (!empty($triesAttrs)) {
            $this->tries = $triesAttrs[0]->newInstance()->tries;
        }

        $timeoutAttrs = $reflection->getAttributes(Timeout::class);
        if (!empty($timeoutAttrs)) {
            $this->timeout = $timeoutAttrs[0]->newInstance()->timeout;
        }
    }
}
