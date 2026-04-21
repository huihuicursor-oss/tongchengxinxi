<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

class Notice extends Model
{
    protected $name = 'notices';
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';
}
