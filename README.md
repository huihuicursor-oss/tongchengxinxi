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

## 下一步可继续扩展

1. 按原型截图继续逐页精修 UI 和交互。
2. 增加服务工单、健康记录、活动通知的新增/编辑功能。
3. 接入短信、微信通知、设备告警和家属端同步。
4. 增加统计图表、大屏和移动端页面。
