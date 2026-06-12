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
        $sql = "INSERT INTO `users` (
            name, password, url_key, default_engine_id, created_at)
            VALUES (:name, :password, :url_key, :default_engine_id, NOW())";
        \DB::query($sql)
            ->bind('name', $name)
            ->bind('password', $password)
            ->bind('url_key', $url_key)
            ->bind('default_engine_id', $default_engine_id)
            ->execute();
    }

    // --- Read

    // nameで取得
    public static function get_user_by_name($name)
    {
        $sql = "SELECT * FROM `users` WHERE name = :name";
        return \DB::query($sql)
            ->bind('name', $name)
            ->execute()
            ->current();
    }

    // url_keyで取得
    public static function get_user_by_url_key($url_key)
    {
        $sql = "SELECT * FROM `users` WHERE url_key = :url_key";
        return \DB::query($sql)
            ->bind('url_key', $url_key)
            ->execute()
            ->current();
    }

    // --- Update

    // パスワードをidで更新
    public static function update_password($id, $new_password)
    {
        $sql = "UPDATE `users` SET password = :password WHERE id = :id";
        \DB::query($sql)
            ->bind('password', $new_password)
            ->bind('id', $id)
            ->execute();
    }

    // デフォルトエンジンIDをidで更新
    public static function update_default_engine_id($id, $default_engine_id)
    {
        $sql = "UPDATE `users` SET default_engine_id = :default_engine_id WHERE id = :id";
        \DB::query($sql)
            ->bind('default_engine_id', $default_engine_id)
            ->bind('id', $id)
            ->execute();
    }

    // --- Delete

    // ユーザーをidで削除
    public static function delete_user($id)
    {
        $sql = "DELETE FROM `users` WHERE id = :id";
        \DB::query($sql)
            ->bind('id', $id)
            ->execute();
    }
}