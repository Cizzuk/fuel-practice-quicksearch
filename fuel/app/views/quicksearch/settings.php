<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>検索エンジンの設定 - Quicksearch</title>
</head>

<body>
    <div class="page" id="settings-root" data-max-engines="<?php echo (int) $max_engines; ?>">
        <h1>検索エンジンの設定</h1>
        <p>ログイン中のアカウント: <?php echo htmlspecialchars($login_name, ENT_QUOTES, 'UTF-8'); ?></p>
        <p>検索URL: <code><?php echo htmlspecialchars($search_url, ENT_QUOTES, 'UTF-8'); ?></code></p>
        <p data-bind="text: message, visible: message"></p>

        <footer>
            <a href="/credits">権利表記</a> /
            <a href="/logout">ログアウト</a> /
            <button type="button" data-bind="click: deleteAccount">アカウント削除</button>
        </footer>
    </div>

    <script src="/assets/js/knockout.js"></script>
    <script src="/assets/js/quicksearch.js"></script>
</body>

</html>