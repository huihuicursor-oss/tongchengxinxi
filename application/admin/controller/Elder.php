<?php

namespace app\admin\controller;

use think\Db;

class Elder extends Base
{
    public function index()
    {
        $keyword = $this->request->get('keyword/s', '');
        $query = Db::name('elder_profile')->order('id asc');
        if ($keyword !== '') {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->whereLike('name', '%' . $keyword . '%')
                    ->whereOrLike('room', '%' . $keyword . '%')
                    ->whereOrLike('care_level', '%' . $keyword . '%');
            });
        }
        $elders = $query->select();
        foreach ($elders as &$elder) {
            $elder['detail_url'] = $this->buildPathUrl('admin/elder/show', ['id' => $elder['id']]);
        }

        $this->assign([
            'pageTitle' => '老人档案',
            'keyword'   => $keyword,
            'elders'    => $elders,
        ]);
        return $this->fetch();
    }

    public function show($id)
    {
        $elder = Db::name('elder_profile')->where('id', (int) $id)->find();
        if (!$elder) {
            $this->error('老人档案不存在');
        }

        $this->assign([
            'pageTitle'     => '老人详情',
            'elder'         => $elder,
            'healthRecords' => Db::name('health_record')->where('elder_id', $elder['id'])->order('id desc')->limit(10)->select(),
            'alerts'        => Db::name('alert_event')->where('elder_id', $elder['id'])->order('id desc')->limit(10)->select(),
            'visits'        => Db::name('visit_record')->where('elder_id', $elder['id'])->order('id desc')->limit(10)->select(),
            'backUrl'       => $this->buildPathUrl('admin/elder/index'),
        ]);
        return $this->fetch();
    }
}
