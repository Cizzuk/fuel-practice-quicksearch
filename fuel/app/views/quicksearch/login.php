<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>Quicksearch</title>
    <link rel="stylesheet" href="/assets/css/quicksearch.css">
</head>

<body>
    <div class="page">
        <h1>Quicksearch</h1>
        <?php if (! empty($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <div class="panel">
            <h2>ログイン</h2>
            <form method="post" action="/login">
                <label>ユーザー名</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($login_name, ENT_QUOTES, 'UTF-8'); ?>" maxlength="20">
                <label>パスワード</label>
                <input type="password" name="password">
                <button type="submit">ログイン</button>
            </form>
        </div>

        <?php if ($signup_allowed): ?>
            <div class="panel">
                <h2>新規登録</h2>
                <form method="post" action="/register">
                    <label>ユーザー名</label>
                    <input type="text" name="name" maxlength="20">
                    <label>パスワード</label>
                    <input type="password" name="password">
                    <label>パスワード確認</label>
                    <input type="password" name="password_confirm">
                    <button type="submit">登録</button>
                </form>
            </div>
        <?php endif; ?>

        <footer>
            <a href="/credits">権利表記</a>
        </footer>
    </div>
</body>

</html>