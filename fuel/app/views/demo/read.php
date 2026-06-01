<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Demo - Read</title>
</head>
<body>
    <h1>Read</h1>
    <ul>
        <li><a href="/demo/create">Create</a></li>
        <li><a href="/demo/update">Update</a></li>
        <li><a href="/demo/delete">Delete</a></li>
    </ul>
    
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Key</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($item['key'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($item['value'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
            <tr>
                <td colspan="3">No data</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
