<section class="grid-cards">
    <?php foreach ($healthOverview as $item): ?>
        <article class="card stat-card">
            <p class="card-label"><?php echo htmlspecialchars($item['label']); ?></p>
            <div class="card-value">
                <?php echo htmlspecialchars((string) $item['value']); ?>
                <small><?php echo htmlspecialchars($item['unit']); ?></small>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<section class="content-grid">
    <article class="card">
        <h2 class="panel-title">实时监测</h2>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>姓名</th>
                    <th>血压</th>
                    <th>血氧</th>
                    <th>心率</th>
                    <th>风险</th>
                    <th>更新时间</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($healthRecords as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['name']); ?></td>
                        <td><?php echo htmlspecialchars($record['blood_pressure']); ?></td>
                        <td><?php echo htmlspecialchars($record['blood_oxygen']); ?></td>
                        <td><?php echo htmlspecialchars((string) $record['heart_rate']); ?></td>
                        <td>
                            <span class="tag <?php echo $record['risk'] === '预警' ? 'tag-high' : ($record['risk'] === '关注' ? 'tag-medium' : 'tag-normal'); ?>">
                                <?php echo htmlspecialchars($record['risk']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($record['updated_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </article>

    <article class="card">
        <h2 class="panel-title">预警列表</h2>
        <ul class="simple-list">
            <?php foreach ($alerts as $alert): ?>
                <li>
                    <strong><?php echo htmlspecialchars($alert['name']); ?></strong>
                    - <?php echo htmlspecialchars($alert['type']); ?>
                    / 房间 <?php echo htmlspecialchars($alert['room']); ?>
                    / <?php echo htmlspecialchars($alert['time']); ?>
                    <span class="tag <?php echo $alert['level'] === '高' ? 'tag-high' : 'tag-medium'; ?>">
                        <?php echo htmlspecialchars($alert['level']); ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </article>
</section>
