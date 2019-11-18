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
        static::creating(static function ($model) {
            $model->keyType = $model->keyType === 'int' ? 'string' : $model->keyType;
            $model->incrementing = false;

            /* @var Model|static $model */
            /* @var UuidInterface $uuid */
            $uuid = $model->resolveUuid();

            $key = $model->getKeyName();

            if (isset($model->attributes[$key]) && $model->attributes[$key] !== null) {
                $uuid = Uuid::fromString(strtolower($model->attributes[$key]));
            }
            $model->attributes[$key] = $model->hasCast($key, 'uuid') ? $uuid->getBytes() : $uuid->toString();
        });
    }

    /**
     * Determine whether an attribute should be cast to a native type.
     *
     * @param string            $key
     * @param array|string|null $types
     *
     * @return bool
     */
    abstract public function hasCast($key, $types = null);

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
        if (property_exists($this, 'uuidVersion') && in_array($this->uuidVersion, $this->uuidVersions, true)) {
            return $this->uuidVersion;
        }

        return 'uuid4';
    }
}