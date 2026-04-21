<?php

namespace app\admin\controller;

use think\Db;

class Log extends Base
{
    public function index()
    {
        $this->assign([
            'pageTitle' => '运营日志',
            'logs'      => Db::name('operation_log')->order('id desc')->limit(100)->select(),
        ]);
        return $this->fetch();
    }
}
