<?php

class Controller_Demo extends \Controller
{
    public function before()
    {
        parent::before();
        Model_Item::create_table();
    }

    // Read
    public function action_index()
    {
        $data['items'] = Model_Item::get_all();
        return \View::forge('demo/read', $data);
    }

    // Create
    public function action_create()
    {
        if (\Input::method() == 'POST') {
            $key = \Input::post('key');
            $value = \Input::post('value');
            if ($key && $value) {
                $existing = Model_Item::find_by_key($key);
                if (!$existing) {
                    Model_Item::insert($key, $value);
                }
            }
            \Response::redirect('demo');
        }
        return \View::forge('demo/create');
    }

    // Update
    public function action_update()
    {
        if (\Input::method() == 'POST') {
            $key = \Input::post('key');
            $value = \Input::post('value');
            if ($key && $value) {
                Model_Item::update($key, $value);
            }
            \Response::redirect('demo');
        }
        return \View::forge('demo/update');
    }

    // Delete
    public function action_delete()
    {
        if (\Input::method() == 'POST') {
            $key = \Input::post('key');
            if ($key) {
                Model_Item::delete($key);
            }
            \Response::redirect('demo');
        }
        return \View::forge('demo/delete');
    }
}
