<?php
class customer
{
    public function has($id = null)
    {
        $user;
        if ($id == null) {
            $user = new User(Session::get('id'));
        } else {
            $user = new User($id);
        }
        return (count($this->get($user->getData()->usr_id)) > 0) ? true : false;

    }

    public function get($id)
    {
        DB::run()->get('usr_order', array('usr_id', '=', $id));
        return DB::run()->getResult();
    }
}