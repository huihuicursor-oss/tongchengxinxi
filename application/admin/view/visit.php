<section class="grid-cards four">
    <?php foreach ($visitSummary as $item): ?>
        <article class="card stat-card">
            <p class="card-label"><?php echo htmlspecialchars($item['label']); ?></p>
            <div class="card-value"><?php echo htmlspecialchars((string) $item['value']); ?></div>
        </article>
    <?php endforeach; ?>
</section>

<section class="card">
    <h2 class="panel-title">探访预约</h2>
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>来访人</th>
                <th>探访老人</th>
                <th>关系</th>
                <th>预约时间</th>
                <th>状态</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($visits as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['visitor']); ?></td>
                    <td><?php echo htmlspecialchars($item['elder']); ?></td>
                    <td><?php echo htmlspecialchars($item['relation']); ?></td>
                    <td><?php echo htmlspecialchars($item['time']); ?></td>
                    <td>
                        <?php
                        $className = 'tag-info';
                        if ($item['status'] === '待审核') {
                            $className = 'tag-pending';
                        } elseif ($item['status'] === '已到访') {
                            $className = 'tag-approved';
                        }
                        ?>
                        <span class="tag <?php echo $className; ?>"><?php echo htmlspecialchars($item['status']); ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
