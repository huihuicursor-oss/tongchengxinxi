<?php
declare(strict_types=1);

namespace app\service;

class ElderlyCareData
{
    public function getOverview(): array
    {
        return [
            'summaryCards' => [
                [
                    'label' => '服务老人',
                    'value' => '286',
                    'trend' => '较上月新增 18 位',
                ],
                [
                    'label' => '今日服务工单',
                    'value' => '47',
                    'trend' => '待处理 9 单',
                ],
                [
                    'label' => '风险预警',
                    'value' => '6',
                    'trend' => '其中高风险 2 项',
                ],
                [
                    'label' => '护理人员',
                    'value' => '38',
                    'trend' => '当班 24 人',
                ],
            ],
            'serviceStats' => [
                ['label' => '居家照护', 'value' => 92],
                ['label' => '助餐服务', 'value' => 64],
                ['label' => '康复训练', 'value' => 51],
                ['label' => '陪诊陪护', 'value' => 37],
            ],
            'notices' => [
                [
                    'title' => '五一前消防巡检安排',
                    'level' => '重要',
                    'time' => '今天 09:30',
                    'content' => '各站点于本周完成消防器材巡检，异常项需在系统内提交整改照片。',
                ],
                [
                    'title' => '慢病随访任务更新',
                    'level' => '普通',
                    'time' => '今天 08:10',
                    'content' => '高血压老人本周需补录血压两次，系统已自动生成提醒。',
                ],
                [
                    'title' => '社区义诊活动报名开始',
                    'level' => '活动',
                    'time' => '昨天 17:20',
                    'content' => '本周六联合社区医院开展义诊，请提前统计参与老人名单。',
                ],
            ],
            'schedule' => [
                ['time' => '08:00', 'task' => '晨间健康巡检', 'owner' => '李芳 / 王宁'],
                ['time' => '10:30', 'task' => '上门助洁服务', 'owner' => '第三网格 4 单'],
                ['time' => '14:00', 'task' => '康复训练小组课', 'owner' => '康复室'],
                ['time' => '16:30', 'task' => '重点老人回访', 'owner' => '社工组'],
            ],
            'alerts' => [
                [
                    'name' => '张秀兰',
                    'type' => '血压异常',
                    'detail' => '收缩压 168 mmHg，建议今日复测并电话回访。',
                    'status' => '待跟进',
                ],
                [
                    'name' => '周建国',
                    'type' => '离床告警',
                    'detail' => '凌晨 02:14 触发离床 18 分钟，夜班已确认安全。',
                    'status' => '已处理',
                ],
                [
                    'name' => '王阿姨',
                    'type' => '用药提醒遗漏',
                    'detail' => '午间药盒未确认领取，请家属端同步提醒。',
                    'status' => '待联系',
                ],
            ],
        ];
    }

    public function getElders(): array
    {
        return [
            [
                'name' => '张秀兰',
                'gender' => '女',
                'age' => 78,
                'room' => '2号楼-305',
                'contact' => '张先生 138****1123',
                'tags' => '高血压 / 独居',
                'status' => '重点关注',
                'health' => '82',
                'lastService' => '今日 09:00 血压复测',
            ],
            [
                'name' => '周建国',
                'gender' => '男',
                'age' => 81,
                'room' => '1号楼-212',
                'contact' => '周女士 139****0032',
                'tags' => '术后康复',
                'status' => '稳定',
                'health' => '90',
                'lastService' => '昨日 16:30 康复训练',
            ],
            [
                'name' => '王淑芬',
                'gender' => '女',
                'age' => 74,
                'room' => '社区居家-朝阳里 6-2-402',
                'contact' => '刘女士 137****6721',
                'tags' => '糖尿病 / 助餐',
                'status' => '随访中',
                'health' => '85',
                'lastService' => '今日 11:30 助餐配送',
            ],
            [
                'name' => '陈德福',
                'gender' => '男',
                'age' => 87,
                'room' => '3号楼-108',
                'contact' => '陈先生 136****8872',
                'tags' => '失能三级 / 长护险',
                'status' => '重点关注',
                'health' => '76',
                'lastService' => '今日 08:20 晨间护理',
            ],
            [
                'name' => '林桂香',
                'gender' => '女',
                'age' => 69,
                'room' => '社区居家-宁馨苑 8-1-702',
                'contact' => '林女士 158****9012',
                'tags' => '空巢 / 陪诊',
                'status' => '稳定',
                'health' => '93',
                'lastService' => '昨日 13:00 门诊陪诊',
            ],
        ];
    }

    public function getServiceOrders(): array
    {
        return [
            [
                'code' => 'FW20260421001',
                'name' => '张秀兰',
                'type' => '上门巡检',
                'address' => '2号楼-305',
                'appointTime' => '今天 09:00',
                'staff' => '李芳',
                'status' => '已完成',
            ],
            [
                'code' => 'FW20260421004',
                'name' => '王淑芬',
                'type' => '助餐配送',
                'address' => '朝阳里 6-2-402',
                'appointTime' => '今天 11:30',
                'staff' => '刘婷',
                'status' => '配送中',
            ],
            [
                'code' => 'FW20260421006',
                'name' => '林桂香',
                'type' => '陪诊服务',
                'address' => '市二院门诊楼',
                'appointTime' => '今天 13:00',
                'staff' => '陈倩',
                'status' => '待出发',
            ],
            [
                'code' => 'FW20260421008',
                'name' => '陈德福',
                'type' => '失能护理',
                'address' => '3号楼-108',
                'appointTime' => '今天 15:30',
                'staff' => '王宁',
                'status' => '待执行',
            ],
            [
                'code' => 'FW20260421011',
                'name' => '朱淑珍',
                'type' => '家政助洁',
                'address' => '新苑小区 4-3-201',
                'appointTime' => '今天 16:00',
                'staff' => '赵洁',
                'status' => '待分派',
            ],
        ];
    }

    public function getHealthRecords(): array
    {
        return [
            [
                'name' => '张秀兰',
                'bloodPressure' => '168/96',
                'bloodSugar' => '6.5',
                'heartRate' => '84',
                'sleep' => '6.2 小时',
                'risk' => '高',
                'updatedAt' => '今天 09:18',
            ],
            [
                'name' => '周建国',
                'bloodPressure' => '132/78',
                'bloodSugar' => '5.8',
                'heartRate' => '73',
                'sleep' => '7.5 小时',
                'risk' => '低',
                'updatedAt' => '今天 08:50',
            ],
            [
                'name' => '王淑芬',
                'bloodPressure' => '138/82',
                'bloodSugar' => '8.9',
                'heartRate' => '79',
                'sleep' => '6.8 小时',
                'risk' => '中',
                'updatedAt' => '今天 07:40',
            ],
            [
                'name' => '陈德福',
                'bloodPressure' => '145/88',
                'bloodSugar' => '7.2',
                'heartRate' => '81',
                'sleep' => '5.9 小时',
                'risk' => '中',
                'updatedAt' => '今天 08:05',
            ],
        ];
    }

    public function getStaff(): array
    {
        return [
            [
                'name' => '李芳',
                'role' => '责任护士',
                'shift' => '早班 08:00 - 16:00',
                'tasks' => '巡检 8 单 / 随访 3 单',
                'phone' => '135****1212',
                'status' => '在岗',
            ],
            [
                'name' => '王宁',
                'role' => '护理员',
                'shift' => '中班 12:00 - 20:00',
                'tasks' => '失能护理 5 单',
                'phone' => '136****2323',
                'status' => '在岗',
            ],
            [
                'name' => '陈倩',
                'role' => '社工',
                'shift' => '白班 09:00 - 17:30',
                'tasks' => '陪诊 2 单 / 活动组织 1 场',
                'phone' => '137****7878',
                'status' => '外勤',
            ],
            [
                'name' => '赵洁',
                'role' => '家政专员',
                'shift' => '机动班',
                'tasks' => '助洁 4 单',
                'phone' => '139****5656',
                'status' => '待分派',
            ],
        ];
    }

    public function getActivities(): array
    {
        return [
            [
                'title' => '社区义诊',
                'date' => '04-24 09:30',
                'location' => '一层康养大厅',
                'participants' => '已报名 28 人',
                'owner' => '社工组',
                'status' => '报名中',
            ],
            [
                'title' => '生日会',
                'date' => '04-26 15:00',
                'location' => '多功能活动室',
                'participants' => '寿星 6 人',
                'owner' => '护理部',
                'status' => '筹备中',
            ],
            [
                'title' => '防跌倒宣教',
                'date' => '04-27 10:00',
                'location' => '2号楼会议角',
                'participants' => '重点老人 18 人',
                'owner' => '康复室',
                'status' => '待开展',
            ],
        ];
    }
}
