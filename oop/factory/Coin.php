<?php

class Coin
{
    public function get($id = null)
    {
        if ($id == null) {
            $id = Session::get('id');
        }
        $db = DB::run();
        $db->get('usr_coin', array('usr_id', '=', $id));
        return $db->getResult();
    }

    public function checkCoin($id = null)
    {
        if ($id == null) {
            $id = Session::get('id');
        }
        return (count($this->get($id)) > 0) ? $this->get($id)[0]->coins : 0;

    }

    public function has($id)
    {
        return (count($this->get($id)) > 0) ? true : false;
    }

    public function update($id, $amount)
    {
        $db = DB::run();
        $db->update('usr_coin', array('usr_id' => $id), array(
            'coins' => $amount,
        ));
    }

    public function insert($id, $amount)
    {
        $db = DB::run();
        $db->insert('usr_coin', array(
            'usr_id' => $id,
            'coins'  => $amount,
        ));
    }

    public function add($id, $amount)
    {
        if ($this->has($id)) {
            $coin = 0;
            $coin = $this->get($id)[0]->coins;
            $coin += $amount;
            $this->update($id, $coin);
        } else {
            $this->insert($id, $amount);
        }
    }

    public function remove($id, $amount)
    {
        if ($this->has($id)) {
            $coin = 0;
            $coin = $this->get($id)[0]->coins;
            $coin -= $amount;
            $this->update($id, $coin);
        }
    }
}