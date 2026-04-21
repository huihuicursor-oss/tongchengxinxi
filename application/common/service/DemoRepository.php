<?php

namespace app\common\service;

class DemoRepository
{
    public function getDashboard(): array
    {
        return [
            'stats' => [
                ['label' => '在院老人', 'value' => 128, 'unit' => '人', 'trend' => '+6'],
                ['label' => '护理人员', 'value' => 36, 'unit' => '人', 'trend' => '+2'],
                ['label' => '今日告警', 'value' => 5, 'unit' => '条', 'trend' => '-1'],
                ['label' => '待执行服务', 'value' => 18, 'unit' => '项', 'trend' => '+4'],
            ],
            'notices' => [
                '二号楼 308 房血压异常，请责任护士 10 分钟内复核。',
                '本周三开展老年慢病健康讲座，请前台完成活动通知。',
                '探访高峰时段为 14:00 - 17:00，请门岗提前核验预约信息。',
            ],
            'alerts' => [
                ['name' => '王淑珍', 'room' => 'A-203', 'type' => '心率偏高', 'level' => '高', 'time' => '08:40'],
                ['name' => '李建国', 'room' => 'B-112', 'type' => '血糖偏低', 'level' => '中', 'time' => '09:05'],
                ['name' => '周桂芳', 'room' => 'C-305', 'type' => '离床未归', 'level' => '中', 'time' => '09:12'],
            ],
            'services' => [
                ['service' => '晨间巡房', 'owner' => '护工-张敏', 'status' => '执行中'],
                ['service' => '康复训练', 'owner' => '康复师-赵琳', 'status' => '待开始'],
                ['service' => '午间送餐', 'owner' => '后勤-王涛', 'status' => '已排班'],
                ['service' => '家属视频连线', 'owner' => '客服-陈静', 'status' => '待确认'],
            ],
        ];
    }

    public function getElderStats(): array
    {
        return [
            ['label' => '总入住', 'value' => 128, 'unit' => '人', 'tone' => 'info'],
            ['label' => '一级护理', 'value' => 32, 'unit' => '人', 'tone' => 'warning'],
            ['label' => '失能照护', 'value' => 14, 'unit' => '人', 'tone' => 'danger'],
            ['label' => '空余床位', 'value' => 21, 'unit' => '张', 'tone' => 'success'],
        ];
    }

    public function getElderProfiles(): array
    {
        return [
            ['name' => '王淑珍', 'gender' => '女', 'age' => 82, 'room' => 'A-203', 'level' => '二级护理', 'contact' => '王磊 13800000001', 'tags' => '高血压 / 夜间巡查'],
            ['name' => '李建国', 'gender' => '男', 'age' => 76, 'room' => 'B-112', 'level' => '一级护理', 'contact' => '李婷 13800000002', 'tags' => '糖尿病 / 饮食控制'],
            ['name' => '周桂芳', 'gender' => '女', 'age' => 79, 'room' => 'C-305', 'level' => '失能照护', 'contact' => '周洋 13800000003', 'tags' => '离床预警 / 24h陪护'],
            ['name' => '陈德明', 'gender' => '男', 'age' => 84, 'room' => 'A-107', 'level' => '康复观察', 'contact' => '陈雪 13800000004', 'tags' => '术后恢复 / 步态训练'],
            ['name' => '孙玉兰', 'gender' => '女', 'age' => 73, 'room' => 'B-208', 'level' => '慢病管理', 'contact' => '孙晨 13800000005', 'tags' => '血脂异常 / 定期复诊'],
        ];
    }

    public function getHealthOverview(): array
    {
        return [
            ['label' => '正常', 'value' => 98, 'unit' => '人', 'tone' => 'success', 'remark' => '生命体征平稳'],
            ['label' => '重点关注', 'value' => 24, 'unit' => '人', 'tone' => 'warning', 'remark' => '建议增加巡检频次'],
            ['label' => '高风险预警', 'value' => 6, 'unit' => '人', 'tone' => 'danger', 'remark' => '需立即跟进处理'],
            ['label' => '设备在线率', 'value' => 99, 'unit' => '%', 'tone' => 'info', 'remark' => '采集设备运行正常'],
        ];
    }

    public function getHealthRecords(): array
    {
        return [
            ['name' => '王淑珍', 'blood_pressure' => '150/92', 'blood_oxygen' => '96%', 'heart_rate' => 105, 'risk' => '预警', 'updated_at' => '2026-04-21 08:40'],
            ['name' => '李建国', 'blood_pressure' => '132/78', 'blood_oxygen' => '98%', 'heart_rate' => 82, 'risk' => '正常', 'updated_at' => '2026-04-21 08:35'],
            ['name' => '周桂芳', 'blood_pressure' => '142/90', 'blood_oxygen' => '95%', 'heart_rate' => 96, 'risk' => '关注', 'updated_at' => '2026-04-21 09:12'],
            ['name' => '陈德明', 'blood_pressure' => '128/76', 'blood_oxygen' => '97%', 'heart_rate' => 78, 'risk' => '正常', 'updated_at' => '2026-04-21 08:50'],
        ];
    }

    public function getAlerts(): array
    {
        return [
            ['name' => '王淑珍', 'room' => 'A-203', 'type' => '血压升高', 'level' => '高', 'time' => '08:40'],
            ['name' => '周桂芳', 'room' => 'C-305', 'type' => '离床未归', 'level' => '中', 'time' => '09:12'],
            ['name' => '李建国', 'room' => 'B-112', 'type' => '血糖偏低', 'level' => '中', 'time' => '09:18'],
        ];
    }

    public function getServiceSummary(): array
    {
        return [
            ['label' => '已完成', 'value' => 24, 'unit' => '项', 'tone' => 'success'],
            ['label' => '执行中', 'value' => 6, 'unit' => '项', 'tone' => 'info'],
            ['label' => '待开始', 'value' => 12, 'unit' => '项', 'tone' => 'warning'],
            ['label' => '延期', 'value' => 1, 'unit' => '项', 'tone' => 'danger'],
        ];
    }

    public function getServiceSchedules(): array
    {
        return [
            ['time' => '08:00', 'service' => '晨检测温', 'target' => '全院', 'owner' => '护理组 A', 'status' => '已完成'],
            ['time' => '09:30', 'service' => '康复训练', 'target' => '康复专区', 'owner' => '赵琳', 'status' => '执行中'],
            ['time' => '11:20', 'service' => '营养配餐', 'target' => '二号楼', 'owner' => '后勤组', 'status' => '待执行'],
            ['time' => '15:00', 'service' => '文娱活动', 'target' => '活动室', 'owner' => '社工组', 'status' => '待执行'],
        ];
    }

    public function getVisitSummary(): array
    {
        return [
            ['label' => '今日预约', 'value' => 12, 'unit' => '次', 'tone' => 'info'],
            ['label' => '待审核', 'value' => 3, 'unit' => '次', 'tone' => 'warning'],
            ['label' => '已到访', 'value' => 8, 'unit' => '次', 'tone' => 'success'],
            ['label' => '视频探访', 'value' => 5, 'unit' => '次', 'tone' => 'danger'],
        ];
    }

    public function getVisits(): array
    {
        return [
            ['visitor' => '王磊', 'elder' => '王淑珍', 'relation' => '儿子', 'time' => '2026-04-21 14:30', 'status' => '已预约'],
            ['visitor' => '李婷', 'elder' => '李建国', 'relation' => '女儿', 'time' => '2026-04-21 15:00', 'status' => '待审核'],
            ['visitor' => '周洋', 'elder' => '周桂芳', 'relation' => '孙子', 'time' => '2026-04-21 16:20', 'status' => '已到访'],
        ];
    }

    public function logs(): array
    {
        return [
            ['module' => '健康监测', 'operator' => '护士长-刘颖', 'action' => '处理高血压预警', 'time' => '2026-04-21 08:45'],
            ['module' => '老人档案', 'operator' => '客服-陈静', 'action' => '更新紧急联系人', 'time' => '2026-04-21 09:05'],
            ['module' => '服务调度', 'operator' => '调度员-王涛', 'action' => '新增康复训练排班', 'time' => '2026-04-21 09:18'],
            ['module' => '探访管理', 'operator' => '门岗-赵峰', 'action' => '审核家属来访申请', 'time' => '2026-04-21 09:20'],
        ];
    }
}
