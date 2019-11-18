<?php

namespace Lucadello91\EloquentUuid\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Lucadello91\EloquentUuid\UuidModelTrait;

class EloquentPostBinaryModel extends Model
{
    use UuidModelTrait;
    protected $table = 'posts_binary';

    protected $casts = [
        'id' => 'uuid',
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(EloquentUserBinaryModel::class, 'user_id');
    }
}
