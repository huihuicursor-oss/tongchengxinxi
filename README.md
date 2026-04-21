# 智慧养老服务管理平台

基于 ThinkPHP 8 和 MySQL 的养老系统演示项目，已实现后台登录/权限、老人档案详情、新增/编辑表单，以及仪表盘、工单、健康监测、排班、活动通知等页面。

## 已实现功能

- 后台登录、退出登录
- 角色权限：超级管理员 / 运营人员 / 只读账号
- MySQL 数据库接入
- 初始化命令 `php think demo:init`
- 运营概览 Dashboard
- 老人档案列表、详情、新增、编辑
- 服务工单中心
- 健康监测
- 护理排班
- 活动通知

## 环境要求

- PHP 8.2+
- Composer
- MySQL 8+

## 数据库配置

复制示例环境变量并按实际环境调整：

```bash
cp .example.env .env
```

本地演示可使用如下配置：

```env
APP_DEBUG = true
DB_DRIVER = mysql
DB_TYPE = mysql
DB_HOST = 127.0.0.1
DB_PORT = 3306
DB_NAME = elderly_care
DB_USER = elderly_app
DB_PASS = ElderlyApp@2026
DB_CHARSET = utf8mb4
DEFAULT_LANG = zh-cn
```

## 初始化数据

```bash
php think demo:init
```

## 启动项目

```bash
php think run
```

默认访问地址：

```text
http://127.0.0.1:8000
```

## 部署注意事项

1. Web 站点根目录必须指向项目的 `public/` 目录，而不是仓库根目录。
2. 如果部署在二级目录，例如 `https://example.com/elderly/`，当前代码已兼容子目录路径。
3. 部署完成后请先执行：

```bash
php think demo:init
```

4. 如果登录页提示“用户名或密码错误”，通常是部署环境数据库里还没有初始化演示账号，或者已经存在旧账号数据。

## 演示账号

- `admin / Admin@123456`（超级管理员）
- `operator / Operator@123456`（运营人员）
- `viewer / Viewer@123456`（只读账号）

## 项目目录结构说明

```text
.
├── app/
│   ├── command/                # 控制台命令
│   │   └── InitDemo.php        # 初始化 MySQL 表结构与演示数据
│   ├── controller/             # 控制器层
│   │   ├── Auth.php            # 登录 / 退出
│   │   ├── AdminBaseController.php
│   │   ├── ElderlyCare.php     # 主业务后台控制器
│   │   └── Index.php           # 根路径与默认跳转
│   ├── middleware/
│   │   └── AuthMiddleware.php  # 登录校验中间件
│   ├── model/                  # ORM 模型
│   │   ├── Elder.php
│   │   ├── HealthRecord.php
│   │   ├── ServiceOrder.php
│   │   ├── CareTask.php
│   │   ├── MedicationPlan.php
│   │   ├── FamilyContact.php
│   │   ├── BillingRecord.php
│   │   ├── CareStaff.php
│   │   ├── Activity.php
│   │   ├── Notice.php
│   │   └── AdminUser.php
│   ├── service/
│   │   └── ElderlyCareService.php  # 页面聚合数据与业务展示逻辑
│   └── common.php              # 路径辅助函数（支持子目录部署）
├── config/                     # ThinkPHP 配置
├── database/
│   └── schema.sql              # 当前项目使用的数据表说明稿
├── public/                     # Web 入口目录（必须作为站点运行目录）
│   ├── index.php
│   ├── .htaccess
│   └── static/
│       └── app.css             # 后台样式文件
├── route/
│   └── app.php                 # 全部页面路由
├── view/                       # 页面模板
│   ├── layout/
│   ├── auth/
│   ├── dashboard/
│   ├── elder/
│   ├── care/
│   ├── service/
│   ├── health/
│   ├── medication/
│   ├── family/
│   ├── staff/
│   ├── activity/
│   └── billing/
├── composer.json
├── README.md
└── think                        # ThinkPHP 命令入口
```

### 关键目录说明

- `app/controller/ElderlyCare.php`：主后台业务入口，负责所有管理页面分发。
- `app/service/ElderlyCareService.php`：把多个数据表聚合成页面所需的数据结构。
- `app/command/InitDemo.php`：初始化数据库结构和演示数据，部署后第一时间执行。
- `view/*`：按模块划分模板页面，便于继续做 1:1 原型还原。
- `public/static/app.css`：后台管理系统统一样式。

## 数据库表关系说明

### 核心主表

- `elders`：老人档案主表，是系统的业务核心。
- `admin_users`：后台账号表，用于登录和角色控制。

### 以 `elders` 为中心的一对多关系

`elders.id` 关联以下业务表的 `elder_id`：

- `health_records`：健康监测记录
- `service_orders`：服务工单
- `family_contacts`：家属联系人
- `medication_plans`：用药计划
- `care_tasks`：护理任务
- `billing_records`：费用账单

可以理解为：

```text
elders
├── health_records
├── service_orders
├── family_contacts
├── medication_plans
├── care_tasks
└── billing_records
```

### 其他独立业务表

- `care_staff`：护理人员与班次资源
- `activities`：活动中心 / 长者活动
- `notices`：后台公告通知

### 表用途概览

| 表名 | 作用 |
|---|---|
| `admin_users` | 后台登录账号、角色、状态 |
| `elders` | 老人基础档案 |
| `health_records` | 血压、血糖、心率、睡眠、风险等级 |
| `service_orders` | 助餐、陪诊、巡检、康复等服务工单 |
| `care_staff` | 护理员、护士、社工、家政人员排班 |
| `activities` | 活动策划、报名、执行 |
| `notices` | 公告通知 |
| `family_contacts` | 家属联系人、最近联系记录、关注等级 |
| `medication_plans` | 用药提醒、剂量、执行状态 |
| `care_tasks` | 护理任务、楼栋床位、优先级、执行状态 |
| `billing_records` | 费用账单、账期、金额、支付状态 |

## 页面路由清单

### 公共路由

| 路径 | 方法 | 说明 | 权限 |
|---|---|---|---|
| `/` | GET | 根路径，自动跳转登录或后台首页 | 公开 |
| `/login` | GET | 登录页 | 公开 |
| `/login` | POST | 登录提交 | 公开 |
| `/logout` | GET | 退出登录 | 已登录 |

### 后台业务路由

以下路由统一受 `AuthMiddleware` 保护：

| 路径 | 方法 | 页面/功能 | 权限 |
|---|---|---|---|
| `/dashboard` | GET | 运营概览 | 已登录 |
| `/elders` | GET | 老人档案列表 | 已登录 |
| `/elders/create` | GET | 新增老人档案页 | `super_admin` / `operator` |
| `/elders/create` | POST | 新增老人档案提交 | `super_admin` / `operator` |
| `/elders/{id}` | GET | 老人详情 | 已登录 |
| `/elders/{id}/edit` | GET | 编辑老人档案页 | `super_admin` / `operator` |
| `/elders/{id}/edit` | POST | 编辑老人档案提交 | `super_admin` / `operator` |
| `/care-tasks` | GET | 护理执行 | 已登录 |
| `/services` | GET | 服务工单 | 已登录 |
| `/health` | GET | 健康监测 | 已登录 |
| `/medications` | GET | 用药提醒 | 已登录 |
| `/families` | GET | 家属联络 | 已登录 |
| `/staff` | GET | 护理排班 | 已登录 |
| `/activities` | GET | 活动中心 | 已登录 |
| `/billing` | GET | 费用结算 | 已登录 |

## 角色权限说明

当前系统内置 3 个角色：

### 1. `super_admin` 超级管理员

权限：
- 登录后台
- 查看全部模块
- 新增老人档案
- 编辑老人档案
- 具备后续扩展为全量管理权限的基础角色

默认账号：
- `admin / Admin@123456`

### 2. `operator` 运营人员

权限：
- 登录后台
- 查看全部模块
- 新增老人档案
- 编辑老人档案
- 适合作为运营、档案录入、客服协同角色

默认账号：
- `operator / Operator@123456`

### 3. `viewer` 只读账号

权限：
- 登录后台
- 查看全部模块
- 不允许新增老人档案
- 不允许编辑老人档案

默认账号：
- `viewer / Viewer@123456`

### 当前代码中的权限边界

目前系统已实际限制：

- `super_admin` / `operator`：可访问 `elders/create` 和 `elders/{id}/edit`
- `viewer`：访问以上页面会返回 `403`

如果后续继续扩展，可进一步细分：
- 财务权限
- 护理权限
- 用药权限
- 活动运营权限
- 家属服务权限

## 后续开发计划

### 第一阶段：把现有模块补齐 CRUD

优先建议补齐以下模块的数据录入和编辑能力：

1. 护理执行 `care_tasks`
2. 用药提醒 `medication_plans`
3. 家属联络 `family_contacts`
4. 费用结算 `billing_records`
5. 服务工单 `service_orders`
6. 健康监测 `health_records`

### 第二阶段：补齐真实业务流程

- 登录后的菜单权限控制
- 新增 / 编辑 / 删除 / 查看审批流
- 任务状态流转
- 费用支付状态流转
- 家属联系记录跟踪
- 多条件筛选与分页

### 第三阶段：继续原型 1:1 还原

- 按原型截图调整布局、字号、配色、间距
- 对齐卡片尺寸、表格结构、详情页编排
- 增加更多交互态（空状态、加载态、状态切换、弹窗）

### 第四阶段：可交付化

- 接入真实业务数据
- 增加日志审计
- 增加异常处理和权限日志
- 接入短信 / 微信 / 设备告警
- 加入大屏 / 移动端 / 家属端

## 部署步骤（Nginx / Apache / 宝塔）

### 通用部署步骤

1. 拉取代码到服务器
2. 安装 PHP 8.2+、Composer、MySQL 8+
3. 安装依赖（如果未来加入更多 Composer 包，再执行）
4. 复制环境文件：

```bash
cp .example.env .env
```

5. 修改 `.env` 中的数据库配置
6. 执行数据库初始化：

```bash
php think demo:init
```

7. 将 Web 入口指向 `public/`
8. 配置伪静态 / rewrite

---

### Nginx 部署步骤

假设项目路径：

```text
/www/wwwroot/elderly-care
```

Nginx 站点配置示例：

```nginx
server {
    listen 80;
    server_name your-domain.com;

    root /www/wwwroot/elderly-care/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
    }
}
```

部署后执行：

```bash
php think demo:init
```

---

### Apache 部署步骤

站点根目录必须指向：

```text
/www/wwwroot/elderly-care/public
```

需要开启模块：

- `mod_rewrite`

并允许目录覆盖（AllowOverride）：

```apache
<Directory "/www/wwwroot/elderly-care/public">
    Options FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

项目里已经自带：

- `public/.htaccess`

会把非真实文件请求转发到 `index.php`。

部署后执行：

```bash
php think demo:init
```

---

### 宝塔部署步骤

如果你用宝塔面板，建议这样配置：

1. 新建 PHP 站点
2. 网站目录指向项目目录，例如：

```text
/www/wwwroot/elderly-care
```

3. 在网站设置中，将**运行目录**设置为：

```text
public
```

4. PHP 版本选择 `8.2+`
5. 数据库创建 MySQL 库，例如：

```text
elderly_care
```

6. 修改 `.env`
7. 进入终端执行：

```bash
cd /www/wwwroot/elderly-care
php think demo:init
```

8. 如果访问异常，检查：
   - 运行目录是否为 `public`
   - 伪静态是否启用
   - 数据库配置是否正确
   - 是否成功初始化演示账号

## 开发日志

### 第 1 阶段：项目初始化

- 基于 ThinkPHP 8 初始化项目骨架。
- 增加基础路由、控制器、模板渲染能力。
- 安装视图驱动，确保模板页面可正常输出。
- 初始版本以养老后台演示页为主，用于快速搭建可访问界面。

对应提交：

- `de0bc43` feat: bootstrap thinkphp elderly care demo
- `0febf72` fix: add thinkphp view driver

### 第 2 阶段：数据库与权限体系接入

- 明确数据库方案为 MySQL。
- 接入 MySQL 数据库配置与 `php think demo:init` 初始化命令。
- 增加后台登录、退出登录、角色权限控制。
- 实现老人档案列表、详情、新增、编辑等基础 CRUD 流程。
- 修复登录会话、路由匹配、搜索筛选等核心问题。

对应提交：

- `f66fda1` feat: add mysql auth and elder crud
- `b331671` fix: complete auth flow and elder search
- `5dda26b` docs: clarify mysql database requirement

### 第 3 阶段：部署兼容修复

- 修复部署到二级目录时静态资源、表单提交、页面跳转写死根路径的问题。
- 新增部署路径辅助方法，统一处理 `appBase`。
- 明确 README 中的部署要求：Web 根目录必须指向 `public/`。

对应提交：

- `03baff3` fix: support subdirectory deployment paths

### 第 4 阶段：完整系统重写

- 结合可恢复的墨刀养老服务平台/护理端原型线索，对系统进行整套后台化重写。
- 页面样式改为更完整的管理系统风格：左侧导航、顶部工具栏、统计卡片、业务表格、详情分区、录入表单。
- 扩展业务模块：
  - 运营概览
  - 老人档案
  - 护理执行
  - 服务工单
  - 健康监测
  - 用药提醒
  - 家属联络
  - 护理排班
  - 活动中心
  - 费用结算
- 扩展数据表与模型：
  - `care_tasks`
  - `medication_plans`
  - `family_contacts`
  - `billing_records`

对应提交：

- `b167a32` feat: rewrite elderly care admin system

### 当前状态

- 系统已具备完整后台导航与主要业务模块。
- `demo:init` 可初始化扩展后的完整演示数据。
- 管理员可登录并访问全部模块。
- 只读账号仍受权限限制，不能新增或编辑老人档案。
- 目前版本已经是“完整系统骨架 + 管理后台样式 + 演示数据闭环”。
- 如需继续 1:1 对齐墨刀页面，还需要补充逐页原型截图或导出图。

## 下一步可继续扩展

1. 按原型截图继续逐页精修 UI 和交互。
2. 增加服务工单、健康记录、活动通知的新增/编辑功能。
3. 接入短信、微信通知、设备告警和家属端同步。
4. 增加统计图表、大屏和移动端页面。
