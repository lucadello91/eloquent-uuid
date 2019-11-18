<?php

namespace Lucadello91\EloquentUuid\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Lucadello91\EloquentUuid\UuidModelTrait;

class EloquentUserBinaryModel extends Model
{
    use UuidModelTrait;
    protected $table = 'users_binary';

    protected $keyType = 'uuid';

    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany(EloquentPostBinaryModel::class, 'user_id');
    }
}
