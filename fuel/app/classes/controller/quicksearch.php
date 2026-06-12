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

    protected function json_response(array $data, $status = 200)
    {
        return Response::forge(
            json_encode($data),
            $status,
            array('Content-Type' => 'application/json; charset=utf-8')
        );
    }
}
