<?php
declare(strict_types=1);

namespace app\middleware;

use Closure;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['admin_user'])) {
            return redirect(app_url_path('login'));
        }

        return $next($request);
    }
}
