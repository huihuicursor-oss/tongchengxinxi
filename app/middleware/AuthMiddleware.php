<?php
declare(strict_types=1);

namespace app\middleware;

use Closure;
use think\facade\Session;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('admin_user')) {
            return redirect('/login');
        }

        return $next($request);
    }
}
