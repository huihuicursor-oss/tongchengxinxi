<?php

namespace app\common\service;

use think\Db;

class AuthService
{
    protected static function buildUrl($route, array $params = [])
    {
        $path = '/index.php?s=/' . ltrim($route, '/');
        if (!$params) {
            return $path;
        }

        foreach ($params as $key => $value) {
            $path .= '/' . rawurlencode((string) $key) . '/' . rawurlencode((string) $value);
        }

        return $path;
    }

    public static function attempt($username, $password)
    {
        InstallerService::bootstrap();
        $user = Db::name('admin_user')
            ->alias('u')
            ->join('ec_role r', 'r.id = u.role_id', 'LEFT')
            ->field('u.*, r.name as role_name, r.code as role_code')
            ->where('u.username', $username)
            ->where('u.status', 1)
            ->find();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        Db::name('admin_user')->where('id', $user['id'])->update([
            'last_login_at' => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        $sessionUser = [
            'id'        => (int) $user['id'],
            'username'  => $user['username'],
            'real_name' => $user['real_name'],
            'role_id'   => (int) $user['role_id'],
            'role_name' => $user['role_name'],
            'role_code' => $user['role_code'],
        ];
        session('admin_user', $sessionUser);
        InstallerService::logOperation($sessionUser['id'], '认证中心', '登录', '管理员登录后台：' . $sessionUser['username']);
        return $sessionUser;
    }

    public static function logout()
    {
        $user = session('admin_user');
        if ($user) {
            InstallerService::logOperation($user['id'], '认证中心', '退出', '管理员退出后台：' . $user['username']);
        }
        session('admin_user', null);
    }

    public static function user()
    {
        return session('admin_user');
    }

    public static function can($roleId, $permission)
    {
        $role = Db::name('role')->where('id', $roleId)->find();
        if (!$role) {
            return false;
        }
        if ($role['code'] === 'super_admin') {
            return true;
        }

        return Db::name('role_menu')
            ->alias('rm')
            ->join('ec_menu m', 'm.id = rm.menu_id', 'LEFT')
            ->where('rm.role_id', $roleId)
            ->where('m.permission', $permission)
            ->where('m.status', 1)
            ->count() > 0;
    }

    public static function menuItems($roleId, $activePermission)
    {
        $role = Db::name('role')->where('id', $roleId)->find();
        $query = Db::name('menu')->where('status', 1)->order('sort asc, id asc');
        if ($role && $role['code'] !== 'super_admin') {
            $menuIds = Db::name('role_menu')->where('role_id', $roleId)->column('menu_id');
            $query->whereIn('id', $menuIds ?: [0]);
        }
        $menus = $query->select();
        foreach ($menus as &$menu) {
            $menu['active'] = $menu['permission'] === $activePermission ? 1 : 0;
            $menu['url'] = self::buildUrl($menu['route']);
        }
        return $menus;
    }

    public static function users()
    {
        return Db::name('admin_user')
            ->alias('u')
            ->join('ec_role r', 'r.id = u.role_id', 'LEFT')
            ->field('u.id,u.username,u.real_name,u.status,u.last_login_at,r.name as role_name,r.id as role_id')
            ->order('u.id asc')
            ->select();
    }

    public static function roles()
    {
        return Db::name('role')->order('id asc')->select();
    }

    public static function roleMenuIds($roleId)
    {
        return Db::name('role_menu')->where('role_id', $roleId)->column('menu_id');
    }

    public static function saveRoleMenus($roleId, array $menuIds, $operatorId)
    {
        Db::startTrans();
        try {
            Db::name('role_menu')->where('role_id', $roleId)->delete();
            foreach ($menuIds as $menuId) {
                Db::name('role_menu')->insert([
                    'role_id' => (int) $roleId,
                    'menu_id' => (int) $menuId,
                ]);
            }
            Db::commit();
            InstallerService::logOperation($operatorId, '权限管理', '分配菜单权限', '更新角色 #' . $roleId . ' 的菜单权限');
        } catch (\Throwable $e) {
            Db::rollback();
            throw $e;
        }
    }

    public static function assignUserRole($userId, $roleId, $operatorId)
    {
        Db::name('admin_user')->where('id', $userId)->update([
            'role_id'    => (int) $roleId,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        InstallerService::logOperation($operatorId, '权限管理', '调整用户角色', '用户 #' . $userId . ' 角色调整为 #' . $roleId);
    }
}
