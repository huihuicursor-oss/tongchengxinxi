<?php

namespace app\admin\controller;

use think\Db;

class Service extends Base
{
    public function index()
    {
        $status = $this->request->get('status/s', '');
        $query = Db::name('service_order')->order('service_time asc, id asc');
        if ($status !== '') {
            $query->where('status', $status);
        }

        $this->assign([
            'pageTitle' => '服务调度',
            'status'    => $status,
            'services'  => $query->select(),
        ]);
        return $this->fetch();
    }
}
