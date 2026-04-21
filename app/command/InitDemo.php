<?php
declare(strict_types=1);

namespace app\command;

use app\model\Activity;
use app\model\AdminUser;
use app\model\BillingRecord;
use app\model\CareStaff;
use app\model\CareTask;
use app\model\Elder;
use app\model\FamilyContact;
use app\model\HealthRecord;
use app\model\MedicationPlan;
use app\model\Notice;
use app\model\ServiceOrder;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;

class InitDemo extends Command
{
    protected function configure(): void
    {
        $this->setName('demo:init')
            ->setDescription('Initialize MySQL schema and seed elderly care demo data');
    }

    protected function execute(Input $input, Output $output): int
    {
        $output->writeln('Preparing MySQL schema...');
        $this->createTables();
        $this->seedAdminUsers();
        $elderMap = $this->seedElders();
        $this->seedHealthRecords($elderMap);
        $this->seedServiceOrders($elderMap);
        $this->seedStaff();
        $this->seedActivities();
        $this->seedNotices();
        $this->seedFamilyContacts($elderMap);
        $this->seedMedicationPlans($elderMap);
        $this->seedCareTasks($elderMap);
        $this->seedBillingRecords($elderMap);
        $output->writeln('Demo data initialized successfully.');

        return 0;
    }

    private function createTables(): void
    {
        $statements = [
            "CREATE TABLE IF NOT EXISTS admin_users (id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT, username VARCHAR(50) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, name VARCHAR(50) NOT NULL, role VARCHAR(30) NOT NULL, status TINYINT(1) NOT NULL DEFAULT 1, last_login_at DATETIME NULL, create_time DATETIME NULL, update_time DATETIME NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS elders (id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT, name VARCHAR(50) NOT NULL, gender VARCHAR(10) NOT NULL, age INT NOT NULL, phone VARCHAR(20) NOT NULL, id_card VARCHAR(32) NULL, service_mode VARCHAR(30) NOT NULL, room VARCHAR(100) NOT NULL, address VARCHAR(255) NOT NULL, contact_name VARCHAR(50) NOT NULL, contact_phone VARCHAR(20) NOT NULL, tags VARCHAR(255) NULL, care_level VARCHAR(30) NOT NULL, health_score INT NOT NULL DEFAULT 0, status VARCHAR(30) NOT NULL, remark TEXT NULL, create_time DATETIME NULL, update_time DATETIME NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS health_records (id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT, elder_id BIGINT UNSIGNED NOT NULL, blood_pressure VARCHAR(20) NOT NULL, blood_sugar DECIMAL(4,1) NOT NULL, heart_rate SMALLINT NOT NULL, sleep_hours DECIMAL(3,1) NOT NULL, risk_level VARCHAR(10) NOT NULL, recorded_at DATETIME NOT NULL, notes VARCHAR(255) NOT NULL, create_time DATETIME NULL, update_time DATETIME NULL, KEY idx_health_elder (elder_id), CONSTRAINT fk_health_elder FOREIGN KEY (elder_id) REFERENCES elders(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS service_orders (id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT, elder_id BIGINT UNSIGNED NOT NULL, code VARCHAR(30) NOT NULL UNIQUE, type VARCHAR(50) NOT NULL, address VARCHAR(255) NOT NULL, appoint_time DATETIME NOT NULL, staff_name VARCHAR(50) NOT NULL, status VARCHAR(30) NOT NULL, notes VARCHAR(255) NOT NULL, create_time DATETIME NULL, update_time DATETIME NULL, KEY idx_order_elder (elder_id), CONSTRAINT fk_order_elder FOREIGN KEY (elder_id) REFERENCES elders(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS care_staff (id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT, name VARCHAR(50) NOT NULL, role VARCHAR(30) NOT NULL, shift VARCHAR(50) NOT NULL, tasks VARCHAR(100) NOT NULL, phone VARCHAR(20) NOT NULL, status VARCHAR(20) NOT NULL, create_time DATETIME NULL, update_time DATETIME NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS activities (id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT, title VARCHAR(80) NOT NULL, activity_date DATETIME NOT NULL, location VARCHAR(120) NOT NULL, participants VARCHAR(120) NOT NULL, owner VARCHAR(50) NOT NULL, status VARCHAR(30) NOT NULL, description TEXT NULL, create_time DATETIME NULL, update_time DATETIME NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS notices (id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT, title VARCHAR(80) NOT NULL, level VARCHAR(20) NOT NULL, content TEXT NOT NULL, publish_time DATETIME NOT NULL, create_time DATETIME NULL, update_time DATETIME NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS family_contacts (id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT, elder_id BIGINT UNSIGNED NOT NULL, name VARCHAR(50) NOT NULL, relation VARCHAR(30) NOT NULL, phone VARCHAR(20) NOT NULL, wechat VARCHAR(40) NULL, is_primary TINYINT(1) NOT NULL DEFAULT 0, concern_level VARCHAR(20) NOT NULL, last_contact_at DATETIME NULL, remark VARCHAR(255) NULL, create_time DATETIME NULL, update_time DATETIME NULL, KEY idx_family_elder (elder_id), CONSTRAINT fk_family_elder FOREIGN KEY (elder_id) REFERENCES elders(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS medication_plans (id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT, elder_id BIGINT UNSIGNED NOT NULL, medicine_name VARCHAR(80) NOT NULL, dosage VARCHAR(50) NOT NULL, frequency VARCHAR(50) NOT NULL, schedule_time DATETIME NOT NULL, executor VARCHAR(50) NOT NULL, status VARCHAR(30) NOT NULL, notes VARCHAR(255) NULL, create_time DATETIME NULL, update_time DATETIME NULL, KEY idx_med_elder (elder_id), CONSTRAINT fk_med_elder FOREIGN KEY (elder_id) REFERENCES elders(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS care_tasks (id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT, elder_id BIGINT UNSIGNED NOT NULL, task_type VARCHAR(50) NOT NULL, ward VARCHAR(50) NOT NULL, bed_no VARCHAR(30) NOT NULL, schedule_time DATETIME NOT NULL, executor VARCHAR(50) NOT NULL, priority VARCHAR(20) NOT NULL, status VARCHAR(30) NOT NULL, notes VARCHAR(255) NULL, create_time DATETIME NULL, update_time DATETIME NULL, KEY idx_care_elder (elder_id), CONSTRAINT fk_care_elder FOREIGN KEY (elder_id) REFERENCES elders(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS billing_records (id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT, elder_id BIGINT UNSIGNED NOT NULL, bill_month VARCHAR(20) NOT NULL, item_name VARCHAR(80) NOT NULL, amount DECIMAL(10,2) NOT NULL, pay_status VARCHAR(20) NOT NULL, pay_method VARCHAR(30) NULL, due_date DATETIME NOT NULL, operator_name VARCHAR(50) NOT NULL, remark VARCHAR(255) NULL, create_time DATETIME NULL, update_time DATETIME NULL, KEY idx_bill_elder (elder_id), CONSTRAINT fk_bill_elder FOREIGN KEY (elder_id) REFERENCES elders(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        ];

        foreach ($statements as $statement) {
            Db::execute($statement);
        }
    }

    private function seedAdminUsers(): void
    {
        if (AdminUser::count() > 0) {
            return;
        }
        $now = date('Y-m-d H:i:s');
        AdminUser::insertAll([
            ['username' => 'admin', 'password' => password_hash('Admin@123456', PASSWORD_DEFAULT), 'name' => '系统管理员', 'role' => 'super_admin', 'status' => 1, 'create_time' => $now, 'update_time' => $now],
            ['username' => 'operator', 'password' => password_hash('Operator@123456', PASSWORD_DEFAULT), 'name' => '运营主管', 'role' => 'operator', 'status' => 1, 'create_time' => $now, 'update_time' => $now],
            ['username' => 'viewer', 'password' => password_hash('Viewer@123456', PASSWORD_DEFAULT), 'name' => '查看账号', 'role' => 'viewer', 'status' => 1, 'create_time' => $now, 'update_time' => $now],
        ]);
    }

    private function seedElders(): array
    {
        if (Elder::count() === 0) {
            $now = date('Y-m-d H:i:s');
            Elder::insertAll([
                ['name' => '张秀兰', 'gender' => '女', 'age' => 78, 'phone' => '13800001123', 'id_card' => '310101194801013226', 'service_mode' => '机构养老', 'room' => '2号楼-305', 'address' => '上海市青浦区幸福养老院 2号楼-305', 'contact_name' => '张先生', 'contact_phone' => '13800001123', 'tags' => '高血压 / 独居', 'care_level' => '二级护理', 'health_score' => 82, 'status' => '重点关注', 'remark' => '需每日晨间复测血压', 'create_time' => $now, 'update_time' => $now],
                ['name' => '周建国', 'gender' => '男', 'age' => 81, 'phone' => '13900000032', 'id_card' => '310101194503061214', 'service_mode' => '机构养老', 'room' => '1号楼-212', 'address' => '上海市青浦区幸福养老院 1号楼-212', 'contact_name' => '周女士', 'contact_phone' => '13900000032', 'tags' => '术后康复', 'care_level' => '康复观察', 'health_score' => 90, 'status' => '稳定', 'remark' => '每周三、周五安排康复训练', 'create_time' => $now, 'update_time' => $now],
                ['name' => '王淑芬', 'gender' => '女', 'age' => 74, 'phone' => '13700006721', 'id_card' => '310101195207083843', 'service_mode' => '社区居家', 'room' => '朝阳里 6-2-402', 'address' => '上海市青浦区朝阳里 6-2-402', 'contact_name' => '刘女士', 'contact_phone' => '13700006721', 'tags' => '糖尿病 / 助餐', 'care_level' => '一级护理', 'health_score' => 85, 'status' => '随访中', 'remark' => '午餐需低糖配餐', 'create_time' => $now, 'update_time' => $now],
                ['name' => '陈德福', 'gender' => '男', 'age' => 87, 'phone' => '13600008872', 'id_card' => '310101193904224517', 'service_mode' => '机构养老', 'room' => '3号楼-108', 'address' => '上海市青浦区幸福养老院 3号楼-108', 'contact_name' => '陈先生', 'contact_phone' => '13600008872', 'tags' => '失能三级 / 长护险', 'care_level' => '三级护理', 'health_score' => 76, 'status' => '重点关注', 'remark' => '需重点防跌倒与翻身护理', 'create_time' => $now, 'update_time' => $now],
                ['name' => '林桂香', 'gender' => '女', 'age' => 69, 'phone' => '15800009012', 'id_card' => '310101195603162927', 'service_mode' => '居家上门', 'room' => '宁馨苑 8-1-702', 'address' => '上海市青浦区宁馨苑 8-1-702', 'contact_name' => '林女士', 'contact_phone' => '15800009012', 'tags' => '空巢 / 陪诊', 'care_level' => '一级护理', 'health_score' => 93, 'status' => '稳定', 'remark' => '月度门诊复诊需陪诊', 'create_time' => $now, 'update_time' => $now],
            ]);
        }

        $map = [];
        foreach (Elder::select() as $elder) {
            $map[(string) $elder->name] = (int) $elder->id;
        }
        return $map;
    }

    private function seedHealthRecords(array $elderMap): void
    {
        if (HealthRecord::count() > 0) {
            return;
        }
        $now = date('Y-m-d H:i:s');
        HealthRecord::insertAll([
            ['elder_id' => $elderMap['张秀兰'], 'blood_pressure' => '168/96', 'blood_sugar' => 6.5, 'heart_rate' => 84, 'sleep_hours' => 6.2, 'risk_level' => '高', 'recorded_at' => '2026-04-21 09:18:00', 'notes' => '收缩压偏高，建议今日复测并电话回访。', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['周建国'], 'blood_pressure' => '132/78', 'blood_sugar' => 5.8, 'heart_rate' => 73, 'sleep_hours' => 7.5, 'risk_level' => '低', 'recorded_at' => '2026-04-21 08:50:00', 'notes' => '指标平稳，继续维持康复训练计划。', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['王淑芬'], 'blood_pressure' => '138/82', 'blood_sugar' => 8.9, 'heart_rate' => 79, 'sleep_hours' => 6.8, 'risk_level' => '中', 'recorded_at' => '2026-04-21 07:40:00', 'notes' => '餐后血糖偏高，需补录饮食记录。', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['陈德福'], 'blood_pressure' => '145/88', 'blood_sugar' => 7.2, 'heart_rate' => 81, 'sleep_hours' => 5.9, 'risk_level' => '中', 'recorded_at' => '2026-04-21 08:05:00', 'notes' => '夜间睡眠不足，需加强翻身护理。', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['林桂香'], 'blood_pressure' => '126/74', 'blood_sugar' => 5.6, 'heart_rate' => 70, 'sleep_hours' => 7.1, 'risk_level' => '低', 'recorded_at' => '2026-04-21 07:20:00', 'notes' => '整体情况良好。', 'create_time' => $now, 'update_time' => $now],
        ]);
    }

    private function seedServiceOrders(array $elderMap): void
    {
        if (ServiceOrder::count() > 0) {
            return;
        }
        $now = date('Y-m-d H:i:s');
        ServiceOrder::insertAll([
            ['elder_id' => $elderMap['张秀兰'], 'code' => 'FW20260421001', 'type' => '上门巡检', 'address' => '2号楼-305', 'appoint_time' => '2026-04-21 09:00:00', 'staff_name' => '李芳', 'status' => '已完成', 'notes' => '完成血压复测并记录。', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['王淑芬'], 'code' => 'FW20260421004', 'type' => '助餐配送', 'address' => '朝阳里 6-2-402', 'appoint_time' => '2026-04-21 11:30:00', 'staff_name' => '刘婷', 'status' => '配送中', 'notes' => '按低糖餐标准备餐。', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['林桂香'], 'code' => 'FW20260421006', 'type' => '陪诊服务', 'address' => '市二院门诊楼', 'appoint_time' => '2026-04-21 13:00:00', 'staff_name' => '陈倩', 'status' => '待出发', 'notes' => '需协助挂号与取药。', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['陈德福'], 'code' => 'FW20260421008', 'type' => '失能护理', 'address' => '3号楼-108', 'appoint_time' => '2026-04-21 15:30:00', 'staff_name' => '王宁', 'status' => '待执行', 'notes' => '重点关注翻身与压疮护理。', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['周建国'], 'code' => 'FW20260421011', 'type' => '康复训练', 'address' => '康复训练室', 'appoint_time' => '2026-04-21 16:00:00', 'staff_name' => '赵洁', 'status' => '待分派', 'notes' => '下午复健训练 40 分钟。', 'create_time' => $now, 'update_time' => $now],
        ]);
    }

    private function seedStaff(): void
    {
        if (CareStaff::count() > 0) {
            return;
        }
        $now = date('Y-m-d H:i:s');
        CareStaff::insertAll([
            ['name' => '李芳', 'role' => '责任护士', 'shift' => '早班 08:00 - 16:00', 'tasks' => '巡检 8 单 / 随访 3 单', 'phone' => '13500001212', 'status' => '在岗', 'create_time' => $now, 'update_time' => $now],
            ['name' => '王宁', 'role' => '护理员', 'shift' => '中班 12:00 - 20:00', 'tasks' => '失能护理 5 单', 'phone' => '13600002323', 'status' => '在岗', 'create_time' => $now, 'update_time' => $now],
            ['name' => '陈倩', 'role' => '社工', 'shift' => '白班 09:00 - 17:30', 'tasks' => '陪诊 2 单 / 活动组织 1 场', 'phone' => '13700007878', 'status' => '外勤', 'create_time' => $now, 'update_time' => $now],
            ['name' => '赵洁', 'role' => '家政专员', 'shift' => '机动班', 'tasks' => '助洁 4 单', 'phone' => '13900005656', 'status' => '待分派', 'create_time' => $now, 'update_time' => $now],
        ]);
    }

    private function seedActivities(): void
    {
        if (Activity::count() > 0) {
            return;
        }
        $now = date('Y-m-d H:i:s');
        Activity::insertAll([
            ['title' => '社区义诊', 'activity_date' => '2026-04-24 09:30:00', 'location' => '一层康养大厅', 'participants' => '已报名 28 人', 'owner' => '社工组', 'status' => '报名中', 'description' => '联合社区医院开展血压、血糖筛查。', 'create_time' => $now, 'update_time' => $now],
            ['title' => '生日会', 'activity_date' => '2026-04-26 15:00:00', 'location' => '多功能活动室', 'participants' => '寿星 6 人', 'owner' => '护理部', 'status' => '筹备中', 'description' => '为本月寿星举办主题生日会。', 'create_time' => $now, 'update_time' => $now],
            ['title' => '防跌倒宣教', 'activity_date' => '2026-04-27 10:00:00', 'location' => '2号楼会议角', 'participants' => '重点老人 18 人', 'owner' => '康复室', 'status' => '待开展', 'description' => '针对失能及高龄老人开展防跌倒课程。', 'create_time' => $now, 'update_time' => $now],
        ]);
    }

    private function seedNotices(): void
    {
        if (Notice::count() > 0) {
            return;
        }
        $now = date('Y-m-d H:i:s');
        Notice::insertAll([
            ['title' => '五一前消防巡检安排', 'level' => '重要', 'content' => '各站点于本周完成消防器材巡检，异常项需在系统内提交整改照片。', 'publish_time' => '2026-04-21 09:30:00', 'create_time' => $now, 'update_time' => $now],
            ['title' => '慢病随访任务更新', 'level' => '普通', 'content' => '高血压老人本周需补录血压两次，系统已自动生成提醒。', 'publish_time' => '2026-04-21 08:10:00', 'create_time' => $now, 'update_time' => $now],
            ['title' => '社区义诊活动报名开始', 'level' => '活动', 'content' => '本周六联合社区医院开展义诊，请提前统计参与老人名单。', 'publish_time' => '2026-04-20 17:20:00', 'create_time' => $now, 'update_time' => $now],
        ]);
    }

    private function seedFamilyContacts(array $elderMap): void
    {
        if (FamilyContact::count() > 0) {
            return;
        }
        $now = date('Y-m-d H:i:s');
        FamilyContact::insertAll([
            ['elder_id' => $elderMap['张秀兰'], 'name' => '张国强', 'relation' => '儿子', 'phone' => '13800001123', 'wechat' => 'zhanggq23', 'is_primary' => 1, 'concern_level' => '高关注', 'last_contact_at' => '2026-04-21 10:30:00', 'remark' => '每日关注血压变化', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['周建国'], 'name' => '周雅兰', 'relation' => '女儿', 'phone' => '13900000032', 'wechat' => 'zhouyl32', 'is_primary' => 1, 'concern_level' => '常规', 'last_contact_at' => '2026-04-20 18:00:00', 'remark' => '康复训练后发送汇报', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['王淑芬'], 'name' => '刘晓敏', 'relation' => '侄女', 'phone' => '13700006721', 'wechat' => 'liuxm721', 'is_primary' => 1, 'concern_level' => '高关注', 'last_contact_at' => '2026-04-21 11:40:00', 'remark' => '饮食情况需同步', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['陈德福'], 'name' => '陈卫东', 'relation' => '儿子', 'phone' => '13600008872', 'wechat' => 'cwd8872', 'is_primary' => 1, 'concern_level' => '重点', 'last_contact_at' => '2026-04-21 08:50:00', 'remark' => '夜间护理异常及时通知', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['林桂香'], 'name' => '林依萍', 'relation' => '女儿', 'phone' => '15800009012', 'wechat' => 'lyp9012', 'is_primary' => 1, 'concern_level' => '常规', 'last_contact_at' => '2026-04-19 20:10:00', 'remark' => '门诊陪诊后回访', 'create_time' => $now, 'update_time' => $now],
        ]);
    }

    private function seedMedicationPlans(array $elderMap): void
    {
        if (MedicationPlan::count() > 0) {
            return;
        }
        $now = date('Y-m-d H:i:s');
        MedicationPlan::insertAll([
            ['elder_id' => $elderMap['张秀兰'], 'medicine_name' => '缬沙坦胶囊', 'dosage' => '80mg / 次', 'frequency' => '每日早 1 次', 'schedule_time' => '2026-04-21 08:00:00', 'executor' => '李芳', 'status' => '待核对', 'notes' => '服药前测血压', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['王淑芬'], 'medicine_name' => '二甲双胍', 'dosage' => '0.5g / 次', 'frequency' => '每日晚 1 次', 'schedule_time' => '2026-04-21 18:30:00', 'executor' => '刘婷', 'status' => '待发药', 'notes' => '餐后 30 分钟服用', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['陈德福'], 'medicine_name' => '阿司匹林肠溶片', 'dosage' => '100mg / 次', 'frequency' => '每日早 1 次', 'schedule_time' => '2026-04-21 08:30:00', 'executor' => '王宁', 'status' => '已完成', 'notes' => '需记录是否吞咽顺利', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['周建国'], 'medicine_name' => '甲钴胺片', 'dosage' => '0.5mg / 次', 'frequency' => '每日中 1 次', 'schedule_time' => '2026-04-21 12:30:00', 'executor' => '赵洁', 'status' => '待执行', 'notes' => '康复后补药', 'create_time' => $now, 'update_time' => $now],
        ]);
    }

    private function seedCareTasks(array $elderMap): void
    {
        if (CareTask::count() > 0) {
            return;
        }
        $now = date('Y-m-d H:i:s');
        CareTask::insertAll([
            ['elder_id' => $elderMap['张秀兰'], 'task_type' => '晨间巡房', 'ward' => '2号楼', 'bed_no' => '305-1', 'schedule_time' => '2026-04-21 08:10:00', 'executor' => '李芳', 'priority' => '高', 'status' => '进行中', 'notes' => '复核血压、服药情况', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['陈德福'], 'task_type' => '翻身护理', 'ward' => '3号楼', 'bed_no' => '108-1', 'schedule_time' => '2026-04-21 10:00:00', 'executor' => '王宁', 'priority' => '高', 'status' => '待执行', 'notes' => '需双人协助', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['周建国'], 'task_type' => '康复训练', 'ward' => '康复区', 'bed_no' => '212-1', 'schedule_time' => '2026-04-21 16:00:00', 'executor' => '赵洁', 'priority' => '中', 'status' => '待执行', 'notes' => '步态训练 40 分钟', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['王淑芬'], 'task_type' => '助餐回访', 'ward' => '社区居家', 'bed_no' => '402', 'schedule_time' => '2026-04-21 12:00:00', 'executor' => '刘婷', 'priority' => '中', 'status' => '已完成', 'notes' => '确认饮食偏好', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['林桂香'], 'task_type' => '陪诊交接', 'ward' => '居家上门', 'bed_no' => '702', 'schedule_time' => '2026-04-21 14:00:00', 'executor' => '陈倩', 'priority' => '低', 'status' => '待出发', 'notes' => '门诊资料需带齐', 'create_time' => $now, 'update_time' => $now],
        ]);
    }

    private function seedBillingRecords(array $elderMap): void
    {
        if (BillingRecord::count() > 0) {
            return;
        }
        $now = date('Y-m-d H:i:s');
        BillingRecord::insertAll([
            ['elder_id' => $elderMap['张秀兰'], 'bill_month' => '2026-04', 'item_name' => '护理服务费', 'amount' => 3200.00, 'pay_status' => '待支付', 'pay_method' => null, 'due_date' => '2026-04-25 00:00:00', 'operator_name' => '财务A', 'remark' => '含重点巡检服务', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['周建国'], 'bill_month' => '2026-04', 'item_name' => '康复训练费', 'amount' => 1800.00, 'pay_status' => '已支付', 'pay_method' => '微信', 'due_date' => '2026-04-20 00:00:00', 'operator_name' => '财务A', 'remark' => '家属线上缴费', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['王淑芬'], 'bill_month' => '2026-04', 'item_name' => '助餐服务费', 'amount' => 960.00, 'pay_status' => '部分支付', 'pay_method' => '银行卡', 'due_date' => '2026-04-23 00:00:00', 'operator_name' => '财务B', 'remark' => '剩余尾款待补', 'create_time' => $now, 'update_time' => $now],
            ['elder_id' => $elderMap['陈德福'], 'bill_month' => '2026-04', 'item_name' => '长护险差额费', 'amount' => 4200.00, 'pay_status' => '待审核', 'pay_method' => null, 'due_date' => '2026-04-28 00:00:00', 'operator_name' => '财务C', 'remark' => '需补交票据', 'create_time' => $now, 'update_time' => $now],
        ]);
    }
}
