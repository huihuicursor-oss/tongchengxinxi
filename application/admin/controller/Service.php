<?php

namespace app\admin\controller;

class Service extends BaseController
{
    public function index()
    {
        return $this->renderPage('service', [
            'pageTitle' => '服务调度',
            'pageDescription' => '护理排班、送餐服务、康复训练等任务统一调度。',
            'serviceSummary' => $this->repo->serviceSummary(),
            'serviceSchedules' => $this->repo->serviceSchedules(),
        ]);
    }
}
