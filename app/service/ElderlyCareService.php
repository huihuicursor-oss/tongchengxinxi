<?php
declare(strict_types=1);

namespace app\service;

use app\model\Activity;
use app\model\BillingRecord;
use app\model\CareStaff;
use app\model\CareTask;
use app\model\Elder;
use app\model\FamilyContact;
use app\model\HealthRecord;
use app\model\MedicationPlan;
use app\model\Notice;
use app\model\ServiceOrder;

class ElderlyCareService
{
    public function getDashboard(): array
    {
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $elderCount = Elder::count();
        $activeOrders = ServiceOrder::whereIn('status', ['待分派', '待执行', '待出发', '配送中'])->count();
        $pendingTasks = CareTask::whereIn('status', ['待执行', '进行中', '待出发'])->count();
        $pendingMeds = MedicationPlan::whereIn('status', ['待执行', '待核对', '待发药'])->count();
        $riskAlerts = HealthRecord::where('risk_level', '高')->count();
        $unpaidBills = (float) BillingRecord::whereIn('pay_status', ['待支付', '部分支付', '待审核'])->sum('amount');

        $summaryCards = [
            ['label' => '在册老人', 'value' => (string) $elderCount, 'trend' => '重点关注 ' . Elder::where('status', '重点关注')->count() . ' 人', 'icon' => 'elder'],
            ['label' => '待办工单', 'value' => (string) $activeOrders, 'trend' => '服务订单持续处理中', 'icon' => 'service'],
            ['label' => '护理任务', 'value' => (string) $pendingTasks, 'trend' => '今日需执行护理事项', 'icon' => 'care'],
            ['label' => '用药提醒', 'value' => (string) $pendingMeds, 'trend' => '待核对/待发药任务', 'icon' => 'medicine'],
            ['label' => '风险预警', 'value' => (string) $riskAlerts, 'trend' => '高风险健康告警', 'icon' => 'warning'],
            ['label' => '待收账款', 'value' => '¥' . number_format($unpaidBills, 0), 'trend' => '含护理/助餐/长护险差额', 'icon' => 'billing'],
        ];

        $quickEntries = [
            ['label' => '新增老人档案', 'count' => '档案录入', 'url' => app_url_path('elders/create'), 'theme' => 'blue'],
            ['label' => '护理执行看板', 'count' => '任务闭环', 'url' => app_url_path('care-tasks'), 'theme' => 'cyan'],
            ['label' => '用药提醒中心', 'count' => '发药核对', 'url' => app_url_path('medications'), 'theme' => 'orange'],
            ['label' => '家属联络台账', 'count' => '关系维护', 'url' => app_url_path('families'), 'theme' => 'purple'],
        ];

        $serviceOverview = [];
        foreach (ServiceOrder::field('type, COUNT(*) AS total')->group('type')->order('total', 'desc')->limit(5)->select() as $row) {
            $serviceOverview[] = [
                'label' => (string) $row->type,
                'value' => (int) $row->total,
                'percent' => min(100, (int) $row->total * 15),
            ];
        }

        $careBoard = [];
        foreach (CareTask::with(['elder'])->order('schedule_time', 'asc')->limit(6)->select() as $task) {
            $careBoard[] = [
                'time' => $this->formatDateTime((string) $task->schedule_time, 'H:i'),
                'elder' => $task->elder ? (string) $task->elder->name : '未知老人',
                'task' => (string) $task->task_type,
                'ward' => (string) $task->ward . ' / ' . (string) $task->bed_no,
                'executor' => (string) $task->executor,
                'status' => (string) $task->status,
                'statusClass' => $this->mapTaskStatusClass((string) $task->status),
            ];
        }

        $medicationAlerts = [];
        foreach (MedicationPlan::with(['elder'])->order('schedule_time', 'asc')->limit(5)->select() as $plan) {
            $medicationAlerts[] = [
                'time' => $this->formatDateTime((string) $plan->schedule_time, 'm-d H:i'),
                'elder' => $plan->elder ? (string) $plan->elder->name : '未知老人',
                'medicine' => (string) $plan->medicine_name,
                'dosage' => (string) $plan->dosage,
                'executor' => (string) $plan->executor,
                'status' => (string) $plan->status,
                'statusClass' => $this->mapMedicationStatusClass((string) $plan->status),
            ];
        }

        $noticeFeed = [];
        foreach (Notice::order('publish_time', 'desc')->limit(4)->select() as $notice) {
            $noticeFeed[] = [
                'title' => (string) $notice->title,
                'level' => (string) $notice->level,
                'time' => $this->formatDateTime((string) $notice->publish_time, 'm-d H:i'),
                'content' => (string) $notice->content,
            ];
        }

        $billingOverview = [];
        foreach (BillingRecord::with(['elder'])->order('due_date', 'asc')->limit(4)->select() as $bill) {
            $billingOverview[] = [
                'elder' => $bill->elder ? (string) $bill->elder->name : '未知老人',
                'item' => (string) $bill->item_name,
                'amount' => '¥' . number_format((float) $bill->amount, 2),
                'status' => (string) $bill->pay_status,
                'statusClass' => $this->mapBillingStatusClass((string) $bill->pay_status),
                'dueDate' => $this->formatDateTime((string) $bill->due_date, 'm-d'),
            ];
        }

        return compact(
            'summaryCards',
            'quickEntries',
            'serviceOverview',
            'careBoard',
            'medicationAlerts',
            'noticeFeed',
            'billingOverview'
        );
    }

    public function getElders(string $keyword = ''): array
    {
        $rows = [];
        foreach (Elder::order('update_time', 'desc')->select() as $elder) {
            if ($keyword !== '') {
                $haystack = implode(' ', [(string) $elder->name, (string) $elder->room, (string) $elder->contact_name, (string) $elder->tags]);
                if (mb_stripos($haystack, $keyword) === false) {
                    continue;
                }
            }

            $primaryFamily = FamilyContact::where('elder_id', $elder->id)->where('is_primary', 1)->find();
            $latestOrder = ServiceOrder::where('elder_id', $elder->id)->order('appoint_time', 'desc')->find();
            $rows[] = [
                'id' => (int) $elder->id,
                'name' => (string) $elder->name,
                'gender' => (string) $elder->gender,
                'age' => (int) $elder->age,
                'serviceMode' => (string) $elder->service_mode,
                'room' => (string) $elder->room,
                'careLevel' => (string) $elder->care_level,
                'family' => $primaryFamily ? (string) $primaryFamily->name . ' / ' . (string) $primaryFamily->relation : (string) $elder->contact_name,
                'phone' => $primaryFamily ? (string) $primaryFamily->phone : (string) $elder->contact_phone,
                'tags' => (string) $elder->tags,
                'status' => (string) $elder->status,
                'statusClass' => $this->mapElderStatusClass((string) $elder->status),
                'health' => (int) $elder->health_score,
                'lastService' => $latestOrder ? $this->formatDateTime((string) $latestOrder->appoint_time, 'm-d H:i') . ' ' . (string) $latestOrder->type : '暂无服务',
            ];
        }

        $elderStats = [
            ['label' => '在册老人', 'value' => Elder::count(), 'desc' => '机构与居家总人数'],
            ['label' => '重点照护', 'value' => Elder::where('status', '重点关注')->count(), 'desc' => '需优先巡检与回访'],
            ['label' => '慢病管理', 'value' => Elder::whereLike('tags', '%糖尿病%')->count() + Elder::whereLike('tags', '%高血压%')->count(), 'desc' => '高血压 / 糖尿病'],
            ['label' => '家属高关注', 'value' => FamilyContact::where('concern_level', '高关注')->count(), 'desc' => '需及时同步汇报'],
        ];

        return ['elderStats' => $elderStats, 'elders' => $rows];
    }

    public function getElderModel(int $id): ?Elder
    {
        return Elder::find($id);
    }

    public function saveElder(array $payload, ?Elder $elder = null): Elder
    {
        $elder = $elder ?? new Elder();
        $elder->save($payload);
        return $elder;
    }

    public function getElderDetail(int $id): ?array
    {
        $elder = Elder::find($id);
        if ($elder === null) {
            return null;
        }

        $familyContacts = [];
        foreach (FamilyContact::where('elder_id', $id)->order('is_primary', 'desc')->select() as $family) {
            $familyContacts[] = [
                'name' => (string) $family->name,
                'relation' => (string) $family->relation,
                'phone' => (string) $family->phone,
                'wechat' => (string) $family->wechat,
                'concernLevel' => (string) $family->concern_level,
                'lastContact' => $this->formatDateTime((string) $family->last_contact_at, 'Y-m-d H:i'),
                'remark' => (string) $family->remark,
                'primary' => (int) $family->is_primary === 1,
            ];
        }

        $medicationPlans = [];
        foreach (MedicationPlan::where('elder_id', $id)->order('schedule_time', 'asc')->limit(5)->select() as $plan) {
            $medicationPlans[] = [
                'medicine' => (string) $plan->medicine_name,
                'dosage' => (string) $plan->dosage,
                'frequency' => (string) $plan->frequency,
                'scheduleTime' => $this->formatDateTime((string) $plan->schedule_time, 'm-d H:i'),
                'executor' => (string) $plan->executor,
                'status' => (string) $plan->status,
                'statusClass' => $this->mapMedicationStatusClass((string) $plan->status),
                'notes' => (string) $plan->notes,
            ];
        }

        $careTasks = [];
        foreach (CareTask::where('elder_id', $id)->order('schedule_time', 'asc')->limit(5)->select() as $task) {
            $careTasks[] = [
                'type' => (string) $task->task_type,
                'ward' => (string) $task->ward,
                'bedNo' => (string) $task->bed_no,
                'scheduleTime' => $this->formatDateTime((string) $task->schedule_time, 'm-d H:i'),
                'executor' => (string) $task->executor,
                'priority' => (string) $task->priority,
                'status' => (string) $task->status,
                'statusClass' => $this->mapTaskStatusClass((string) $task->status),
                'notes' => (string) $task->notes,
            ];
        }

        $healthRecords = [];
        foreach (HealthRecord::where('elder_id', $id)->order('recorded_at', 'desc')->limit(5)->select() as $record) {
            $healthRecords[] = [
                'bloodPressure' => (string) $record->blood_pressure,
                'bloodSugar' => (string) $record->blood_sugar,
                'heartRate' => (string) $record->heart_rate,
                'sleep' => (string) $record->sleep_hours . ' 小时',
                'risk' => (string) $record->risk_level,
                'riskClass' => $this->mapRiskClass((string) $record->risk_level),
                'recordedAt' => $this->formatDateTime((string) $record->recorded_at, 'Y-m-d H:i'),
                'notes' => (string) $record->notes,
            ];
        }

        $serviceOrders = [];
        foreach (ServiceOrder::where('elder_id', $id)->order('appoint_time', 'desc')->limit(6)->select() as $order) {
            $serviceOrders[] = [
                'code' => (string) $order->code,
                'type' => (string) $order->type,
                'appointTime' => $this->formatDateTime((string) $order->appoint_time, 'Y-m-d H:i'),
                'staff' => (string) $order->staff_name,
                'status' => (string) $order->status,
                'statusClass' => $this->mapOrderStatusClass((string) $order->status),
                'address' => (string) $order->address,
                'notes' => (string) $order->notes,
            ];
        }

        $billingRecords = [];
        foreach (BillingRecord::where('elder_id', $id)->order('due_date', 'desc')->limit(4)->select() as $bill) {
            $billingRecords[] = [
                'month' => (string) $bill->bill_month,
                'item' => (string) $bill->item_name,
                'amount' => '¥' . number_format((float) $bill->amount, 2),
                'status' => (string) $bill->pay_status,
                'statusClass' => $this->mapBillingStatusClass((string) $bill->pay_status),
                'dueDate' => $this->formatDateTime((string) $bill->due_date, 'Y-m-d'),
                'remark' => (string) $bill->remark,
            ];
        }

        return [
            'elder' => [
                'id' => (int) $elder->id,
                'name' => (string) $elder->name,
                'gender' => (string) $elder->gender,
                'age' => (int) $elder->age,
                'phone' => (string) $elder->phone,
                'idCard' => (string) $elder->id_card,
                'serviceMode' => (string) $elder->service_mode,
                'room' => (string) $elder->room,
                'address' => (string) $elder->address,
                'contactName' => (string) $elder->contact_name,
                'contactPhone' => (string) $elder->contact_phone,
                'tags' => (string) $elder->tags,
                'careLevel' => (string) $elder->care_level,
                'healthScore' => (int) $elder->health_score,
                'status' => (string) $elder->status,
                'statusClass' => $this->mapElderStatusClass((string) $elder->status),
                'remark' => (string) $elder->remark,
                'updatedAt' => $this->formatDateTime((string) $elder->update_time, 'Y-m-d H:i'),
            ],
            'familyContacts' => $familyContacts,
            'medicationPlans' => $medicationPlans,
            'careTasks' => $careTasks,
            'healthRecords' => $healthRecords,
            'serviceOrders' => $serviceOrders,
            'billingRecords' => $billingRecords,
        ];
    }

    public function getServiceOrders(): array
    {
        $summary = [
            ['label' => '全部工单', 'value' => ServiceOrder::count(), 'theme' => 'blue'],
            ['label' => '待分派', 'value' => ServiceOrder::where('status', '待分派')->count(), 'theme' => 'orange'],
            ['label' => '进行中', 'value' => ServiceOrder::whereIn('status', ['待执行', '待出发', '配送中'])->count(), 'theme' => 'cyan'],
            ['label' => '已完成', 'value' => ServiceOrder::where('status', '已完成')->count(), 'theme' => 'green'],
        ];

        $orders = [];
        foreach (ServiceOrder::with(['elder'])->order('appoint_time', 'desc')->select() as $order) {
            $orders[] = [
                'code' => (string) $order->code,
                'elderId' => $order->elder ? (int) $order->elder->id : 0,
                'name' => $order->elder ? (string) $order->elder->name : '待匹配',
                'type' => (string) $order->type,
                'address' => (string) $order->address,
                'appointTime' => $this->formatDateTime((string) $order->appoint_time, 'Y-m-d H:i'),
                'staff' => (string) $order->staff_name,
                'status' => (string) $order->status,
                'statusClass' => $this->mapOrderStatusClass((string) $order->status),
                'notes' => (string) $order->notes,
            ];
        }

        return ['summary' => $summary, 'orders' => $orders];
    }

    public function getHealthRecords(): array
    {
        $healthStats = [
            ['label' => '高风险', 'value' => HealthRecord::where('risk_level', '高')->count(), 'theme' => 'orange'],
            ['label' => '中风险', 'value' => HealthRecord::where('risk_level', '中')->count(), 'theme' => 'cyan'],
            ['label' => '低风险', 'value' => HealthRecord::where('risk_level', '低')->count(), 'theme' => 'green'],
            ['label' => '慢病老人', 'value' => Elder::whereLike('tags', '%糖尿病%')->count() + Elder::whereLike('tags', '%高血压%')->count(), 'theme' => 'purple'],
        ];

        $records = [];
        foreach (HealthRecord::with(['elder'])->order('recorded_at', 'desc')->select() as $record) {
            $records[] = [
                'elderId' => $record->elder ? (int) $record->elder->id : 0,
                'name' => $record->elder ? (string) $record->elder->name : '未知老人',
                'bloodPressure' => (string) $record->blood_pressure,
                'bloodSugar' => (string) $record->blood_sugar,
                'heartRate' => (string) $record->heart_rate,
                'sleep' => (string) $record->sleep_hours,
                'risk' => (string) $record->risk_level,
                'riskClass' => $this->mapRiskClass((string) $record->risk_level),
                'updatedAt' => $this->formatDateTime((string) $record->recorded_at, 'Y-m-d H:i'),
                'notes' => (string) $record->notes,
            ];
        }

        return ['healthStats' => $healthStats, 'records' => $records];
    }

    public function getStaff(): array
    {
        $shiftStats = [
            ['label' => '护理人员', 'value' => CareStaff::count(), 'theme' => 'blue'],
            ['label' => '在岗', 'value' => CareStaff::where('status', '在岗')->count(), 'theme' => 'green'],
            ['label' => '外勤', 'value' => CareStaff::where('status', '外勤')->count(), 'theme' => 'cyan'],
            ['label' => '待分派', 'value' => CareStaff::where('status', '待分派')->count(), 'theme' => 'orange'],
        ];

        $staffList = [];
        foreach (CareStaff::order('id', 'asc')->select() as $staff) {
            $staffList[] = [
                'name' => (string) $staff->name,
                'role' => (string) $staff->role,
                'shift' => (string) $staff->shift,
                'tasks' => (string) $staff->tasks,
                'phone' => (string) $staff->phone,
                'status' => (string) $staff->status,
                'statusClass' => (string) $staff->status === '在岗' ? 'badge-success' : ((string) $staff->status === '外勤' ? 'badge-soft' : 'badge-warning'),
            ];
        }

        return ['shiftStats' => $shiftStats, 'staffList' => $staffList];
    }

    public function getActivities(): array
    {
        $activityStats = [
            ['label' => '活动总数', 'value' => Activity::count(), 'theme' => 'blue'],
            ['label' => '报名中', 'value' => Activity::where('status', '报名中')->count(), 'theme' => 'orange'],
            ['label' => '筹备中', 'value' => Activity::where('status', '筹备中')->count(), 'theme' => 'cyan'],
            ['label' => '待开展', 'value' => Activity::where('status', '待开展')->count(), 'theme' => 'green'],
        ];

        $activities = [];
        foreach (Activity::order('activity_date', 'asc')->select() as $activity) {
            $activities[] = [
                'title' => (string) $activity->title,
                'date' => $this->formatDateTime((string) $activity->activity_date, 'm-d H:i'),
                'location' => (string) $activity->location,
                'participants' => (string) $activity->participants,
                'owner' => (string) $activity->owner,
                'status' => (string) $activity->status,
                'description' => (string) $activity->description,
            ];
        }

        return ['activityStats' => $activityStats, 'activities' => $activities];
    }

    public function getCareTasks(): array
    {
        $taskStats = [
            ['label' => '今日护理任务', 'value' => CareTask::count(), 'theme' => 'blue'],
            ['label' => '进行中', 'value' => CareTask::where('status', '进行中')->count(), 'theme' => 'cyan'],
            ['label' => '待执行', 'value' => CareTask::where('status', '待执行')->count(), 'theme' => 'orange'],
            ['label' => '已完成', 'value' => CareTask::where('status', '已完成')->count(), 'theme' => 'green'],
        ];

        $tasks = [];
        foreach (CareTask::with(['elder'])->order('schedule_time', 'asc')->select() as $task) {
            $tasks[] = [
                'elderId' => $task->elder ? (int) $task->elder->id : 0,
                'elder' => $task->elder ? (string) $task->elder->name : '未知老人',
                'taskType' => (string) $task->task_type,
                'ward' => (string) $task->ward,
                'bedNo' => (string) $task->bed_no,
                'scheduleTime' => $this->formatDateTime((string) $task->schedule_time, 'Y-m-d H:i'),
                'executor' => (string) $task->executor,
                'priority' => (string) $task->priority,
                'priorityClass' => $this->mapPriorityClass((string) $task->priority),
                'status' => (string) $task->status,
                'statusClass' => $this->mapTaskStatusClass((string) $task->status),
                'notes' => (string) $task->notes,
            ];
        }

        return ['taskStats' => $taskStats, 'tasks' => $tasks];
    }

    public function getMedicationPlans(): array
    {
        $medStats = [
            ['label' => '药品计划', 'value' => MedicationPlan::count(), 'theme' => 'blue'],
            ['label' => '待核对', 'value' => MedicationPlan::where('status', '待核对')->count(), 'theme' => 'orange'],
            ['label' => '待发药', 'value' => MedicationPlan::where('status', '待发药')->count(), 'theme' => 'cyan'],
            ['label' => '已完成', 'value' => MedicationPlan::where('status', '已完成')->count(), 'theme' => 'green'],
        ];

        $plans = [];
        foreach (MedicationPlan::with(['elder'])->order('schedule_time', 'asc')->select() as $plan) {
            $plans[] = [
                'elderId' => $plan->elder ? (int) $plan->elder->id : 0,
                'elder' => $plan->elder ? (string) $plan->elder->name : '未知老人',
                'medicine' => (string) $plan->medicine_name,
                'dosage' => (string) $plan->dosage,
                'frequency' => (string) $plan->frequency,
                'scheduleTime' => $this->formatDateTime((string) $plan->schedule_time, 'Y-m-d H:i'),
                'executor' => (string) $plan->executor,
                'status' => (string) $plan->status,
                'statusClass' => $this->mapMedicationStatusClass((string) $plan->status),
                'notes' => (string) $plan->notes,
            ];
        }

        return ['medStats' => $medStats, 'plans' => $plans];
    }

    public function getFamilyContacts(): array
    {
        $familyStats = [
            ['label' => '家属联系人', 'value' => FamilyContact::count(), 'theme' => 'blue'],
            ['label' => '高关注', 'value' => FamilyContact::where('concern_level', '高关注')->count(), 'theme' => 'orange'],
            ['label' => '重点', 'value' => FamilyContact::where('concern_level', '重点')->count(), 'theme' => 'cyan'],
            ['label' => '主联系人', 'value' => FamilyContact::where('is_primary', 1)->count(), 'theme' => 'green'],
        ];

        $contacts = [];
        foreach (FamilyContact::with(['elder'])->order('last_contact_at', 'desc')->select() as $family) {
            $contacts[] = [
                'elderId' => $family->elder ? (int) $family->elder->id : 0,
                'elder' => $family->elder ? (string) $family->elder->name : '未知老人',
                'name' => (string) $family->name,
                'relation' => (string) $family->relation,
                'phone' => (string) $family->phone,
                'wechat' => (string) $family->wechat,
                'primary' => (int) $family->is_primary === 1,
                'concernLevel' => (string) $family->concern_level,
                'lastContact' => $this->formatDateTime((string) $family->last_contact_at, 'Y-m-d H:i'),
                'remark' => (string) $family->remark,
            ];
        }

        return ['familyStats' => $familyStats, 'contacts' => $contacts];
    }

    public function getBillingRecords(): array
    {
        $billingStats = [
            ['label' => '账单记录', 'value' => BillingRecord::count(), 'theme' => 'blue'],
            ['label' => '待支付', 'value' => BillingRecord::where('pay_status', '待支付')->count(), 'theme' => 'orange'],
            ['label' => '部分支付', 'value' => BillingRecord::where('pay_status', '部分支付')->count(), 'theme' => 'cyan'],
            ['label' => '已支付', 'value' => BillingRecord::where('pay_status', '已支付')->count(), 'theme' => 'green'],
        ];

        $records = [];
        foreach (BillingRecord::with(['elder'])->order('due_date', 'asc')->select() as $bill) {
            $records[] = [
                'elderId' => $bill->elder ? (int) $bill->elder->id : 0,
                'elder' => $bill->elder ? (string) $bill->elder->name : '未知老人',
                'month' => (string) $bill->bill_month,
                'item' => (string) $bill->item_name,
                'amount' => '¥' . number_format((float) $bill->amount, 2),
                'status' => (string) $bill->pay_status,
                'statusClass' => $this->mapBillingStatusClass((string) $bill->pay_status),
                'payMethod' => (string) ($bill->pay_method ?: '-'),
                'dueDate' => $this->formatDateTime((string) $bill->due_date, 'Y-m-d'),
                'operator' => (string) $bill->operator_name,
                'remark' => (string) $bill->remark,
            ];
        }

        return ['billingStats' => $billingStats, 'records' => $records];
    }

    private function mapOrderStatusClass(string $status): string
    {
        return match ($status) {
            '已完成' => 'badge-success',
            '待分派', '待执行', '待出发' => 'badge-warning',
            default => 'badge-soft',
        };
    }

    private function mapElderStatusClass(string $status): string
    {
        return match ($status) {
            '重点关注' => 'badge-warning',
            '稳定' => 'badge-success',
            default => 'badge-soft',
        };
    }

    private function mapRiskClass(string $risk): string
    {
        return match ($risk) {
            '高' => 'badge-warning',
            '低' => 'badge-success',
            default => 'badge-soft',
        };
    }

    private function mapTaskStatusClass(string $status): string
    {
        return match ($status) {
            '已完成' => 'badge-success',
            '进行中' => 'badge-soft',
            default => 'badge-warning',
        };
    }

    private function mapMedicationStatusClass(string $status): string
    {
        return match ($status) {
            '已完成' => 'badge-success',
            '待执行' => 'badge-soft',
            default => 'badge-warning',
        };
    }

    private function mapBillingStatusClass(string $status): string
    {
        return match ($status) {
            '已支付' => 'badge-success',
            '部分支付' => 'badge-soft',
            default => 'badge-warning',
        };
    }

    private function mapPriorityClass(string $priority): string
    {
        return match ($priority) {
            '高' => 'badge-warning',
            '中' => 'badge-soft',
            default => 'badge-success',
        };
    }

    private function formatDateTime(string $value, string $format): string
    {
        if ($value === '') {
            return '-';
        }
        $timestamp = strtotime($value);
        return $timestamp === false ? $value : date($format, $timestamp);
    }
}
