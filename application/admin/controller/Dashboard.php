<?php

namespace app\admin\controller;

use app\common\service\DashboardService;

class Dashboard extends Base
{
    public function index()
    {
        $this->assign([
            'pageTitle' => '系统总览',
            'summary'   => DashboardService::summary(),
        ]);
        return $this->fetch();
    }
}
