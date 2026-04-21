<?php

namespace app\admin\controller;

class Dashboard extends BaseController
{
    public function index()
    {
        $dashboard = $this->repo->getDashboard();

        return $this->renderPage('dashboard', [
            'pageTitle' => '系统总览',
            'pageDescription' => '展示机构入住情况、健康告警、服务执行和通知公告。',
            'summary' => $dashboard,
            'alerts' => $dashboard['alerts'],
            'notices' => $dashboard['notices'],
        ]);
    }
}
