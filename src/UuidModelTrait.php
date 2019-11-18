<?php

namespace Lucadello91\EloquentUuid;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

trait UuidModelTrait
{
    /**
     * The UUID versions.
     *
     * @var array
     */
    protected $uuidVersions = [
        'uuid1',
        'uuid3',
        'uuid4',
        'uuid5',
        'ordered',
    ];

    /**
     * Boot the trait, adding a creating observer.
     *
     * When persisting a new model instance, we resolve the UUID field, then set
     * a fresh UUID, taking into account if we need to cast to binary or not.
     *
     * @return void
     */
    protected static function bootUuidModelTrait(): void
    {
        static::creating(static function(Model $model) {
            $model->configureKeyType();

            /* @var Model|static $model */
            $uuid = $model->resolveUuid();

            $key = $model->getKeyName();

            if (isset($model->attributes[$key]) && $model->attributes[$key] !== NULL) {
                $uuid = Uuid::fromString(strtolower($model->attributes[$key]));
            }
            $model->attributes[$key] = $model->hasCast($key, 'uuid') ? $uuid->getBytes() : $uuid->toString();
        });

        static::retrieved(static function($model) {
            $model->configureKeyType();
        });
    }

    /**
     * Resolve a UUID instance for the configured version.
     *
     * @return UuidInterface
     */
    public function resolveUuid(): UuidInterface
    {
        if (($version = $this->resolveUuidVersion()) === 'ordered') {
            return Str::orderedUuid();
        }

        return call_user_func([Uuid::class, $version]);
    }

    /**
     * Resolve the UUID version to use when setting the UUID value. Default to uuid4.
     *
     * @return string
     */
    public function resolveUuidVersion(): string
    {
        if (property_exists($this, 'uuidVersion') && in_array($this->uuidVersion, $this->uuidVersions, TRUE)) {
            return $this->uuidVersion;
        }

        return 'uuid4';
    }

    private function configureKeyType(): void
    {
        $this->setKeyType($this->getKeyType() === 'int' ? 'string' : $this->getKeyType());
        $this->incrementing = FALSE;

        if ($this->getKeyType() === 'uuid') {
            $this->casts = array_merge([$this->getKeyName() => $this->getKeyType()], $this->casts);
        }
    }

    protected function castAttribute($key, $value)
    {
        if ($value !== NULL && !empty($value) && $this->getCastType($key) === 'uuid') {
            return Uuid::fromBytes($value)->toString();
        }

        return parent::castAttribute($key, $value);
    }
}
