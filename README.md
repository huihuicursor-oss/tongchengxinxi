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
