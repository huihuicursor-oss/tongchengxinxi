<?php

namespace api\portal\controller;

use api\portal\service\PortalDemoService;
use cmf\controller\RestBaseController;

class DiscoveryController extends RestBaseController
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
        $this->success('获取发现列表成功', $this->service->discoveryFeed($this->request->param()));
    }

    public function topics()
    {
        $this->success('获取发现话题成功', $this->service->topics());
    }

    public function friends()
    {
        $this->success('获取圈友列表成功', $this->service->friends());
    }

    public function read($id)
    {
        $detail = $this->service->discoveryDetail($id);

        if (!$detail) {
            $this->error('帖子不存在');
        }

        $this->success('获取帖子详情成功', $detail);
    }

    public function save()
    {
        $this->success('发帖成功', $this->service->publishDiscovery($this->request->post()));
    }
}
