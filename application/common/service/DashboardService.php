<?php

namespace app\common\service;

use think\Db;

class DashboardService
{
    public static function summary()
    {
        return [
            'stats' => [
                ['label' => '在住老人', 'value' => Db::name('elder_profile')->where('status', '在住')->count(), 'unit' => '人'],
                ['label' => '待处理告警', 'value' => Db::name('alert_event')->where('status', '待处理')->count(), 'unit' => '条'],
                ['label' => '待审核探访', 'value' => Db::name('visit_record')->where('status', '待审核')->count(), 'unit' => '单'],
                ['label' => '今日服务', 'value' => Db::name('service_order')->count(), 'unit' => '项'],
            ],
            'alerts' => Db::name('alert_event')
                ->alias('a')
                ->join('ec_elder_profile e', 'e.id = a.elder_id', 'LEFT')
                ->field('a.id,a.title,a.alert_type,a.level,a.status,a.created_at,e.name,e.room')
                ->order('a.id desc')
                ->limit(5)
                ->select(),
            'visits' => Db::name('visit_record')
                ->alias('v')
                ->join('ec_elder_profile e', 'e.id = v.elder_id', 'LEFT')
                ->field('v.id,v.visitor_name,v.relation,v.visit_time,v.status,e.name')
                ->order('v.id desc')
                ->limit(5)
                ->select(),
            'services' => Db::name('service_order')->order('id asc')->limit(5)->select(),
        ];
    }
}
