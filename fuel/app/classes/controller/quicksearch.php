<?php

class Controller_Quicksearch extends Controller
{
    protected $current_user = null;
    protected $config = array();

    public function before()
    {
        parent::before();

        Config::load('quicksearch', true);
        $this->config = Config::get('quicksearch', array());

        Model_Users::create_table();
        Model_Engines::create_table();

        // セッション取得
        $user_id = Session::get('quicksearch_user_id');
        if ($user_id) {
            // ユーザーの存在チェック
            $this->current_user = Model_Users::get_user_by_id($user_id);
            if (! $this->current_user) {
                Session::delete('quicksearch_user_id');
            }
        }
    }

    // レスポンス作成ヘルパー

    protected function render_page($view, array $data = array(), $status = 200)
    {
        return Response::forge(
            View::forge($view, $data),
            $status
        );
    }

    protected function render_error($message, $status = 400)
    {
        return $this->render_page(
            'quicksearch/error',
            array('message' => $message,),
            $status
        );
    }

    protected function json_response(array $data, $status = 200)
    {
        return Response::forge(
            json_encode($data),
            $status,
            array('Content-Type' => 'application/json; charset=utf-8')
        );
    }

    // ログイン必須API用のヘルパー
    protected function require_login_json()
    {
        // ログインしていればnull
        if ($this->current_user) return null;

        // ログインしていなければエラーJSON
        return $this->json_response(array('message' => 'ログインしてください。'), 403);
    }

    protected function login_user($name)
    {
        $user = Model_Users::get_user_by_name($name);
        // セッションとクッキーにユーザー情報を保存
        Session::set('quicksearch_user_id', $user['id']);
        // 30日間有効なクッキーをセット
        Cookie::set('quicksearch_user_name', $user['name'], 60 * 60 * 24 * 30);
        $this->current_user = $user;
    }

    // ユーザー情報の更新
    protected function refresh_current_user()
    {
        if ($this->current_user) {
            $this->current_user = Model_Users::get_user_by_id($this->current_user['id']);
        }
    }

    // ユーザー固有のURLキーの生成
    protected function generate_unique_url_key()
    {
        // 24文字のランダムなURLキーを生成
        // 重複がないことが確認できるまで繰り返す
        do {
            $url_key = substr(hash('sha256', uniqid((string) mt_rand(), true)), 0, 24);
        } while (Model_Users::get_user_by_url_key($url_key));

        return $url_key;
    }

    // ユーザーのURLキーから検索URLを生成
    protected function get_search_url()
    {
        return Uri::base() . 'search/' . $this->current_user['url_key'] . '?q=%s';
    }

    public function action_credits()
    {
        return $this->render_page('quicksearch/credits', array());
    }

    public function action_not_found()
    {
        return $this->render_error('ページが見つかりません。', 404);
    }
}
