<?php

class Controller_Engines extends Controller_Quicksearch
{
    // 検索エンジン一覧を返す
    public function action_api_engines()
    {
        if ($response = $this->require_login_json()) {
            return $response;
        }

        return $this->json_response($this->engines_payload());
    }

    // 検索エンジンの追加および更新
    public function action_api_engine_save()
    {
        if ($response = $this->require_login_json()) {
            return $response;
        }

        if (Input::method() !== 'POST') {
            return $this->json_response(array('message' => '不正なリクエストです。'), 400);
        }

        $id = (int) Input::post('id', 0);
        $name = trim(Input::post('name', ''));
        $keyword = trim(Input::post('keyword', ''));
        $url = trim(Input::post('url', ''));
        $make_default = (bool) Input::post('make_default', false);

        // バリデーション

        if ($name === '' || $keyword === '' || $url === '') {
            return $this->json_response(array('message' => 'すべてのフィールドを入力してください。'), 400);
        }

        if (preg_match('/\s/', $keyword)) {
            return $this->json_response(array('message' => 'キーワードに空白は使えません。'), 400);
        }

        if (strpos($url, '%s') === false) {
            return $this->json_response(array('message' => '検索URLには%sを含めてください。'), 400);
        }

        // この追加/更新でデフォルトの検索エンジンを設定する必要があるか確認
        $should_make_default = ($make_default || self::should_make_default());
        $engine = null;

        // 更新の場合はidがある
        if ($id) {
            $engine = Model_Engines::get_engine_by_id($id);
            if (! $engine || (int) $engine['user_id'] !== (int) $this->current_user['id']) {
                return $this->json_response(array('message' => '編集対象が見つかりません。'), 404);
            }

            $duplicate = Model_Engines::get_engine_by_keyword($this->current_user['id'], $keyword, $id);
            if ($duplicate) {
                return $this->json_response(array('message' => 'キーワードが重複しています。'), 400);
            }

            // 更新
            Model_Engines::update_engine($id, $name, $keyword, $url);
        } else {
            if (Model_Engines::get_engine_count_by_user_id($this->current_user['id']) >= (int) Config::get('quicksearch.max_engines', 10)) {
                return $this->json_response(array('message' => '検索エンジンの上限に達しています。'), 400);
            }

            if (Model_Engines::get_engine_by_keyword($this->current_user['id'], $keyword)) {
                return $this->json_response(array('message' => 'キーワードが重複しています。'), 400);
            }

            // 作成
            Model_Engines::create_engine($this->current_user['id'], $name, $keyword, $url);
            $engine = Model_Engines::get_engine_by_keyword($this->current_user['id'], $keyword);
        }

        // 必要に応じてデフォルトの検索エンジンを更新
        if ($should_make_default && $engine) {
            Model_Users::update_default_engine_id($this->current_user['id'], $engine['id']);
        }

        return $this->json_response($this->engines_payload());
    }

    // 検索エンジンの削除
    public function action_api_engine_delete($id = null)
    {
        if ($response = $this->require_login_json()) {
            return $response;
        }

        if (Input::method() !== 'POST') {
            return $this->json_response(array('message' => '不正なリクエストです。'), 400);
        }

        // エンジンが存在し、かつ現在のユーザーのものであることを確認
        $engine = Model_Engines::get_engine_by_id((int) $id);
        if (! $engine || (int) $engine['user_id'] !== (int) $this->current_user['id']) {
            return $this->json_response(array('message' => '検索エンジンが見つかりません。'), 404);
        }

        // 削除
        Model_Engines::delete_engine($engine['id']);

        // 削除したエンジンがデフォルトの検索エンジンだった場合、デフォルトの検索エンジンをnullにする
        if ((int) $this->current_user['default_engine_id'] === (int) $engine['id']) {
            Model_Users::update_default_engine_id($this->current_user['id'], null);
        }

        return $this->json_response($this->engines_payload());
    }

    // デフォルトの検索エンジンにする必要があるかを返す
    protected function should_make_default()
    {
        // デフォルトの検索エンジンがない
        if (! $this->current_user['default_engine_id']) {
            return true;
        }

        // 検索エンジンが1つもない
        $count = Model_Engines::get_engine_count_by_user_id($this->current_user['id']);
        if ($count === 0) {
            return true;
        }

        return false;
    }

    // 検索エンジン一覧のJSONペイロードを生成
    protected function engines_payload()
    {
        // 最新のユーザーデータを取得
        $this->refresh_current_user();

        // ユーザーの検索エンジンを取得し、デフォルトのフラグを追加
        $engines = Model_Engines::get_engines_by_user_id($this->current_user['id']);
        foreach ($engines as &$engine) {
            $engine['is_default'] = ! empty($this->current_user['default_engine_id']) && (int) $this->current_user['default_engine_id'] === (int) $engine['id'];
        }

        return array(
            'engines' => $engines,
            'default_engine_id' => $this->current_user['default_engine_id'],
            'max_engines' => (int) Config::get('quicksearch.max_engines', 10),
            'search_url' => $this->get_search_url(),
        );
    }
}
