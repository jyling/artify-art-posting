<?php
class Post
{
    private $_data,
        $_db;
    public function __construct($post = null)
    {
        $this->_db = DB::run();
        if ($post != null) {
            if (!$this->find($post)) {
                return false;
            }
            return $this->_data;
        }
    }
    public function getCost($id)
    {
        $data = DB::run()->get('post', array('post_id', '=', $id));
        if ($data->getCount()) {
            return $data->getResult()[0]->cost;
        }
        return 0;
    }

    public function getCategory($id)
    {
        $data = DB::run()->get('art_category', array('category_id', '=', $id));
        if ($data->getCount()) {
            return $data->getResult()[0]->name;
        }
        return 'non';
    }
    public function getData()
    {
        return $this->_data;
    }

    public function insert($params)
    {
        if (!$this->_db->insert('post', $params)) {
            throw new \Exception("Error Processing Request");
        }
    }
    public function getOwner()
    {
        if (count($this->getData()) > 0) {
            $data = $this->getData();
            return $data->usr_id;
        }
    }
    public function isOwner($id = null)
    {
        if (!empty($this->getData())) {
            $data = $this->getData();
            if ($id != null) {
                return ($data->usr_id == $id) ? true : false;
            } else {
                return ($data->usr_id == Session::get('id')) ? true : false;
            }
        }
    }
    public function remove($post)
    {
        $db = DB::run();
        $db->remove('post', array('post_id', '=', $post));
    }
    public function find($post = null)
    {
        if (is_numeric($post)) {
            $data = DB::run()->get('post', array('post_id', '=', $post));
            if ($data->getCount()) {
                $this->_data = $data->getResult()[0];
                return true;
            }
        }
        return false;

    }

}