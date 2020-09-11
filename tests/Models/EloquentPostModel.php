<?php

namespace Lucadello91\EloquentUuid\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Lucadello91\EloquentUuid\UuidModelTrait;

class EloquentPostModel extends Model
{
    use UuidModelTrait;

    protected $table = 'posts';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(EloquentUserModel::class, 'user_id');
    }
}
