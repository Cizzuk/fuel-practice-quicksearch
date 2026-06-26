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
        $sql = "INSERT INTO `engines` (
            user_id, name, keyword, url, created_at, updated_at)
            VALUES (:user_id, :name, :keyword, :url, NOW(), NOW())";
        \DB::query($sql)
            ->bind('user_id', $user_id)
            ->bind('name', $name)
            ->bind('keyword', $keyword)
            ->bind('url', $url)
            ->execute();
    }

    // --- Read

    // user_idで取得
    public static function get_engines_by_user_id($user_id)
    {
        $sql = "SELECT * FROM `engines` WHERE user_id = :user_id";
        return \DB::query($sql)
            ->bind('user_id', $user_id)
            ->execute()
            ->as_array();
    }

    // user_idとkeywordで取得
    public static function get_engine_by_keyword($user_id, $keyword, $exclude_id = null)
    {
        $sql = "SELECT * FROM `engines` WHERE user_id = :user_id AND keyword = :keyword";

        // 重複チェック用
        if ($exclude_id !== null) {
            $sql .= " AND id != :exclude_id";
        }

        $query = \DB::query($sql)
            ->bind('user_id', $user_id)
            ->bind('keyword', $keyword);

        if ($exclude_id !== null) {
            $query->bind('exclude_id', $exclude_id);
        }

        return $query->execute()->current();
    }

    // idで取得
    public static function get_engine_by_id($id)
    {
        $sql = "SELECT * FROM `engines` WHERE id = :id";
        return \DB::query($sql)
            ->bind('id', $id)
            ->execute()
            ->current();
    }

    // user_idで件数を取得
    public static function get_engine_count_by_user_id($user_id)
    {
        $sql = "SELECT COUNT(*) AS total FROM `engines` WHERE user_id = :user_id";
        $row = \DB::query($sql)
            ->bind('user_id', $user_id)
            ->execute()
            ->current();

        return (int) $row['total'];
    }

    // --- Update

    // idでエンジンを更新
    public static function update_engine($id, $name, $keyword, $url)
    {
        $sql = "UPDATE `engines` SET
            name = :name,
            keyword = :keyword,
            url = :url,
            updated_at = NOW()
            WHERE id = :id
        ";
        \DB::query($sql)
            ->bind('id', $id)
            ->bind('name', $name)
            ->bind('keyword', $keyword)
            ->bind('url', $url)
            ->execute();
    }

    // --- Delete

    // idでエンジンを削除
    public static function delete_engine($id)
    {
        $sql = "DELETE FROM `engines` WHERE id = :id";
        \DB::query($sql)
            ->bind('id', $id)
            ->execute();
    }

    // user_idで削除
    public static function delete_engines_by_user_id($user_id)
    {
        $sql = "DELETE FROM `engines` WHERE user_id = :user_id";
        \DB::query($sql)
            ->bind('user_id', $user_id)
            ->execute();
    }
}