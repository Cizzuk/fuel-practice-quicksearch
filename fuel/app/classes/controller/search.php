<?php

class Controller_Search extends Controller_Quicksearch
{
    public function action_index($url_key = null)
    {
        $user = Model_Users::get_user_by_url_key($url_key);
        if (! $user) {
            return $this->render_error('検索URLのキーが正しくありません。', 404);
        }

        // クエリを取得
        $query = trim((string) Input::get('q', ''));
        $engine = null;
        $search_term = $query;

        // クエリを最初のスペースで分割して、2つに分ける
        $parts = explode(' ', $query, 2);
        if (count($parts) === 2) {
            $keyword = $parts[0];
            $potential_search_term = $parts[1];

            // 検索エンジンをDBから探して、一致するものがあればそれを使用する
            $engines = Model_Engines::get_engine_by_keyword($user['id'], $keyword);
            if ($engines) {
                $engine = $engines;
                $search_term = $potential_search_term;
            }
        }

        // エンジンが見つからない場合は、デフォルトのエンジンを使用
        if (! $engine && ! empty($user['default_engine_id'])) {
            $engine = Model_Engines::get_engine_by_id($user['default_engine_id']);
        }

        // それでもエンジンが見つからない場合は、エラーを返す
        if (! $engine) {
            return $this->render_error('デフォルトの検索エンジンが設定されていません。', 404);
        }

        // 検索URLを生成
        $target_url = str_replace('%s', urlencode($search_term), $engine['url']);
        
        return Response::redirect($target_url, 'location', 303);
    }
}
