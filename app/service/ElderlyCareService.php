<?php
declare(strict_types=1);

namespace app\service;

use app\model\Activity;
use app\model\CareStaff;
use app\model\Elder;
use app\model\HealthRecord;
use app\model\Notice;
use app\model\ServiceOrder;

class ElderlyCareService
{
    public function getDashboard(): array
    {
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
        $todayOrderCount = ServiceOrder::where('appoint_time', 'between', [$todayStart, $todayEnd])->count();
        $pendingOrderCount = ServiceOrder::whereIn('status', ['待分派', '待执行', '待出发', '配送中'])->count();
        $highRiskCount = HealthRecord::where('risk_level', '高')->count();
        $staffTotal = CareStaff::count();
        $staffOnDuty = CareStaff::where('status', '在岗')->count();

        $serviceStats = [];
        foreach (ServiceOrder::field('type, COUNT(*) AS total')->group('type')->order('total', 'desc')->limit(4)->select() as $row) {
            $serviceStats[] = [
                'label' => (string) $row->type,
                'value' => min(100, (int) $row->total * 12),
                'count' => (int) $row->total,
            ];
        }

        $notices = [];
        foreach (Notice::order('publish_time', 'desc')->limit(3)->select() as $notice) {
            $notices[] = [
                'title' => (string) $notice->title,
                'level' => (string) $notice->level,
                'time' => $this->formatDateTime((string) $notice->publish_time, 'm-d H:i'),
                'content' => (string) $notice->content,
            ];
        }

        $schedule = [];
        foreach (ServiceOrder::with(['elder'])->order('appoint_time', 'asc')->limit(4)->select() as $order) {
            $elderName = $order->elder ? (string) $order->elder->name : '待匹配老人';
            $schedule[] = [
                'time' => $this->formatDateTime((string) $order->appoint_time, 'H:i'),
                'task' => (string) $order->type . ' - ' . $elderName,
                'owner' => (string) $order->staff_name,
            ];
        }

        $alerts = [];
        foreach (HealthRecord::with(['elder'])->where('risk_level', '<>', '低')->order('recorded_at', 'desc')->limit(3)->select() as $record) {
            $alerts[] = [
                'name' => $record->elder ? (string) $record->elder->name : '未知老人',
                'type' => (string) $record->risk_level . '风险',
                'detail' => (string) $record->notes,
                'status' => (string) $record->risk_level === '高' ? '待跟进' : '已记录',
                'statusClass' => (string) $record->risk_level === '高' ? 'badge-warning' : 'badge-soft',
            ];
        }

        return [
            'summaryCards' => [
                ['label' => '服务老人', 'value' => (string) Elder::count(), 'trend' => '已接入 MySQL 档案库'],
                ['label' => '今日服务工单', 'value' => (string) $todayOrderCount, 'trend' => '待处理 ' . $pendingOrderCount . ' 单'],
                ['label' => '风险预警', 'value' => (string) $highRiskCount, 'trend' => '来源于健康监测记录'],
                ['label' => '护理人员', 'value' => (string) $staffTotal, 'trend' => '当班 ' . $staffOnDuty . ' 人'],
            ],
            'serviceStats' => $serviceStats,
            'notices' => $notices,
            'schedule' => $schedule,
            'alerts' => $alerts,
        ];
    }

    public function getElders(string $keyword = ''): array
    {
        $elders = [];
        foreach (Elder::order('update_time', 'desc')->select() as $elder) {
            if ($keyword !== '') {
                $haystack = implode(' ', [
                    (string) $elder->name,
                    (string) $elder->room,
                    (string) $elder->contact_name,
                    (string) $elder->tags,
                ]);
                if (mb_stripos($haystack, $keyword) === false) {
                    continue;
                }
            }
            $latestOrder = ServiceOrder::where('elder_id', $elder->id)->order('appoint_time', 'desc')->find();
            $elders[] = [
                'id' => (int) $elder->id,
                'name' => (string) $elder->name,
                'gender' => (string) $elder->gender,
                'age' => (int) $elder->age,
                'serviceMode' => (string) $elder->service_mode,
                'room' => (string) $elder->room,
                'contact' => (string) $elder->contact_name . ' ' . (string) $elder->contact_phone,
                'tags' => (string) $elder->tags,
                'status' => (string) $elder->status,
                'statusClass' => $this->mapElderStatusClass((string) $elder->status),
                'health' => (string) $elder->health_score,
                'lastService' => $latestOrder ? $this->formatDateTime((string) $latestOrder->appoint_time, 'm-d H:i') . ' ' . (string) $latestOrder->type : '暂无服务记录',
            ];
        }

        return [
            'elderStats' => [
                ['label' => '老人总数', 'value' => Elder::count()],
                ['label' => '重点关注', 'value' => Elder::where('status', '重点关注')->count()],
                ['label' => '随访中', 'value' => Elder::where('status', '随访中')->count()],
                ['label' => '稳定档案', 'value' => Elder::where('status', '稳定')->count()],
            ],
            'elders' => $elders,
        ];
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
            'healthRecords' => $healthRecords,
            'serviceOrders' => $serviceOrders,
        ];
    }

    public function getServiceOrders(): array
    {
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
            ];
        }

        return $orders;
    }

    public function getHealthRecords(): array
    {
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

        return $records;
    }

    public function getStaff(): array
    {
        $staffList = [];
        foreach (CareStaff::order('id', 'asc')->select() as $staff) {
            $staffList[] = [
                'name' => (string) $staff->name,
                'role' => (string) $staff->role,
                'shift' => (string) $staff->shift,
                'tasks' => (string) $staff->tasks,
                'phone' => (string) $staff->phone,
                'status' => (string) $staff->status,
                'statusClass' => (string) $staff->status === '在岗' ? 'badge-success' : 'badge-soft',
            ];
        }

        return $staffList;
    }

    public function getActivities(): array
    {
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

        return $activities;
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

    private function formatDateTime(string $value, string $format): string
    {
        if ($value === '') {
            return '-';
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return $value;
        }

        return date($format, $timestamp);
    }
}
