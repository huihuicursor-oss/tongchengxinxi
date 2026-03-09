<?php

namespace api\portal\controller;

use api\portal\service\PortalDemoService;
use cmf\controller\RestBaseController;

class ContentController extends RestBaseController
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

    public function channels()
    {
        $this->success('获取频道成功', $this->service->channels());
    }

    public function index()
    {
        $params = $this->request->param();
        $this->success('获取信息列表成功', $this->service->contents($params));
    }

    public function read($id)
    {
        $detail = $this->service->contentDetail($id);

        if (!$detail) {
            $this->error('信息不存在');
        }

        $this->success('获取信息详情成功', $detail);
    }

    public function save()
    {
        $this->success('发布成功', $this->service->publishContent($this->request->post()));
    }
}
