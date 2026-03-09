<?php

namespace api\portal\controller;

use api\portal\service\PortalDemoService;
use cmf\controller\RestBaseController;

class UserController extends RestBaseController
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

    public function dashboard()
    {
        $this->success('获取用户中心成功', $this->service->dashboard());
    }

    public function collections()
    {
        $this->success('获取收藏成功', $this->service->collections());
    }

    public function collect()
    {
        $this->success('收藏状态已更新', $this->service->toggleCollection($this->request->post()));
    }

    public function feedback()
    {
        $this->success('反馈已提交', $this->service->feedback($this->request->post()));
    }

    public function authStatus()
    {
        $this->success('获取实名认证状态成功', $this->service->authStatus());
    }
}
