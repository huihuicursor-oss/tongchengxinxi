<?php

namespace api\portal\controller;

use api\portal\service\PortalDemoService;
use cmf\controller\RestBaseController;

class MerchantController extends RestBaseController
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
        $this->success('获取商家列表成功', $this->service->merchantList($this->request->param()));
    }

    public function read($id)
    {
        $detail = $this->service->merchantDetail($id);

        if (!$detail) {
            $this->error('商家不存在');
        }

        $this->success('获取商家详情成功', $detail);
    }

    public function comment()
    {
        $this->success('点评成功', $this->service->commentMerchant($this->request->post()));
    }

    public function apply()
    {
        $this->success('申请入驻已提交', $this->service->applyMerchant($this->request->post()));
    }
}
