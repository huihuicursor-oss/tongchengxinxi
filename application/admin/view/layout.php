<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - <?php echo htmlspecialchars($systemName); ?></title>
    <link rel="stylesheet" href="/static/css/admin.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo">YL</div>
            <div>
                <strong><?php echo htmlspecialchars($systemName); ?></strong>
                <p>ThinkPHP5 原型版</p>
            </div>
        </div>
        <nav class="menu">
            <?php foreach ($menuItems as $key => $title): ?>
                <a class="menu-item <?php echo $activeMenu === $key ? 'active' : ''; ?>" href="/index.php?s=/<?php echo $key; ?>/index">
                    <span><?php echo htmlspecialchars($title); ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <p>机构：颐养中心示范院</p>
            <p>值班：王护士长</p>
        </div>
    </aside>
    <main class="main-content">
        <header class="topbar">
            <div>
                <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
                <p><?php echo htmlspecialchars($pageDescription); ?></p>
            </div>
            <div class="topbar-actions">
                <span class="status-dot"></span>
                <span>实时监控正常</span>
            </div>
        </header>

        <?php echo $content; ?>
    </main>
</div>
</body>
</html>
