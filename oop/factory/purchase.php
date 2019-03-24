<?php
class purchase
{
    public function get($post)
    {
        $db = DB::run();
        $db->multiCon('SELECT *', 'art_purchase', array(
            'usr_id'  => Session::get('id'),
            'post_id' => $post,
        ));
        return $db->getResult();
    }

    public function getById($post)
    {
        $db = DB::run();
        $db->get('art_purchase', array(
            'purchase_id', '=', $post,
        ));
        return $db->getResult();
    }
    public function getCount()
    {
        $db = DB::run();
        $db->get('art_purchase', array('usr_id', '=', Session::get('id')));
        return $db->getCount();
    }
    public function getPost($post)
    {
        $db = DB::run();
        $db->get('post', array('post_id', '=', $post));
        return $db->getResult();
    }

    public function has($post)
    {
        return (Count($this->get($post)) > 0) ? true : false;
    }
    public function Own($id)
    {
        $db = DB::run();
        $db->multiCon('SELECT *', 'art_purchase', array(
            'usr_id'      => Session::get('id'),
            'purchase_id' => $id,
        ));
        return (Count($db->getResult()) > 0) ? true : false;
    }
    public function add($post)
    {
        $db = DB::run();
        if ($this->has($post)) {
            return false;
        }
        if (Count($this->getPost($post)) > 0) {
            $db->insert('art_purchase', array(
                'usr_id'        => Session::get('id'),
                'post_id'       => $post,
                'artPath'       => $this->getPost($post)[0]->artPathRaw,
                'compressedImg' => $this->getPost($post)[0]->artCompressed,

            ));
            return true;
        } else {
            return false;
        }
    }

}