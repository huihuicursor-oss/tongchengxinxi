<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use think\facade\Session;

class Index extends BaseController
{
    public function index()
    {
        if (Session::has('admin_user')) {
            return redirect('/dashboard');
        }

        return redirect('/login');
    }

    public function hello(string $name = 'ThinkPHP8')
    {
        return 'hello,' . $name;
    }
}
