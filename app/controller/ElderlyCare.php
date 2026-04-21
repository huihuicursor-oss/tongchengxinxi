<?php
declare(strict_types=1);

namespace app\controller;

use app\BaseController;
use app\service\ElderlyCareData;
use think\facade\View;

class ElderlyCare extends BaseController
{
    private ElderlyCareData $dataService;

    protected function initialize(): void
    {
        $this->dataService = new ElderlyCareData();
    }

    public function index()
    {
        return redirect('/dashboard');
    }

    public function dashboard()
    {
        $data = $this->dataService->getOverview();
        $data['alerts'] = array_map(function (array $alert): array {
            $alert['statusClass'] = $alert['status'] === '已处理' ? 'badge-success' : 'badge-warning';
            return $alert;
        }, $data['alerts']);

        return $this->renderPage('dashboard/index', '运营概览', [
            'summaryCards' => $data['summaryCards'],
            'serviceStats' => $data['serviceStats'],
            'notices' => $data['notices'],
            'schedule' => $data['schedule'],
            'alerts' => $data['alerts'],
        ]);
    }

    public function elders()
    {
        $elders = array_map(function (array $elder): array {
            $elder['statusClass'] = $elder['status'] === '重点关注' ? 'badge-warning' : 'badge-soft';
            return $elder;
        }, $this->dataService->getElders());

        return $this->renderPage('elder/index', '老人档案', [
            'elders' => $elders,
        ]);
    }

    public function services()
    {
        $orders = array_map(function (array $order): array {
            $order['statusClass'] = match ($order['status']) {
                '已完成' => 'badge-success',
                '待分派', '待执行' => 'badge-warning',
                default => 'badge-soft',
            };
            return $order;
        }, $this->dataService->getServiceOrders());

        return $this->renderPage('service/index', '服务工单', [
            'orders' => $orders,
        ]);
    }

    public function health()
    {
        $records = array_map(function (array $record): array {
            $record['riskClass'] = match ($record['risk']) {
                '高' => 'badge-warning',
                '低' => 'badge-success',
                default => 'badge-soft',
            };
            return $record;
        }, $this->dataService->getHealthRecords());

        return $this->renderPage('health/index', '健康监测', [
            'records' => $records,
        ]);
    }

    public function staff()
    {
        $staffList = array_map(function (array $staff): array {
            $staff['statusClass'] = $staff['status'] === '在岗' ? 'badge-success' : 'badge-soft';
            return $staff;
        }, $this->dataService->getStaff());

        return $this->renderPage('staff/index', '护理排班', [
            'staffList' => $staffList,
        ]);
    }

    public function activities()
    {
        return $this->renderPage('activity/index', '活动通知', [
            'activities' => $this->dataService->getActivities(),
        ]);
    }

    private function renderPage(string $template, string $pageTitle, array $data = [])
    {
        $activeNav = strtolower($this->request->action());
        $navigation = [
            ['key' => 'dashboard', 'label' => '运营概览', 'url' => '/dashboard', 'activeClass' => $activeNav === 'dashboard' ? 'active' : ''],
            ['key' => 'elders', 'label' => '老人档案', 'url' => '/elders', 'activeClass' => $activeNav === 'elders' ? 'active' : ''],
            ['key' => 'services', 'label' => '服务工单', 'url' => '/services', 'activeClass' => $activeNav === 'services' ? 'active' : ''],
            ['key' => 'health', 'label' => '健康监测', 'url' => '/health', 'activeClass' => $activeNav === 'health' ? 'active' : ''],
            ['key' => 'staff', 'label' => '护理排班', 'url' => '/staff', 'activeClass' => $activeNav === 'staff' ? 'active' : ''],
            ['key' => 'activities', 'label' => '活动通知', 'url' => '/activities', 'activeClass' => $activeNav === 'activities' ? 'active' : ''],
        ];

        View::assign(array_merge([
            'systemTitle' => '智慧养老服务管理平台',
            'pageTitle' => $pageTitle,
            'navigation' => $navigation,
        ], $data));

        return View::fetch($template);
    }
}
