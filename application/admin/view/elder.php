<section class="grid-cards four">
    <?php foreach ($elderStats as $item): ?>
        <article class="card stat-card">
            <p class="card-label"><?php echo htmlspecialchars($item['label']); ?></p>
            <div class="stat-value">
                <strong><?php echo htmlspecialchars((string) $item['value']); ?></strong>
                <span><?php echo htmlspecialchars($item['unit']); ?></span>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<section class="card">
    <div class="card-title">老人档案列表</div>
    <table class="table">
        <thead>
        <tr>
            <th>姓名</th>
            <th>性别</th>
            <th>年龄</th>
            <th>房间</th>
            <th>护理等级</th>
            <th>家属联系人</th>
            <th>标签</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($profiles as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo htmlspecialchars($item['gender']); ?></td>
                <td><?php echo (int) $item['age']; ?></td>
                <td><?php echo htmlspecialchars($item['room']); ?></td>
                <td><?php echo htmlspecialchars($item['level']); ?></td>
                <td><?php echo htmlspecialchars($item['contact']); ?></td>
                <td><?php echo htmlspecialchars($item['tags']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
