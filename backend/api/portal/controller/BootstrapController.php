<?php

namespace api\portal\controller;

use api\portal\service\PortalDemoService;
use cmf\controller\RestBaseController;

class BootstrapController extends RestBaseController
{
    /**
     * @var PortalDemoService
     */
    protected $service;

    protected function initialize()
    {
        parent::initialize();
        $this->service = new PortalDemoService();
    }

    public function index()
    {
        $this->success('获取首页配置成功', $this->service->bootstrap());
    }

    public function cities()
    {
        $this->success('获取城市树成功', $this->service->cities());
    }

    public function guessCity()
    {
        $params = $this->request->param();
        $this->success('定位成功', $this->service->guessCity($params));
    }

    public function params()
    {
        $this->success('获取上传参数成功', $this->service->uploadParams());
    }
}
