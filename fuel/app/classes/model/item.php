<?php

class Model_Item extends \Model
{
    public static function create_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `items` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `key` TEXT NOT NULL UNIQUE,
            `value` TEXT NOT NULL
        )";
        \DB::query($sql)->execute();
    }

    public static function get_all()
    {
        return \DB::select()->from('items')->execute()->as_array();
    }
}
