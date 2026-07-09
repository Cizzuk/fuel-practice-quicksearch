<?php

class Model_Users extends \Model
{
    // usersテーブルが存在しない場合に作成する
    public static function create_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL UNIQUE KEY,
            `password` VARCHAR(255) NOT NULL,
            `url_key` VARCHAR(255) NOT NULL UNIQUE KEY,
            `default_engine_id` INT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        )";
        \DB::query($sql)
            ->execute();
    }

    // --- Create

    // ユーザーを追加
    public static function create_user($name, $password, $url_key, $default_engine_id = null)
    {
        \DB::insert('users')
            ->set(array(
                'name' => $name,
                'password' => $password,
                'url_key' => $url_key,
                'default_engine_id' => $default_engine_id,
                'created_at' => \DB::expr('NOW()'),
            ))
            ->execute();
    }

    // --- Read

    // nameで取得
    public static function get_user_by_name($name)
    {
        return \DB::select('*')
            ->from('users')
            ->where('name', '=', $name)
            ->execute()
            ->current();
    }

    // url_keyで取得
    public static function get_user_by_url_key($url_key)
    {
        return \DB::select('*')
            ->from('users')
            ->where('url_key', '=', $url_key)
            ->execute()
            ->current();
    }

    // idで取得
    public static function get_user_by_id($id)
    {
        return \DB::select('*')
            ->from('users')
            ->where('id', '=', $id)
            ->execute()
            ->current();
    }

    // --- Update

    // パスワードをidで更新
    public static function update_password($id, $new_password)
    {
        \DB::update('users')
            ->set(array('password' => $new_password))
            ->where('id', '=', $id)
            ->execute();
    }

    // デフォルトエンジンIDをidで更新
    public static function update_default_engine_id($id, $default_engine_id)
    {
        \DB::update('users')
            ->set(array('default_engine_id' => $default_engine_id))
            ->where('id', '=', $id)
            ->execute();
    }

    // --- Delete

    // ユーザーをidで削除
    public static function delete_user($id)
    {
        \DB::delete('users')
            ->where('id', '=', $id)
            ->execute();
    }
}