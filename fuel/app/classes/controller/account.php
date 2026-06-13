<?php

class Controller_Account extends Controller_Quicksearch
{
    public function action_index()
    {
        // ログイン済みなら設定ページへリダイレクト
        if ($this->current_user) {
            return Response::redirect('settings');
        }

        // ログインページを表示
        return $this->render_page('quicksearch/login', array(
            'signup_allowed' => (bool) Config::get('quicksearch.allow_signup', true),
            'login_name' => Cookie::get('quicksearch_user_name', ''),
            'error_message' => Session::get_flash('quicksearch_message', ''),
        ));
    }

    public function action_login()
    {
        if (Input::method() !== 'POST') {
            return Response::redirect('/');
        }

        $name = trim(Input::post('name', ''));
        $password = trim(Input::post('password', ''));
        $user = Model_Users::get_user_by_name($name);

        if (! $user || ! $this->verify_password($password, $user['password'])) {
            Session::set_flash('quicksearch_message', 'ユーザー名かパスワードが違います。');
            return Response::redirect('/');
        }

        $this->login_user($user['name']);
        return Response::redirect('settings');
    }

    public function action_register()
    {
        if (! Config::get('quicksearch.allow_signup', true)) {
            Session::set_flash('quicksearch_message', '新規登録は停止されています。');
            return Response::redirect('/');
        }

        if (Input::method() !== 'POST') {
            return Response::redirect('/');
        }

        $name = trim(Input::post('name', ''));
        $password = trim(Input::post('password', ''));
        $password_confirm = trim(Input::post('password_confirm', ''));

        if ($name === '' || $password === '' || $password_confirm === '') {
            Session::set_flash('quicksearch_message', '入力が足りません。');
            return Response::redirect('/');
        }

        // ユーザー名のバリデーション (英数字と_のみ, 3-20文字)
        if (! preg_match('/^[a-zA-Z0-9_]{3,20}$/', $name)) {
            Session::set_flash('quicksearch_message', 'ユーザー名は英数字とアンダースコアのみで、3文字以上20文字以下である必要があります。');
            return Response::redirect('/');
        }

        if ($password !== $password_confirm) {
            Session::set_flash('quicksearch_message', 'パスワードが一致しません。');
            return Response::redirect('/');
        }

        if (Model_Users::get_user_by_name($name)) {
            Session::set_flash('quicksearch_message', 'そのユーザー名は既に使われています。');
            return Response::redirect('/');
        }

        $url_key = $this->generate_unique_url_key();
        Model_Users::create_user($name, $this->hash_password($password), $url_key);
        $this->login_user($name);

        return Response::redirect('settings');
    }

    public function action_logout()
    {
        Session::delete('quicksearch_user_id');
        Cookie::delete('quicksearch_user_name');
        return Response::redirect('/');
    }
}
