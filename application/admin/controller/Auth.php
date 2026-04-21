<?php

namespace app\admin\controller;

use app\common\service\AuthService;
use app\common\service\InstallerService;
use think\Controller;

class Auth extends Controller
{
    protected function initialize()
    {
        InstallerService::bootstrap();
    }

    public function login()
    {
        if (AuthService::user()) {
            $this->redirect('/index.php?s=/admin/dashboard/index');
            return;
        }

        if ($this->request->isPost()) {
            $username = $this->request->post('username/s', '');
            $password = $this->request->post('password/s', '');
            if (AuthService::attempt($username, $password)) {
                $this->redirect('/index.php?s=/admin/dashboard/index');
                return;
            }
            $this->assign('errorMessage', '账号或密码错误，或账号已被禁用。');
        }

        $this->assign('appName', config('app_name'));
        return $this->fetch();
    }

    public function logout()
    {
        AuthService::logout();
        $this->redirect('/index.php?s=/admin/auth/login');
    }
}
