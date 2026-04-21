<?php

namespace app\admin\controller;

use think\Db;

class Health extends Base
{
    public function index()
    {
        $records = Db::name('health_record')
            ->alias('h')
            ->join('ec_elder_profile e', 'e.id = h.elder_id', 'LEFT')
            ->field('h.*,e.name,e.room')
            ->order('h.measured_at desc, h.id desc')
            ->select();

        $this->assign([
            'pageTitle' => '健康监测',
            'overview'  => [
                ['label' => '正常', 'value' => Db::name('health_record')->where('risk_level', '正常')->count(), 'unit' => '条'],
                ['label' => '重点关注', 'value' => Db::name('health_record')->where('risk_level', '关注')->count(), 'unit' => '条'],
                ['label' => '预警', 'value' => Db::name('health_record')->where('risk_level', '预警')->count(), 'unit' => '条'],
                ['label' => '监测设备在线', 'value' => '99', 'unit' => '%'],
            ],
            'records'   => $records,
        ]);
        return $this->fetch();
    }
}
