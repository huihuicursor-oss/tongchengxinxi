<?php
declare(strict_types=1);

namespace app\model;

use think\Model;
use think\model\relation\BelongsTo;

class HealthRecord extends Model
{
    protected $name = 'health_records';
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';

    public function elder(): BelongsTo
    {
        return $this->belongsTo(Elder::class, 'elder_id', 'id');
    }
}
