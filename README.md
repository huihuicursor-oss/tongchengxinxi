# 智慧养老管理系统（ThinkPHP5 目录规范原型版）

## 项目说明

本仓库根据用户提供的墨刀养老系统原型链接，输出一套可继续落地的养老管理系统项目骨架，采用 **ThinkPHP5 常见目录结构** 组织代码，便于后续直接迁移到正式的 ThinkPHP5.1 运行环境。

由于当前原型公开页无法直接抓取到具体页面明细，本版本按照智慧养老后台的典型业务进行实现，覆盖以下核心模块：

- 系统总览
- 老人档案
- 健康监测
- 服务调度
- 家属探访
- 运营日志

## 目录结构

```text
.
├── application
│   ├── admin
│   │   ├── controller
│   │   └── view
│   ├── common
│   │   └── service
│   ├── config.php
│   ├── database.php
│   └── route.php
├── database
│   └── elderly_care.sql
├── docs
│   ├── 开发日志.md
│   ├── 部署文档.md
│   └── 架构说明.md
├── extend
│   └── bootstrap.php
└── public
    ├── index.php
    ├── router.php
    └── static
        └── css
```

## 已实现内容

### 1. 后台原型页面

- 适老化后台视觉风格
- 左侧导航 + 顶部摘要 + 卡片式内容区
- 六个业务页面的可切换展示
- 使用 PHP 数组模拟数据，便于后续接数据库

### 2. 数据库初始化脚本

已提供 `database/elderly_care.sql`，包含示例表：

- `admin_user`
- `elder_profile`
- `caregiver`
- `health_record`
- `alert_event`
- `service_order`
- `visit_record`
- `operation_log`

### 3. 交付文档

- `docs/开发日志.md`
- `docs/部署文档.md`
- `docs/架构说明.md`

## 本地预览

当前仓库是 **按 ThinkPHP5 项目习惯组织的原型版**，为了便于在未安装完整 TP5 内核的环境里也能查看页面，项目带了一个轻量入口：

```bash
php -S 0.0.0.0:8000 -t public public/router.php
```

访问：

```text
http://127.0.0.1:8000/index.php?s=/dashboard/index
```

其它页面：

- `/index.php?s=/elder/index`
- `/index.php?s=/health/index`
- `/index.php?s=/service/index`
- `/index.php?s=/visit/index`
- `/index.php?s=/log/index`

## 与正式 ThinkPHP5 项目的衔接建议

1. 使用 Composer 安装 `thinkphp 5.1` 正式内核。
2. 将当前 `application` 下的控制器、视图、配置迁移到正式 TP5 项目。
3. 将 `DemoRepository` 中的模拟数组替换为模型 + 数据库查询。
4. 接入登录、RBAC、菜单权限、消息通知、设备数据采集。

## 注意事项

- 当前版本优先交付 **项目骨架、页面原型、数据库设计和文档**。
- 当前环境最初未预装 PHP / Composer，因此没有直接拉起官方 TP5 内核；已按 TP5 目录规范完成可继续开发的实现。
- 推荐部署时使用 `PHP 7.2 ~ 7.4 + Nginx + MySQL 5.7/8.0`。
