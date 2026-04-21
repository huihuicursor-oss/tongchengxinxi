<section class="grid-cards">
    <?php foreach ($stats as $item): ?>
        <article class="card stat-card">
            <p class="card-label"><?php echo htmlspecialchars($item['label']); ?></p>
            <div class="card-value"><?php echo htmlspecialchars((string) $item['value']); ?><small><?php echo htmlspecialchars($item['unit']); ?></small></div>
            <p class="trend"><?php echo htmlspecialchars($item['trend']); ?></p>
        </article>
    <?php endforeach; ?>
</section>

<section class="content-grid">
    <article class="card">
        <h2 class="panel-title">今日预警</h2>
        <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>姓名</th>
                <th>房间</th>
                <th>事件</th>
                <th>等级</th>
                <th>时间</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($alerts as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['room']); ?></td>
                    <td><?php echo htmlspecialchars($item['type']); ?></td>
                    <td><span class="tag <?php echo $item['level'] === '高' ? 'tag-high' : 'tag-warning'; ?>"><?php echo htmlspecialchars($item['level']); ?></span></td>
                    <td><?php echo htmlspecialchars($item['time']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </article>

    <article class="card">
        <h2 class="panel-title">院内通知</h2>
        <ul class="simple-list">
            <?php foreach ($notices as $notice): ?>
                <li><?php echo htmlspecialchars($notice); ?></li>
            <?php endforeach; ?>
        </ul>
        <h2 class="panel-title section-spacer">待执行服务</h2>
        <ul class="simple-list">
            <?php foreach ($services as $item): ?>
                <li>
                    <?php echo htmlspecialchars($item['service']); ?> - <?php echo htmlspecialchars($item['owner']); ?>（<?php echo htmlspecialchars($item['status']); ?>）
                </li>
            <?php endforeach; ?>
        </ul>
    </article>
</section>
