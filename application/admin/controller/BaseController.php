<?php

namespace app\admin\controller;

use app\common\service\DemoRepository;

class BaseController
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new DemoRepository();
    }

    protected function renderPage($currentNav, array $payload = [])
    {
        $config = include dirname(dirname(__DIR__)) . '/config.php';
        $layoutFile = dirname(__DIR__) . '/view/layout.php';
        $viewFile = dirname(__DIR__) . '/view/' . $currentNav . '.php';

        $data = array_merge([
            'systemName' => $config['app_name'],
            'pageTitle' => '智慧养老管理系统',
            'pageDescription' => '机构养老、健康预警、服务调度、探访管理一体化协同后台。',
            'activeMenu' => $currentNav,
            'menuItems' => [
                'dashboard' => '系统总览',
                'elder' => '老人档案',
                'health' => '健康监测',
                'service' => '服务调度',
                'visit' => '家属探访',
                'log' => '运营日志',
            ],
            'viewFile' => $viewFile,
        ], $payload);

        extract($data, EXTR_OVERWRITE);
        ob_start();
        include $layoutFile;
        $content = ob_get_clean();
        return $content;
    }
}
