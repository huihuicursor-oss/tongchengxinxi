<?php

namespace app\admin\controller;

use think\Db;

class Alert extends Base
{
    protected function pageUrl($route, array $params = [])
    {
        return $this->buildPathUrl($route, $params);
    }

    public function index()
    {
        $status = $this->request->get('status/s', '');
        $level = $this->request->get('level/s', '');
        $query = Db::name('alert_event')
            ->alias('a')
            ->join('ec_elder_profile e', 'e.id = a.elder_id', 'LEFT')
            ->field('a.*,e.name,e.room')
            ->order('a.id desc');
        if ($status !== '') {
            $query->where('a.status', $status);
        }
        if ($level !== '') {
            $query->where('a.level', $level);
        }
        $alerts = $query->select();
        foreach ($alerts as &$alert) {
            $alert['handle_url'] = $this->pageUrl('admin/alert/handle', ['id' => $alert['id']]);
        }

        $this->assign([
            'pageTitle' => '告警处理',
            'status'    => $status,
            'level'     => $level,
            'alerts'    => $alerts,
        ]);
        return $this->fetch();
    }

    public function handle($id)
    {
        Db::name('alert_event')->where('id', (int) $id)->update([
            'status'       => $this->request->post('status/s', '已处理'),
            'handled_by'   => $this->user['real_name'],
            'handled_note' => $this->request->post('handled_note/s', ''),
            'handled_at'   => date('Y-m-d H:i:s'),
        ]);
        $this->writeLog('告警处理', '处置告警', '处理告警 #' . (int) $id);
        $this->success('告警处理结果已保存', $this->pageUrl('admin/alert/index'));
    }
}
