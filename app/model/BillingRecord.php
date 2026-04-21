<?php
declare(strict_types=1);

namespace app\model;

use think\Model;
use think\model\relation\BelongsTo;

class BillingRecord extends Model
{
    protected $name = 'billing_records';
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';

    public function elder(): BelongsTo
    {
        return $this->belongsTo(Elder::class, 'elder_id', 'id');
    }
}
