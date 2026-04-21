<?php

namespace app\admin\controller;

class Log extends BaseController
{
    public function index()
    {
        return $this->renderPage('log', [
            'pageTitle' => '运营日志',
            'pageDescription' => '记录告警处理、档案修改、排班调整等关键操作。',
            'logs' => $this->repo->operationLogs(),
        ]);
    }
}
