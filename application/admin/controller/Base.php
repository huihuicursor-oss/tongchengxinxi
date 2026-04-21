<?php

namespace app\admin\controller;

use app\common\service\AuthService;
use app\common\service\InstallerService;
use think\Controller;

class Base extends Controller
{
    protected $user;

    protected function buildPathUrl($route, array $params = [])
    {
        $path = '/index.php?s=/' . ltrim($route, '/');
        foreach ($params as $key => $value) {
            $path .= '/' . rawurlencode((string) $key) . '/' . rawurlencode((string) $value);
        }

        return $path;
    }

    protected function initialize()
    {
        InstallerService::bootstrap();
        $this->user = AuthService::user();
        if (!$this->user) {
            $this->redirect('/index.php?s=/admin/auth/login');
            return;
        }

        $permission = $this->resolvePermission();
        if (!AuthService::can($this->user['role_id'], $permission)) {
            $this->error('当前账号没有权限访问该功能');
        }

        $appName = config('app_name') ?: config('app.app_name');

        $this->assign([
            'appName'           => $appName,
            'userInfo'          => $this->user,
            'currentPermission' => $permission,
            'menuItems'         => AuthService::menuItems($this->user['role_id'], $permission),
            'logoutUrl'         => '/index.php?s=/admin/auth/logout',
        ]);
    }

    protected function resolvePermission()
    {
        $permission = strtolower($this->request->controller() . '/' . $this->request->action());
        $aliases = [
            'elder/show'               => 'elder/index',
            'alert/handle'             => 'alert/index',
            'visit/review'             => 'visit/index',
            'permission/assignrole'    => 'permission/index',
            'permission/saverolemenus' => 'permission/index',
            'menu/save'                => 'menu/index',
            'menu/toggle'              => 'menu/index',
        ];

        return isset($aliases[$permission]) ? $aliases[$permission] : $permission;
    }

    protected function writeLog($module, $action, $content)
    {
        InstallerService::logOperation($this->user['id'], $module, $action, $content);
    }
}
