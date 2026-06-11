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
            `default_engine_id` INT,
            `created_at` DATETIME NOT NULL,
        )";
        \DB::query($sql)
            ->execute();
    }
}