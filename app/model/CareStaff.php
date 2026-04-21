<?php
declare(strict_types=1);

namespace app\model;

use think\Model;

class CareStaff extends Model
{
    protected $name = 'care_staff';
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';
}
