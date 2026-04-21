<section class="grid-cards four">
    <?php foreach ($serviceSummary as $item): ?>
        <article class="card stat-card">
            <p class="card-label"><?php echo htmlspecialchars($item['label']); ?></p>
            <div class="stat-value">
                <strong><?php echo htmlspecialchars((string) $item['value']); ?></strong>
                <span>项</span>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<section class="card">
    <div class="card-title">当日服务排班</div>
    <table class="table">
        <thead>
        <tr>
            <th>时间</th>
            <th>服务项目</th>
            <th>服务对象</th>
            <th>责任人</th>
            <th>状态</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($serviceSchedules as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['time']); ?></td>
                <td><?php echo htmlspecialchars($item['service']); ?></td>
                <td><?php echo htmlspecialchars($item['target']); ?></td>
                <td><?php echo htmlspecialchars($item['owner']); ?></td>
                <td>
                    <span class="tag <?php echo $item['status'] === '已完成' ? 'done' : ($item['status'] === '执行中' ? 'progress' : 'pending'); ?>">
                        <?php echo htmlspecialchars($item['status']); ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
