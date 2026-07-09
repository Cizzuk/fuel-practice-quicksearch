<?php

class Model_Engines extends \Model
{
    // enginesテーブルが存在しない場合に作成する
    public static function create_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `engines` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `keyword` VARCHAR(255) NOT NULL,
            `url` TEXT NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY user_engine_unique (user_id, keyword)
        )";
        \DB::query($sql)
            ->execute();
    }

    // --- Create

    // エンジンを追加
    public static function create_engine($user_id, $name, $keyword, $url)
    {
        \DB::insert('engines')
            ->set(array(
                'user_id' => $user_id,
                'name' => $name,
                'keyword' => $keyword,
                'url' => $url,
                'created_at' => \DB::expr('NOW()'),
                'updated_at' => \DB::expr('NOW()'),
            ))
            ->execute();
    }

    // --- Read

    // user_idで取得
    public static function get_engines_by_user_id($user_id)
    {
        return \DB::select('*')
            ->from('engines')
            ->where('user_id', '=', $user_id)
            ->execute()
            ->as_array();
    }

    // user_idとkeywordで取得
    public static function get_engine_by_keyword($user_id, $keyword, $exclude_id = null)
    {
        $query = \DB::select('*')
            ->from('engines')
            ->where('user_id', '=', $user_id)
            ->and_where('keyword', '=', $keyword);

        // 重複チェック用
        if ($exclude_id !== null) {
            $query->and_where('id', '!=', $exclude_id);
        }

        return $query->execute()->current();
    }

    // idで取得
    public static function get_engine_by_id($id)
    {
        return \DB::select('*')
            ->from('engines')
            ->where('id', '=', $id)
            ->execute()
            ->current();
    }

    // user_idで件数を取得
    public static function get_engine_count_by_user_id($user_id)
    {
        $row = \DB::select(\DB::expr('COUNT(*) AS total'))
            ->from('engines')
            ->where('user_id', '=', $user_id)
            ->execute()
            ->current();

        return (int) $row['total'];
    }

    // --- Update

    // idでエンジンを更新
    public static function update_engine($id, $name, $keyword, $url)
    {
        \DB::update('engines')
            ->set(array(
                'name' => $name,
                'keyword' => $keyword,
                'url' => $url,
                'updated_at' => \DB::expr('NOW()'),
            ))
            ->where('id', '=', $id)
            ->execute();
    }

    // --- Delete

    // idでエンジンを削除
    public static function delete_engine($id)
    {
        \DB::delete('engines')
            ->where('id', '=', $id)
            ->execute();
    }

    // user_idで削除
    public static function delete_engines_by_user_id($user_id)
    {
        \DB::delete('engines')
            ->where('user_id', '=', $user_id)
            ->execute();
    }
}