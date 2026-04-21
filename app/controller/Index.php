<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return redirect((string) url('elderlyCare/dashboard'));
    }

    public function hello(string $name = 'ThinkPHP8')
    {
        return 'hello,' . $name;
    }
}
