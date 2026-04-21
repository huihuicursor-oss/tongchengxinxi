<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

class Activity extends Model
{
    protected $name = 'activities';
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';
}
