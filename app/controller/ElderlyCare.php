<?php
declare(strict_types=1);

namespace app\controller;

use app\model\Elder;
use app\service\ElderlyCareService;
use think\facade\View;

class ElderlyCare extends AdminBaseController
{
    private ElderlyCareService $service;

    protected function initialize(): void
    {
        parent::initialize();
        $this->service = new ElderlyCareService();
    }

    public function dashboard()
    {
        return $this->renderPage('dashboard/index', '运营概览', $this->service->getDashboard());
    }

    public function elders()
    {
        $keyword = trim((string) $this->request->get('keyword', ''));

        return $this->renderPage('elder/index', '老人档案', array_merge(
            $this->service->getElders($keyword),
            ['keyword' => $keyword]
        ));
    }

    public function show(int $id)
    {
        $detail = $this->service->getElderDetail($id);
        if ($detail === null) {
            abort(404, '老人档案不存在');
        }

        return $this->renderPage('elder/show', '老人详情', $detail);
    }

    public function create()
    {
        $this->authorizeRoles(['super_admin', 'operator']);

        return $this->renderPage('elder/form', '新增老人档案', $this->buildFormViewData('/elders/create', '新建档案'));
    }

    public function store()
    {
        $this->authorizeRoles(['super_admin', 'operator']);
        $payload = $this->collectElderPayload();
        $this->validateElderPayload($payload);
        $elder = $this->service->saveElder($payload);

        return redirect('/elders/' . $elder->id);
    }

    public function edit(int $id)
    {
        $this->authorizeRoles(['super_admin', 'operator']);
        $elder = $this->service->getElderModel($id);
        if ($elder === null) {
            abort(404, '老人档案不存在');
        }

        return $this->renderPage('elder/form', '编辑老人档案', $this->buildFormViewData('/elders/' . $id . '/edit', '保存修改', $elder));
    }

    public function update(int $id)
    {
        $this->authorizeRoles(['super_admin', 'operator']);
        $elder = $this->service->getElderModel($id);
        if ($elder === null) {
            abort(404, '老人档案不存在');
        }

        $payload = $this->collectElderPayload();
        $this->validateElderPayload($payload);
        $this->service->saveElder($payload, $elder);

        return redirect('/elders/' . $elder->id);
    }

    public function services()
    {
        return $this->renderPage('service/index', '服务工单', [
            'orders' => $this->service->getServiceOrders(),
        ]);
    }

    public function health()
    {
        return $this->renderPage('health/index', '健康监测', [
            'records' => $this->service->getHealthRecords(),
        ]);
    }

    public function staff()
    {
        return $this->renderPage('staff/index', '护理排班', [
            'staffList' => $this->service->getStaff(),
        ]);
    }

    public function activities()
    {
        return $this->renderPage('activity/index', '活动通知', [
            'activities' => $this->service->getActivities(),
        ]);
    }

    private function renderPage(string $template, string $pageTitle, array $data = [])
    {
        $activeNav = strtolower($this->request->action());
        if (in_array($activeNav, ['create', 'store', 'edit', 'update', 'show'], true)) {
            $activeNav = 'elders';
        }

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

    private function buildFormViewData(string $formAction, string $submitLabel, ?Elder $elder = null): array
    {
        $defaults = [
            'id' => 0,
            'name' => '',
            'gender' => '女',
            'age' => 70,
            'phone' => '',
            'id_card' => '',
            'service_mode' => '机构养老',
            'room' => '',
            'address' => '',
            'contact_name' => '',
            'contact_phone' => '',
            'tags' => '',
            'care_level' => '二级护理',
            'health_score' => 85,
            'status' => '稳定',
            'remark' => '',
        ];

        return [
            'formAction' => $formAction,
            'submitLabel' => $submitLabel,
            'elder' => $elder ? array_merge($defaults, $elder->toArray()) : $defaults,
            'genderOptions' => ['男', '女'],
            'serviceModeOptions' => ['机构养老', '社区居家', '居家上门'],
            'careLevelOptions' => ['一级护理', '二级护理', '三级护理', '康复观察'],
            'statusOptions' => ['稳定', '随访中', '重点关注'],
        ];
    }

    private function collectElderPayload(): array
    {
        return [
            'name' => trim((string) $this->request->post('name')),
            'gender' => (string) $this->request->post('gender'),
            'age' => (int) $this->request->post('age'),
            'phone' => trim((string) $this->request->post('phone')),
            'id_card' => trim((string) $this->request->post('id_card')),
            'service_mode' => (string) $this->request->post('service_mode'),
            'room' => trim((string) $this->request->post('room')),
            'address' => trim((string) $this->request->post('address')),
            'contact_name' => trim((string) $this->request->post('contact_name')),
            'contact_phone' => trim((string) $this->request->post('contact_phone')),
            'tags' => trim((string) $this->request->post('tags')),
            'care_level' => (string) $this->request->post('care_level'),
            'health_score' => (int) $this->request->post('health_score'),
            'status' => (string) $this->request->post('status'),
            'remark' => trim((string) $this->request->post('remark')),
        ];
    }

    private function validateElderPayload(array $payload): void
    {
        $this->validate($payload, [
            'name' => 'require|max:50',
            'gender' => 'require|in:男,女',
            'age' => 'require|integer|between:50,120',
            'phone' => 'require|max:20',
            'service_mode' => 'require|max:30',
            'room' => 'require|max:100',
            'address' => 'require|max:255',
            'contact_name' => 'require|max:50',
            'contact_phone' => 'require|max:20',
            'tags' => 'max:255',
            'care_level' => 'require|max:30',
            'health_score' => 'require|integer|between:0,100',
            'status' => 'require|max:30',
            'remark' => 'max:1000',
        ], [
            'name.require' => '请填写老人姓名',
            'room.require' => '请填写居住位置',
            'address.require' => '请填写服务地址',
            'contact_name.require' => '请填写联系人',
            'contact_phone.require' => '请填写联系人电话',
        ]);
    }
}
