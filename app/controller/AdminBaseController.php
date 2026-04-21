<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use think\facade\Session;
use think\facade\View;

abstract class AdminBaseController extends BaseController
{
    protected array $currentUser = [];

    protected function initialize(): void
    {
        $this->currentUser = Session::get('admin_user', []);
        View::assign('currentUser', $this->currentUser);
        View::assign('currentRoleLabel', $this->getRoleLabel($this->currentUser['role'] ?? ''));
        View::assign('canManageElders', $this->canManageElders());
    }

    protected function canManageElders(): bool
    {
        return in_array($this->currentUser['role'] ?? '', ['super_admin', 'operator'], true);
    }

    protected function authorizeRoles(array $roles): void
    {
        if (!in_array($this->currentUser['role'] ?? '', $roles, true)) {
            abort(403, '当前账号无权限执行该操作');
        }
    }

    protected function getRoleLabel(string $role): string
    {
        return match ($role) {
            'super_admin' => '超级管理员',
            'operator' => '运营人员',
            'viewer' => '只读账号',
            default => '未登录',
        };
    }
}
