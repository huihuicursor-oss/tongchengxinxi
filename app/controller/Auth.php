<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use app\model\AdminUser;
use think\facade\View;

class Auth extends BaseController
{
    public function loginForm()
    {
        if (isset($_SESSION['admin_user'])) {
            return redirect(app_url_path('dashboard'));
        }

        View::assign([
            'error' => '',
            'lastUsername' => '',
            'appBase' => app_base_url(),
        ]);

        return View::fetch('auth/login');
    }

    public function login()
    {
        $data = [
            'username' => trim((string) $this->request->post('username')),
            'password' => (string) $this->request->post('password'),
        ];

        $this->validate($data, [
            'username' => 'require|max:50',
            'password' => 'require|min:6|max:50',
        ], [
            'username.require' => '请输入账号',
            'password.require' => '请输入密码',
        ]);

        $user = AdminUser::where('username', $data['username'])->find();
        if (!$user || (int) $user->status !== 1 || !password_verify($data['password'], (string) $user->password)) {
            View::assign([
                'error' => '用户名或密码错误',
                'lastUsername' => $data['username'],
                'appBase' => app_base_url(),
            ]);

            return View::fetch('auth/login');
        }

        $sessionUser = [
            'id' => (int) $user->id,
            'username' => (string) $user->username,
            'name' => (string) $user->name,
            'role' => (string) $user->role,
        ];

        $_SESSION['admin_user'] = $sessionUser;
        $user->save(['last_login_at' => date('Y-m-d H:i:s')]);

        return redirect(app_url_path('dashboard'));
    }

    public function logout()
    {
        unset($_SESSION['admin_user']);

        return redirect(app_url_path('login'));
    }
}
