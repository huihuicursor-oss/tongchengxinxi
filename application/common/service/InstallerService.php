<?php

namespace app\common\service;

use PDO;
use RuntimeException;

class InstallerService
{
    protected static $booted = false;

    public static function bootstrap($refresh = false)
    {
        if (self::$booted && !$refresh) {
            return;
        }

        $runtimeDir = self::runtimeDir();
        if (!is_dir($runtimeDir) && !mkdir($runtimeDir, 0777, true) && !is_dir($runtimeDir)) {
            throw new RuntimeException('无法创建运行目录：' . $runtimeDir);
        }

        $dbFile = self::dbFile();
        if ($refresh && is_file($dbFile)) {
            unlink($dbFile);
        }

        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA foreign_keys = OFF');

        self::createTables($pdo);
        self::seedTables($pdo);
        self::$booted = true;
    }

    public static function dbFile()
    {
        return self::runtimeDir() . '/elderly.sqlite';
    }

    protected static function runtimeDir()
    {
        return self::rootPath() . '/runtime/data';
    }

    protected static function rootPath()
    {
        return dirname(dirname(dirname(__DIR__)));
    }

    protected static function createTables(PDO $pdo)
    {
        $sql = [
            'CREATE TABLE IF NOT EXISTS ec_role (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, code TEXT NOT NULL UNIQUE, description TEXT DEFAULT "", status INTEGER DEFAULT 1, created_at TEXT NOT NULL)',
            'CREATE TABLE IF NOT EXISTS ec_admin_user (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL UNIQUE, password_hash TEXT NOT NULL, real_name TEXT NOT NULL, role_id INTEGER NOT NULL, status INTEGER DEFAULT 1, last_login_at TEXT DEFAULT "", created_at TEXT NOT NULL, updated_at TEXT NOT NULL)',
            'CREATE TABLE IF NOT EXISTS ec_menu (id INTEGER PRIMARY KEY AUTOINCREMENT, parent_id INTEGER DEFAULT 0, title TEXT NOT NULL, route TEXT NOT NULL, permission TEXT NOT NULL, icon TEXT DEFAULT "", sort INTEGER DEFAULT 0, status INTEGER DEFAULT 1, created_at TEXT NOT NULL)',
            'CREATE TABLE IF NOT EXISTS ec_role_menu (id INTEGER PRIMARY KEY AUTOINCREMENT, role_id INTEGER NOT NULL, menu_id INTEGER NOT NULL)',
            'CREATE TABLE IF NOT EXISTS ec_elder_profile (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, gender TEXT NOT NULL, age INTEGER NOT NULL, room TEXT NOT NULL, care_level TEXT NOT NULL, contact_name TEXT NOT NULL, contact_phone TEXT NOT NULL, tags TEXT DEFAULT "", medical_history TEXT DEFAULT "", admission_date TEXT NOT NULL, status TEXT NOT NULL, remark TEXT DEFAULT "")',
            'CREATE TABLE IF NOT EXISTS ec_health_record (id INTEGER PRIMARY KEY AUTOINCREMENT, elder_id INTEGER NOT NULL, blood_pressure TEXT NOT NULL, blood_oxygen TEXT NOT NULL, heart_rate INTEGER NOT NULL, risk_level TEXT NOT NULL, note TEXT DEFAULT "", measured_at TEXT NOT NULL)',
            'CREATE TABLE IF NOT EXISTS ec_service_order (id INTEGER PRIMARY KEY AUTOINCREMENT, service_name TEXT NOT NULL, target_name TEXT NOT NULL, owner_name TEXT NOT NULL, service_time TEXT NOT NULL, status TEXT NOT NULL, remark TEXT DEFAULT "")',
            'CREATE TABLE IF NOT EXISTS ec_alert_event (id INTEGER PRIMARY KEY AUTOINCREMENT, elder_id INTEGER NOT NULL, title TEXT NOT NULL, alert_type TEXT NOT NULL, level TEXT NOT NULL, status TEXT NOT NULL, content TEXT NOT NULL, handled_by TEXT DEFAULT "", handled_note TEXT DEFAULT "", created_at TEXT NOT NULL, handled_at TEXT DEFAULT "")',
            'CREATE TABLE IF NOT EXISTS ec_visit_record (id INTEGER PRIMARY KEY AUTOINCREMENT, elder_id INTEGER NOT NULL, visitor_name TEXT NOT NULL, relation TEXT NOT NULL, visit_time TEXT NOT NULL, status TEXT NOT NULL, review_note TEXT DEFAULT "", reviewed_by TEXT DEFAULT "", created_at TEXT NOT NULL, reviewed_at TEXT DEFAULT "")',
            'CREATE TABLE IF NOT EXISTS ec_operation_log (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER DEFAULT 0, module TEXT NOT NULL, action TEXT NOT NULL, content TEXT NOT NULL, created_at TEXT NOT NULL)'
        ];

        foreach ($sql as $statement) {
            $pdo->exec($statement);
        }
    }

    protected static function seedTables(PDO $pdo)
    {
        $count = (int) $pdo->query('SELECT COUNT(*) FROM ec_admin_user')->fetchColumn();
        if ($count > 0) {
            return;
        }

        $now = date('Y-m-d H:i:s');
        $roles = [
            ['id' => 1, 'name' => '超级管理员', 'code' => 'super_admin', 'description' => '拥有全部系统权限'],
            ['id' => 2, 'name' => '护理主管', 'code' => 'care_manager', 'description' => '可查看老人、健康、服务、告警和探访信息'],
            ['id' => 3, 'name' => '前台接待', 'code' => 'reception', 'description' => '负责来访登记与老人档案查询'],
        ];
        self::insertBatch($pdo, 'ec_role', $roles, ['id', 'name', 'code', 'description'], ['status' => 1, 'created_at' => $now]);

        $users = [
            ['id' => 1, 'username' => 'admin', 'password_hash' => password_hash('Admin@123', PASSWORD_DEFAULT), 'real_name' => '系统管理员', 'role_id' => 1],
            ['id' => 2, 'username' => 'operator', 'password_hash' => password_hash('Operator@123', PASSWORD_DEFAULT), 'real_name' => '王护士长', 'role_id' => 2],
            ['id' => 3, 'username' => 'reception', 'password_hash' => password_hash('Reception@123', PASSWORD_DEFAULT), 'real_name' => '前台小李', 'role_id' => 3],
        ];
        self::insertBatch($pdo, 'ec_admin_user', $users, ['id', 'username', 'password_hash', 'real_name', 'role_id'], ['status' => 1, 'last_login_at' => '', 'created_at' => $now, 'updated_at' => $now]);

        $menus = [
            ['id' => 1, 'parent_id' => 0, 'title' => '系统总览', 'route' => 'admin/dashboard/index', 'permission' => 'dashboard/index', 'sort' => 10],
            ['id' => 2, 'parent_id' => 0, 'title' => '老人档案', 'route' => 'admin/elder/index', 'permission' => 'elder/index', 'sort' => 20],
            ['id' => 3, 'parent_id' => 0, 'title' => '健康监测', 'route' => 'admin/health/index', 'permission' => 'health/index', 'sort' => 30],
            ['id' => 4, 'parent_id' => 0, 'title' => '服务调度', 'route' => 'admin/service/index', 'permission' => 'service/index', 'sort' => 40],
            ['id' => 5, 'parent_id' => 0, 'title' => '告警处理', 'route' => 'admin/alert/index', 'permission' => 'alert/index', 'sort' => 50],
            ['id' => 6, 'parent_id' => 0, 'title' => '家属探访', 'route' => 'admin/visit/index', 'permission' => 'visit/index', 'sort' => 60],
            ['id' => 7, 'parent_id' => 0, 'title' => '权限管理', 'route' => 'admin/permission/index', 'permission' => 'permission/index', 'sort' => 70],
            ['id' => 8, 'parent_id' => 0, 'title' => '菜单管理', 'route' => 'admin/menu/index', 'permission' => 'menu/index', 'sort' => 80],
            ['id' => 9, 'parent_id' => 0, 'title' => '运营日志', 'route' => 'admin/log/index', 'permission' => 'log/index', 'sort' => 90]
        ];
        self::insertBatch($pdo, 'ec_menu', $menus, ['id', 'parent_id', 'title', 'route', 'permission', 'sort'], ['icon' => '', 'status' => 1, 'created_at' => $now]);

        $roleMenus = [];
        foreach (range(1, 9) as $menuId) {
            $roleMenus[] = ['role_id' => 1, 'menu_id' => $menuId];
        }
        foreach ([1, 2, 3, 4, 5, 6, 9] as $menuId) {
            $roleMenus[] = ['role_id' => 2, 'menu_id' => $menuId];
        }
        foreach ([1, 2, 5, 6] as $menuId) {
            $roleMenus[] = ['role_id' => 3, 'menu_id' => $menuId];
        }
        self::insertBatch($pdo, 'ec_role_menu', $roleMenus, ['role_id', 'menu_id'], []);

        $elders = [
            ['id' => 1, 'name' => '王淑珍', 'gender' => '女', 'age' => 82, 'room' => 'A-203', 'care_level' => '二级护理', 'contact_name' => '王磊', 'contact_phone' => '13800000001', 'tags' => '高血压,夜间巡查', 'medical_history' => '高血压、骨质疏松', 'admission_date' => '2025-06-12', 'status' => '在住', 'remark' => '需重点关注血压波动'],
            ['id' => 2, 'name' => '李建国', 'gender' => '男', 'age' => 76, 'room' => 'B-112', 'care_level' => '一级护理', 'contact_name' => '李婷', 'contact_phone' => '13800000002', 'tags' => '糖尿病,饮食控制', 'medical_history' => 'II型糖尿病', 'admission_date' => '2025-09-05', 'status' => '在住', 'remark' => '用药需按时打卡'],
            ['id' => 3, 'name' => '周桂芳', 'gender' => '女', 'age' => 79, 'room' => 'C-305', 'care_level' => '失能照护', 'contact_name' => '周洋', 'contact_phone' => '13800000003', 'tags' => '离床预警,24小时陪护', 'medical_history' => '脑梗后遗症', 'admission_date' => '2024-12-18', 'status' => '在住', 'remark' => '需轮椅协助活动'],
            ['id' => 4, 'name' => '陈德明', 'gender' => '男', 'age' => 84, 'room' => 'A-107', 'care_level' => '康复观察', 'contact_name' => '陈雪', 'contact_phone' => '13800000004', 'tags' => '术后恢复,步态训练', 'medical_history' => '髋关节术后恢复', 'admission_date' => '2026-02-21', 'status' => '在住', 'remark' => '每日一次康复训练'],
            ['id' => 5, 'name' => '孙玉兰', 'gender' => '女', 'age' => 73, 'room' => 'B-208', 'care_level' => '慢病管理', 'contact_name' => '孙晨', 'contact_phone' => '13800000005', 'tags' => '血脂异常,定期复诊', 'medical_history' => '高脂血症', 'admission_date' => '2025-11-09', 'status' => '在住', 'remark' => '重点追踪饮食结构']
        ];
        self::insertBatch($pdo, 'ec_elder_profile', $elders, ['id', 'name', 'gender', 'age', 'room', 'care_level', 'contact_name', 'contact_phone', 'tags', 'medical_history', 'admission_date', 'status', 'remark'], []);

        $healthRecords = [
            ['elder_id' => 1, 'blood_pressure' => '150/92', 'blood_oxygen' => '96%', 'heart_rate' => 105, 'risk_level' => '预警', 'note' => '早间血压偏高', 'measured_at' => '2026-04-21 08:40:00'],
            ['elder_id' => 2, 'blood_pressure' => '132/78', 'blood_oxygen' => '98%', 'heart_rate' => 82, 'risk_level' => '正常', 'note' => '体征平稳', 'measured_at' => '2026-04-21 08:35:00'],
            ['elder_id' => 3, 'blood_pressure' => '142/90', 'blood_oxygen' => '95%', 'heart_rate' => 96, 'risk_level' => '关注', 'note' => '离床后心率偏快', 'measured_at' => '2026-04-21 09:12:00'],
            ['elder_id' => 4, 'blood_pressure' => '128/76', 'blood_oxygen' => '97%', 'heart_rate' => 78, 'risk_level' => '正常', 'note' => '康复前评估', 'measured_at' => '2026-04-21 08:50:00'],
            ['elder_id' => 5, 'blood_pressure' => '138/84', 'blood_oxygen' => '97%', 'heart_rate' => 80, 'risk_level' => '正常', 'note' => '复测正常', 'measured_at' => '2026-04-21 09:30:00']
        ];
        self::insertBatch($pdo, 'ec_health_record', $healthRecords, ['elder_id', 'blood_pressure', 'blood_oxygen', 'heart_rate', 'risk_level', 'note', 'measured_at'], []);

        $services = [
            ['service_name' => '晨检测温', 'target_name' => '全院', 'owner_name' => '护理组 A', 'service_time' => '2026-04-21 08:00', 'status' => '已完成', 'remark' => '完成 128 人体温采集'],
            ['service_name' => '康复训练', 'target_name' => '陈德明', 'owner_name' => '赵琳', 'service_time' => '2026-04-21 09:30', 'status' => '执行中', 'remark' => '步态训练 30 分钟'],
            ['service_name' => '营养配餐', 'target_name' => '二号楼', 'owner_name' => '后勤组', 'service_time' => '2026-04-21 11:20', 'status' => '待执行', 'remark' => '糖尿病配餐单独标记'],
            ['service_name' => '文娱活动', 'target_name' => '活动室', 'owner_name' => '社工组', 'service_time' => '2026-04-21 15:00', 'status' => '待执行', 'remark' => '书法兴趣班']
        ];
        self::insertBatch($pdo, 'ec_service_order', $services, ['service_name', 'target_name', 'owner_name', 'service_time', 'status', 'remark'], []);

        $alerts = [
            ['elder_id' => 1, 'title' => '血压升高', 'alert_type' => '生命体征', 'level' => '高', 'status' => '待处理', 'content' => '早间测量血压 150/92，建议 10 分钟后复测。', 'handled_by' => '', 'handled_note' => '', 'created_at' => '2026-04-21 08:40:00', 'handled_at' => ''],
            ['elder_id' => 3, 'title' => '离床未归', 'alert_type' => '行为预警', 'level' => '中', 'status' => '处理中', 'content' => '离床传感器触发 15 分钟未归位。', 'handled_by' => '王护士长', 'handled_note' => '护理员已前往查看。', 'created_at' => '2026-04-21 09:12:00', 'handled_at' => '2026-04-21 09:15:00'],
            ['elder_id' => 2, 'title' => '血糖偏低', 'alert_type' => '慢病管理', 'level' => '中', 'status' => '待处理', 'content' => '餐前血糖偏低，请留意补糖。', 'handled_by' => '', 'handled_note' => '', 'created_at' => '2026-04-21 09:18:00', 'handled_at' => '']
        ];
        self::insertBatch($pdo, 'ec_alert_event', $alerts, ['elder_id', 'title', 'alert_type', 'level', 'status', 'content', 'handled_by', 'handled_note', 'created_at', 'handled_at'], []);

        $visits = [
            ['elder_id' => 1, 'visitor_name' => '王磊', 'relation' => '儿子', 'visit_time' => '2026-04-21 14:30', 'status' => '已通过', 'review_note' => '已核验身份信息', 'reviewed_by' => '前台小李', 'created_at' => '2026-04-20 18:00:00', 'reviewed_at' => '2026-04-20 18:10:00'],
            ['elder_id' => 2, 'visitor_name' => '李婷', 'relation' => '女儿', 'visit_time' => '2026-04-21 15:00', 'status' => '待审核', 'review_note' => '', 'reviewed_by' => '', 'created_at' => '2026-04-20 19:00:00', 'reviewed_at' => ''],
            ['elder_id' => 3, 'visitor_name' => '周洋', 'relation' => '孙子', 'visit_time' => '2026-04-21 16:20', 'status' => '已通过', 'review_note' => '允许陪同 30 分钟', 'reviewed_by' => '前台小李', 'created_at' => '2026-04-20 20:15:00', 'reviewed_at' => '2026-04-20 20:20:00'],
            ['elder_id' => 4, 'visitor_name' => '陈雪', 'relation' => '女儿', 'visit_time' => '2026-04-22 10:00', 'status' => '已驳回', 'review_note' => '当天康复训练排期冲突，请改约。', 'reviewed_by' => '前台小李', 'created_at' => '2026-04-20 20:30:00', 'reviewed_at' => '2026-04-20 20:45:00']
        ];
        self::insertBatch($pdo, 'ec_visit_record', $visits, ['elder_id', 'visitor_name', 'relation', 'visit_time', 'status', 'review_note', 'reviewed_by', 'created_at', 'reviewed_at'], []);

        $logs = [
            ['user_id' => 1, 'module' => '系统初始化', 'action' => '创建基础数据', 'content' => '初始化角色、菜单、老人、告警与探访示例数据', 'created_at' => $now],
            ['user_id' => 2, 'module' => '告警处理', 'action' => '跟进离床预警', 'content' => '已通知护理员现场查看周桂芳状态', 'created_at' => '2026-04-21 09:16:00']
        ];
        self::insertBatch($pdo, 'ec_operation_log', $logs, ['user_id', 'module', 'action', 'content', 'created_at'], []);
    }

    protected static function insertBatch(PDO $pdo, $table, array $rows, array $columns, array $defaults)
    {
        if (!$rows) {
            return;
        }

        foreach ($rows as $row) {
            $payload = array_merge($defaults, $row);
            $fields = array_keys($payload);
            $placeholders = array_map(function ($field) {
                return ':' . $field;
            }, $fields);

            $stmt = $pdo->prepare(sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $table,
                implode(', ', $fields),
                implode(', ', $placeholders)
            ));

            foreach ($payload as $field => $value) {
                $stmt->bindValue(':' . $field, $value);
            }
            $stmt->execute();
        }
    }

    public static function logOperation($userId, $module, $action, $content)
    {
        self::bootstrap();
        $pdo = new PDO('sqlite:' . self::dbFile());
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare('INSERT INTO ec_operation_log (user_id, module, action, content, created_at) VALUES (:user_id, :module, :action, :content, :created_at)');
        $stmt->execute([
            ':user_id'    => (int) $userId,
            ':module'     => $module,
            ':action'     => $action,
            ':content'    => $content,
            ':created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
