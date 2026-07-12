<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>エラー - Quicksearch</title>
    <link rel="stylesheet" href="/assets/css/quicksearch.css">
</head>

<body>
    <div class="page">
        <h1>エラー</h1>
        <p class="error-message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><a href="/settings">設定へ戻る</a></p>
    </div>
</body>

</html>