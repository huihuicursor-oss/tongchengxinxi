<?php
declare(strict_types=1);

namespace app\model;

use think\Model;
use think\model\relation\BelongsTo;

class FamilyContact extends Model
{
    protected $name = 'family_contacts';
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';

    public function elder(): BelongsTo
    {
        return $this->belongsTo(Elder::class, 'elder_id', 'id');
    }
}
