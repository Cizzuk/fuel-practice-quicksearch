// Knockout.js前提

(function () {
    // Fetch APIを使ってJSONデータをリクエストする関数
    function requestJson(url, options) {
        return fetch(url, options).then(function (response) {
            return response.text().then(function (text) {
                // レスポンスをJSONとしてパース
                var data = {};
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    data = { message: 'サーバーからのレスポンスが不正です。' };
                }

                // エラーレスポンスの場合は、例外として投げる
                if (!response.ok) {
                    throw data;
                }

                return data;
            });
        });
    }

    function SettingsViewModel(root) {
        var self = this;
        self.root = root;
        self.maxEngines = parseInt(root.getAttribute('data-max-engines'), 10) || 0;
        
        self.message = ko.observable('');
        self.isLoading = ko.observable(false);

        self.setMessage = function (text) {
            self.message(text || '');
        };

        self.deleteAccount = function () {
            if (!confirm('アカウントを削除しますか？この操作は元に戻せません。')) {
                return;
            }

            self.isLoading(true);
            requestJson('/account/delete', {
                method: 'POST'
            }).then(function () {
                window.location.href = '/';
            }).catch(function (error) {
                self.setMessage((error && error.message) ? error.message : 'アカウントの削除に失敗しました。');
            }).then(function () {
                self.isLoading(false);
            });
        };
    }

    document.addEventListener('DOMContentLoaded', function () {
        var root = document.getElementById('settings-root');
        if (!root || !window.ko) {
            return;
        }

        ko.applyBindings(new SettingsViewModel(root), root);
    });
})();