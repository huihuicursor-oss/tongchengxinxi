<?php

namespace app\admin\controller;

use think\Db;

class Visit extends Base
{
    public function index()
    {
        $status = $this->request->get('status/s', '');
        $query = Db::name('visit_record')
            ->alias('v')
            ->join('ec_elder_profile e', 'e.id = v.elder_id', 'LEFT')
            ->field('v.*,e.name')
            ->order('v.id desc');
        if ($status !== '') {
            $query->where('v.status', $status);
        }
        $visits = $query->select();
        foreach ($visits as &$visit) {
            $visit['review_url'] = $this->buildPathUrl('admin/visit/review', ['id' => $visit['id']]);
        }

        $this->assign([
            'pageTitle' => '家属探访',
            'status'    => $status,
            'visits'    => $visits,
        ]);
        return $this->fetch();
    }

    public function review($id)
    {
        Db::name('visit_record')->where('id', (int) $id)->update([
            'status'      => $this->request->post('status/s', '已通过'),
            'review_note' => $this->request->post('review_note/s', ''),
            'reviewed_by' => $this->user['real_name'],
            'reviewed_at' => date('Y-m-d H:i:s'),
        ]);
        $this->writeLog('家属探访', '审核预约', '审核探访记录 #' . (int) $id);
        $this->success('探访审核结果已保存', $this->buildPathUrl('admin/visit/index'));
    }
}
