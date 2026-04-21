# 智慧养老管理系统（ThinkPHP5.1 可运行版）

## 项目简介

本项目已从原型版升级为 **真正可运行的 ThinkPHP5.1 项目**，并实现了养老机构后台的核心业务功能。

当前版本默认使用 **SQLite** 作为开箱即用的数据源，便于在无 MySQL 的环境中直接启动、登录和演示；同时保留了后续切换到 MySQL 的空间。

## 已实现功能

### 1. 认证与权限

- 后台登录 / 退出登录
- 角色权限控制（RBAC 基础版）
- 菜单权限分配
- 用户角色调整
- 菜单启停管理

### 2. 养老业务模块

- 系统总览
- 老人档案列表
- 老人详情页
- 健康监测
- 服务调度
- 告警处理
- 家属探访审核
- 运营日志

### 3. 数据能力

- SQLite 自动初始化
- 默认演示数据自动写入
- 告警处理结果可落库
- 家属探访审核可落库
- 用户角色调整可落库
- 菜单状态切换可落库

## 默认账号

- 超级管理员：`admin / Admin@123`
- 护理主管：`operator / Operator@123`
- 前台接待：`reception / Reception@123`

## 目录结构

```text
.
├── application
│   ├── admin
│   │   ├── controller
│   │   └── view
│   ├── common
│   │   ├── command
│   │   └── service
│   ├── command.php
│   ├── common.php
│   ├── provider.php
│   └── tags.php
├── config
├── database
│   └── elderly_care.sql
├── docs
├── public
│   └── static
├── route
├── runtime
├── scripts
│   └── patch_thinkphp_php8.php
├── think
├── thinkphp
├── composer.json
└── composer.lock
```

## 本地启动

### 1. 安装依赖

```bash
composer install --ignore-platform-reqs
```

### 2. 初始化数据

```bash
php scripts/patch_thinkphp_php8.php
php think app:init --refresh
```

### 3. 启动服务

```bash
php think run -p 8001
```

## 访问地址

当前项目已验证可用的访问方式为：

```text
http://127.0.0.1:8001/index.php?s=/admin/auth/login
```

常用页面：

- `/index.php?s=/admin/dashboard/index`
- `/index.php?s=/admin/elder/index`
- `/index.php?s=/admin/health/index`
- `/index.php?s=/admin/service/index`
- `/index.php?s=/admin/alert/index`
- `/index.php?s=/admin/visit/index`
- `/index.php?s=/admin/permission/index`
- `/index.php?s=/admin/menu/index`
- `/index.php?s=/admin/log/index`

## 技术说明

### ThinkPHP5.1 与 PHP 8.3 兼容

ThinkPHP5.1 在 PHP 8.1+ 下存在若干兼容性问题。本项目已补充：

- `scripts/patch_thinkphp_php8.php` 自动补丁脚本
- 对 `Container / Request / Loader` 等兼容点的处理

在新环境部署时，推荐先执行：

```bash
php scripts/patch_thinkphp_php8.php
```

## 后续建议

后续可继续扩展：

1. 切换到 MySQL 正式库
2. 增加密码修改 / 重置密码
3. 增加机构、楼栋、房间管理
4. 增加老人建档、编辑、删除
5. 增加服务工单创建和分派
6. 增加统计报表和数据可视化
7. 接入短信、微信、智能设备告警
