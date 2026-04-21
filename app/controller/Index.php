<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        if (isset($_SESSION['admin_user'])) {
            return redirect('/dashboard');
        }

        return redirect('/login');
    }

    public function hello(string $name = 'ThinkPHP8')
    {
        return 'hello,' . $name;
    }
}
