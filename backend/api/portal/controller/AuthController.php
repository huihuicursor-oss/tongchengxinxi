<?php

namespace api\portal\controller;

use api\portal\service\PortalDemoService;
use cmf\controller\RestBaseController;

class AuthController extends RestBaseController
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

    public function login()
    {
        $params = $this->request->post();
        $result = $this->service->login($params);

        if (!$result['ok']) {
            $this->error($result['message']);
        }

        $this->success($result['message'], $result['data']);
    }

    public function register()
    {
        $this->success('注册成功', $this->service->register($this->request->post()));
    }

    public function wxLogin()
    {
        $params = $this->request->post();
        $this->success('微信登录模拟成功', $this->service->wxLogin($params));
    }
}
