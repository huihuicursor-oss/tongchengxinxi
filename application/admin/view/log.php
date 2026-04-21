<section class="page-section">
    <div class="panel">
        <div class="panel-header">
            <h2>系统操作日志</h2>
            <span>留存关键动作，满足运营复盘与审计追溯</span>
        </div>
        <table class="data-table">
            <thead>
            <tr>
                <th>模块</th>
                <th>操作人</th>
                <th>动作</th>
                <th>时间</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($logs as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['module']); ?></td>
                    <td><?php echo htmlspecialchars($item['operator']); ?></td>
                    <td><?php echo htmlspecialchars($item['action']); ?></td>
                    <td><?php echo htmlspecialchars($item['time']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
