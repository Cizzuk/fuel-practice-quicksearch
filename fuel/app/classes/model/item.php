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
        \DB::query($sql)
            ->execute();
    }

    public static function get_all()
    {
        return \DB::select()
            ->from('items')
            ->execute()
            ->as_array();
    }

    public static function find_by_key($key)
    {
        return \DB::select()
            ->from('items')
            ->where('key', $key)
            ->execute()
            ->current();
    }

    public static function insert($key, $value)
    {
        list($insert_id, $rows_affected) = \DB::insert('items')->set(array(
            'key' => $key,
            'value' => $value,
        ))->execute();
        return $rows_affected;
    }

    public static function update($key, $value)
    {
        return \DB::update('items')
            ->value('value', $value)
            ->where('key', $key)
            ->execute();
    }

    public static function delete($key)
    {
        return \DB::delete('items')
            ->where('key', $key)
            ->execute();
    }
}
