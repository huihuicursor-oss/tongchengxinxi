<?php

namespace api\portal\controller;

use api\portal\service\PortalDemoService;
use cmf\controller\RestBaseController;

class NewsController extends RestBaseController
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
        $this->success('获取头条列表成功', $this->service->newsList($this->request->param()));
    }

    public function read($id)
    {
        $detail = $this->service->newsDetail($id);

        if (!$detail) {
            $this->error('头条不存在');
        }

        $this->success('获取头条详情成功', $detail);
    }
}
