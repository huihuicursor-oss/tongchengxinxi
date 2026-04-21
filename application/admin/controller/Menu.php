<?php

namespace app\admin\controller;

use think\Db;

class Menu extends Base
{
    public function index()
    {
        $menus = Db::name('menu')->order('sort asc, id asc')->select();
        foreach ($menus as &$menu) {
            $menu['toggle_url'] = $this->buildPathUrl('admin/menu/toggle', ['id' => $menu['id']]);
        }

        $this->assign([
            'pageTitle' => '菜单管理',
            'menus'     => $menus,
            'saveUrl'   => $this->buildPathUrl('admin/menu/save'),
        ]);
        return $this->fetch();
    }

    public function save()
    {
        $payload = [
            'parent_id'  => (int) $this->request->post('parent_id/d', 0),
            'title'      => $this->request->post('title/s', ''),
            'route'      => $this->request->post('route/s', ''),
            'permission' => $this->request->post('permission/s', ''),
            'sort'       => (int) $this->request->post('sort/d', 0),
            'status'     => (int) $this->request->post('status/d', 1),
        ];
        $id = (int) $this->request->post('id/d', 0);

        if ($id > 0) {
            Db::name('menu')->where('id', $id)->update($payload);
            $this->writeLog('菜单管理', '更新菜单', '更新菜单 #' . $id . '：' . $payload['title']);
        } else {
            $payload['created_at'] = date('Y-m-d H:i:s');
            Db::name('menu')->insert($payload);
            $this->writeLog('菜单管理', '新增菜单', '新增菜单：' . $payload['title']);
        }

        $this->success('菜单已保存', $this->buildPathUrl('admin/menu/index'));
    }

    public function toggle($id)
    {
        $menu = Db::name('menu')->where('id', (int) $id)->find();
        if (!$menu) {
            $this->error('菜单不存在');
        }
        $newStatus = (int) !$menu['status'];
        Db::name('menu')->where('id', $menu['id'])->update(['status' => $newStatus]);
        $this->writeLog('菜单管理', '切换菜单状态', '菜单 #' . $menu['id'] . ' 状态切换为 ' . $newStatus);
        $this->success('菜单状态已更新', $this->buildPathUrl('admin/menu/index'));
    }
}
