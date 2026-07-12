<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>検索エンジンの設定 - Quicksearch</title>
    <link rel="stylesheet" href="/assets/css/quicksearch.css">
</head>

<body>
    <div class="page" id="settings-root" data-max-engines="<?php echo (int) $max_engines; ?>">
        <h1>検索エンジンの設定</h1>
        <p>ログイン中のアカウント: <?php echo htmlspecialchars($login_name, ENT_QUOTES, 'UTF-8'); ?></p>
        <p>検索URL: <code><?php echo htmlspecialchars($search_url, ENT_QUOTES, 'UTF-8'); ?></code></p>
        <p><small>このURLをあなたのブラウザの既定の検索エンジンに設定してください。</small></p>
        <p class="error-message" data-bind="text: errorMessage, visible: errorMessage"></p>

        <!-- 検索エンジン編集のシート -->

        <div class=settings-sheet data-bind="visible: isEditing">
            <div class="panel">
                <h2>検索エンジンの編集</h2>
                <form data-bind="submit: saveEngine">
                    <input type="hidden" data-bind="value: form.id">
                    <label>名前</label>
                    <input type="text" data-bind="value: form.name, valueUpdate: 'afterkeydown'">
                    <label>キーワード</label>
                    <input type="text" data-bind="value: form.keyword, valueUpdate: 'afterkeydown'">
                    <small>このキーワードを先頭に入力すると、この検索エンジンで検索します</small><br>
                    <label>検索URL</label>
                    <input type="text" data-bind="value: form.url, valueUpdate: 'afterkeydown'">
                    <small>クエリを%sで置き換えます</small><br>
                    <label><input type="checkbox" data-bind="checked: form.makeDefault"> この検索エンジンを規定にする</label>
                    <button type="submit" data-bind="enable: canSave">保存</button>
                    <button type="button" data-bind="click: closeForm">キャンセル</button>
                    <button type="button" data-bind="click: deleteEngine, visible: form.id">削除</button>
                </form>
                <p class="error-message" data-bind="text: errorMessage, visible: errorMessage"></p>
            </div>
        </div>

        <!-- 検索エンジン一覧 -->

        <div class="panel">
            <h2>検索エンジンの一覧</h2>
            <p data-bind="visible: engines().length === 0">検索エンジンがありません。</p>

            <div data-bind="foreach: engines, visible: engines().length > 0">
                <div class="engine-item" data-bind="click: $parent.editEngine">
                    <div>
                        <b data-bind="text: name"></b>
                        <small data-bind="visible: is_default">(規定)</small>
                    </div>
                    <div>
                        <span data-bind="text: keyword"></span>
                        <small data-bind="text: url"></small>
                    </div>
                </div>
            </div>

            <!-- アカウント削除のシート -->

            <div class="settings-sheet" data-bind="visible: isDeletingAccount">
                <div class="panel">
                    <h2>アカウント削除</h2>
                    <p>アカウントを削除すると、登録した検索エンジンの情報もすべて削除されます。</p>
                    <form data-bind="submit: confirmDeleteAccount">
                        <label>パスワード</label>
                        <input type="password" data-bind="value: deletePassword, valueUpdate: 'afterkeydown'">
                        <button type="submit" data-bind="enable: canSave">削除する</button>
                        <button type="button" data-bind="click: cancelDeleteAccount">キャンセル</button>
                    </form>
                    <p class="error-message" data-bind="text: errorMessage, visible: errorMessage"></p>
                </div>
            </div>

            <button type="button" data-bind="click: addNewEngine, visible: engines().length < maxEngines">新しい検索エンジンを追加</button>
            <p class="error-message" data-bind="visible: engines().length >= maxEngines">登録可能な検索エンジンの最大数に達しました。</p>
        </div>

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