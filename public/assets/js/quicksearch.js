// Knockout.js前提

(function () {
    const csrfTokenKey = document.querySelector('meta[name="csrf-token-key"]').getAttribute('content');

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

        self.engines = ko.observableArray([]);
        self.errorMessage = ko.observable('');

        self.isEditing = ko.observable(false);
        self.isLoading = ko.observable(false);

        self.isDeletingAccount = ko.observable(false);
        self.deleteAccountPassword = ko.observable('');
        self.deleteAccountErrorMessage = ko.observable('');

        self.form = {
            id: ko.observable(''),
            name: ko.observable(''),
            keyword: ko.observable(''),
            url: ko.observable(''),
            makeDefault: ko.observable(false)
        };

        self.setErrorMessage = function (text) {
            self.errorMessage(text || '');
        };

        self.setDeleteAccountErrorMessage = function (text) {
            self.deleteAccountErrorMessage(text || '');
        };

        self.deleteAccount = function () {
            self.isDeletingAccount(true);
        };

        self.confirmDeleteAccount = function () {
            self.isLoading(true);
            requestJson('/account/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: 'password=' + encodeURIComponent(self.deleteAccountPassword()) +
                    '&' + encodeURIComponent(csrfTokenKey) + '=' + encodeURIComponent(fuel_csrf_token())
            }).then(function () {
                window.location.href = '/';
            }).catch(function (error) {
                self.setDeleteAccountErrorMessage((error && error.message) ? error.message : 'アカウントの削除に失敗しました。');
            }).then(function () {
                self.isLoading(false);
            });
        };

        self.cancelDeleteAccount = function () {
            self.isDeletingAccount(false);
        };

        self.canSave = ko.computed(function () {
            return !self.isLoading();
        });

        self.resetForm = function () {
            self.form.id('');
            self.form.name('');
            self.form.keyword('');
            self.form.url('');
            self.form.makeDefault(false);
            self.setErrorMessage('');
        };

        self.closeForm = function () {
            self.isEditing(false);
            self.resetForm();
        }

        self.addNewEngine = function () {
            self.isEditing(true);
            self.resetForm();
        };

        self.editEngine = function (engine) {
            self.isEditing(true);
            self.form.id(engine.id);
            self.form.name(engine.name);
            self.form.keyword(engine.keyword);
            self.form.url(engine.url);
            self.form.makeDefault(!!engine.is_default);
        };

        self.reload = function () {
            return requestJson('/api/engines').then(function (data) {
                self.engines(data.engines || []);
                self.maxEngines = data.max_engines || self.maxEngines;
                self.setErrorMessage('');
            });
        };

        self.saveEngine = function () {
            self.isLoading(true);
            requestJson('/api/engines/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: 'id=' + encodeURIComponent(self.form.id()) +
                    '&name=' + encodeURIComponent(self.form.name()) +
                    '&keyword=' + encodeURIComponent(self.form.keyword()) +
                    '&url=' + encodeURIComponent(self.form.url()) +
                    '&make_default=' + (self.form.makeDefault() ? '1' : '0') +
                    '&' + encodeURIComponent(csrfTokenKey) + '=' + encodeURIComponent(fuel_csrf_token())
            }).then(function (data) {
                self.engines(data.engines || []);
                self.closeForm();
            }).catch(function (error) {
                self.setErrorMessage((error && error.message) ? error.message : '保存に失敗しました。');
            }).then(function () {
                self.isLoading(false);
            });
        };

        self.deleteEngine = function () {
            if (!confirm('削除しますか。')) {
                return;
            }

            self.isLoading(true);
            requestJson('/api/engines/delete/' + encodeURIComponent(self.form.id()), {
                method: 'POST',
                body: encodeURIComponent(csrfTokenKey) + '=' + encodeURIComponent(fuel_csrf_token())
            }).then(function (data) {
                self.engines(data.engines || []);
                self.resetForm();
                self.setErrorMessage('削除しました。');
            }).catch(function (error) {
                self.setErrorMessage((error && error.message) ? error.message : '削除に失敗しました。');
            }).then(function () {
                self.closeForm();
                self.isLoading(false);
            });
        };

        self.reload();
    }

    document.addEventListener('DOMContentLoaded', function () {
        var root = document.getElementById('settings-root');
        if (!root || !window.ko) {
            return;
        }

        ko.applyBindings(new SettingsViewModel(root), root);
    });
})();