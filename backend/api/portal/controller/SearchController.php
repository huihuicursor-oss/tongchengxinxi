<?php

namespace api\portal\controller;

use api\portal\service\PortalDemoService;
use cmf\controller\RestBaseController;

class SearchController extends RestBaseController
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

    public function content()
    {
        $this->success('搜索信息成功', $this->service->contents($this->request->param()));
    }

    public function merchants()
    {
        $this->success('搜索商家成功', $this->service->merchantList($this->request->param()));
    }
}
