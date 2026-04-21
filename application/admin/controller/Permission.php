<?php

namespace app\admin\controller;

use app\common\service\AuthService;
use think\Db;

class Permission extends Base
{

    public function index()
    {
        $roles = AuthService::roles();
        $roleId = (int) $this->request->get('role_id/d', isset($roles[0]) ? $roles[0]['id'] : 0);

        $this->assign([
            'pageTitle'       => '权限管理',
            'users'           => AuthService::users(),
            'roles'           => $roles,
            'menus'           => Db::name('menu')->order('sort asc, id asc')->select(),
            'selectedRoleId'  => $roleId,
            'selectedMenuIds' => AuthService::roleMenuIds($roleId),
            'assignRoleUrl'   => $this->buildPathUrl('admin/permission/assignRole'),
            'saveRoleMenuUrl' => $this->buildPathUrl('admin/permission/saveRoleMenus'),
        ]);
        return $this->fetch();
    }

    public function assignRole()
    {
        AuthService::assignUserRole(
            (int) $this->request->post('user_id/d', 0),
            (int) $this->request->post('role_id/d', 0),
            $this->user['id']
        );
        $this->success('用户角色已更新', $this->buildPathUrl('admin/permission/index'));
    }

    public function saveRoleMenus()
    {
        $roleId = (int) $this->request->post('role_id/d', 0);
        AuthService::saveRoleMenus($roleId, $this->request->post('menu_ids/a', []), $this->user['id']);
        $this->success('角色菜单权限已更新', $this->buildPathUrl('admin/permission/index', ['role_id' => $roleId]));
    }
}
