<?php

namespace app\admin\controller;

class Health extends BaseController
{
    public function index()
    {
        return $this->renderPage('health', [
            'pageTitle' => '健康监测',
            'pageDescription' => '血压、血氧、心率与预警事件集中展示。',
            'healthOverview' => $this->repo->healthOverview(),
            'healthRecords' => $this->repo->healthRecords(),
            'alerts' => $this->repo->alertEvents(),
        ]);
    }
}
