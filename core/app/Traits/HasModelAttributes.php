<?php

namespace App\Traits;

use App\Attributes\Casts;
use App\Attributes\Fillable;
use App\Attributes\Guarded;
use App\Attributes\Hidden;
use App\Attributes\Table;
use ReflectionClass;

trait HasModelAttributes
{
    /**
     * Internal reflection cache for model attributes.
     *
     * @var array<string, array<string, mixed>>
     */
    protected static array $modelAttributeCache = [];

    /**
     * Initialize the HasModelAttributes trait on instantiation.
     */
    public function initializeHasModelAttributes(): void
    {
        $metadata = static::resolveModelAttributes();

        if (isset($metadata['table'])) {
            $this->setTable($metadata['table']);
        }

        if (isset($metadata['primaryKey'])) {
            $this->setKeyName($metadata['primaryKey']);
        }

        if (!empty($metadata['hidden'])) {
            $this->hidden = array_values(array_unique(array_merge($this->hidden, $metadata['hidden'])));
        }

        if (!empty($metadata['casts'])) {
            $this->casts = array_merge($this->casts, $metadata['casts']);
        }

        if (!empty($metadata['fillable'])) {
            $this->fillable = array_values(array_unique(array_merge($this->fillable, $metadata['fillable'])));
        }

        if (!empty($metadata['guarded'])) {
            $this->guarded = array_values(array_unique(array_merge($this->guarded, $metadata['guarded'])));
        }
    }

    /**
     * Resolve and cache attributes declared on the model class.
     *
     * @return array<string, mixed>
     */
    public static function resolveModelAttributes(): array
    {
        $class = static::class;

        if (isset(static::$modelAttributeCache[$class])) {
            return static::$modelAttributeCache[$class];
        }

        $metadata = [];
        $reflection = new ReflectionClass($class);

        // #[Table]
        $tableAttributes = $reflection->getAttributes(Table::class);
        if (!empty($tableAttributes)) {
            $instance = $tableAttributes[0]->newInstance();
            $metadata['table'] = $instance->name;
            if ($instance->key !== null) {
                $metadata['primaryKey'] = $instance->key;
            }
        }

        // #[Hidden]
        $hiddenAttributes = $reflection->getAttributes(Hidden::class);
        if (!empty($hiddenAttributes)) {
            $instance = $hiddenAttributes[0]->newInstance();
            $metadata['hidden'] = $instance->hidden;
        }

        // #[Casts]
        $castsAttributes = $reflection->getAttributes(Casts::class);
        if (!empty($castsAttributes)) {
            $instance = $castsAttributes[0]->newInstance();
            $metadata['casts'] = $instance->casts;
        }

        // #[Fillable]
        $fillableAttributes = $reflection->getAttributes(Fillable::class);
        if (!empty($fillableAttributes)) {
            $instance = $fillableAttributes[0]->newInstance();
            $metadata['fillable'] = $instance->fillable;
        }

        // #[Guarded]
        $guardedAttributes = $reflection->getAttributes(Guarded::class);
        if (!empty($guardedAttributes)) {
            $instance = $guardedAttributes[0]->newInstance();
            $metadata['guarded'] = $instance->guarded;
        }

        return static::$modelAttributeCache[$class] = $metadata;
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        if (isset($this->table)) {
            return $this->table;
        }

        $metadata = static::resolveModelAttributes();
        if (isset($metadata['table'])) {
            return $this->table = $metadata['table'];
        }

        return parent::getTable();
    }

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName()
    {
        if (isset($this->primaryKey)) {
            return $this->primaryKey;
        }

        $metadata = static::resolveModelAttributes();
        if (isset($metadata['primaryKey'])) {
            return $this->primaryKey = $metadata['primaryKey'];
        }

        return parent::getKeyName();
    }

    /**
     * Get the casts array.
     *
     * @return array
     */
    public function getCasts()
    {
        $metadata = static::resolveModelAttributes();
        if (!empty($metadata['casts'])) {
            return array_merge(parent::getCasts(), $metadata['casts']);
        }

        return parent::getCasts();
    }

    /**
     * Get the hidden attributes for the model.
     *
     * @return array
     */
    public function getHidden()
    {
        $metadata = static::resolveModelAttributes();
        if (!empty($metadata['hidden'])) {
            return array_values(array_unique(array_merge(parent::getHidden(), $metadata['hidden'])));
        }

        return parent::getHidden();
    }

    /**
     * Get the fillable attributes for the model.
     *
     * @return array
     */
    public function getFillable()
    {
        $metadata = static::resolveModelAttributes();
        if (!empty($metadata['fillable'])) {
            return array_values(array_unique(array_merge(parent::getFillable(), $metadata['fillable'])));
        }

        return parent::getFillable();
    }

    /**
     * Get the guarded attributes for the model.
     *
     * @return array
     */
    public function getGuarded()
    {
        $metadata = static::resolveModelAttributes();
        if (!empty($metadata['guarded'])) {
            return array_values(array_unique(array_merge(parent::getGuarded(), $metadata['guarded'])));
        }

        return parent::getGuarded();
    }
}
