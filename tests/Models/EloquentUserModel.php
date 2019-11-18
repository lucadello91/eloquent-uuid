<?php

namespace Spatie\MediaLibrary\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Lucadello91\EloquentUuid\UuidModelTrait;

class EloquentUserModel extends Model
{
    use UuidModelTrait;
    protected $table = 'users';

    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany(EloquentPostModel::class, 'user_id');
    }
}