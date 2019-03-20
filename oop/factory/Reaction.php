<?php
class Reaction
{
    public function has($id)
    {
        $db = DB::run();

        if ($id == null) {
            $db->multiCon('SELECT *', 'likes', array(
                'usr_id'  => Session::get('id'),
                'post_id' => $id,
            ));
            return ($db->getCount() > 0) ? true : false;
        }
        $db->multiCon('SELECT *', 'likes', array(
            'usr_id'  => Session::get('id'),
            'post_id' => $id,
        ));
        return ($db->getCount() > 0) ? true : false;
    }

    public function add($id, $choice)
    {
        $db = DB::run();
        $db->insert('likes', array(
            'usr_id'  => Session::get('id'),
            'post_id' => $id,
            'value'   => $choice,
        ));
    }

    public function contain($id, $choice)
    {
        $db = DB::run();
        $db->multiCon('SELECT *', 'likes', array(
            'usr_id'  => Session::get('id'),
            'post_id' => $id,
            'value'   => $choice,
        ));
        return ($db->getCount() > 0) ? true : false;
    }

    public function get($id)
    {
        $db = DB::run();
        $db->multiCon('SELECT *', 'likes', array(
            'usr_id'  => Session::get('id'),
            'post_id' => $id,
        ));
        return $db->getResult()[0];
    }

    public function update($like_id, $choice)
    {
        $db = DB::run();
        $db->update('likes', array('like_id' => $like_id), array(
            'value' => $choice,
        ));
    }

    public function remove($data)
    {
        $db = DB::run();
        $db->multiCon('DELETE', 'likes', $data);
    }

    function list($id) {
        $db = DB::run();

        $list = array();
        $db->multiCon('SELECT *', 'likes', array(
            'post_id' => $id,
            'value'   => 0,
        ));
        $list['dislike'] = $db->getCount();
        $db->multiCon('SELECT *', 'likes', array(
            'post_id' => $id,
            'value'   => 1,
        ));
        $list['like'] = $db->getCount();
        return $list;

    }
    public function like($id = null, $choice = 1)
    {
        $output = array();
        if (!$this->has($id)) {
            $this->add($id, $choice);
            $output['success']['add'] = true;
        } else {
            if (!$this->contain($id, $choice)) {
                $data = $this->get($id);
                $this->update($data->like_id, $choice);
                $output['success']['update'] = true;
            } else {
                $data = $this->get($id, $choice);
                $this->remove(array(
                    'like_id' => $data->like_id,
                ));
                $output['success']['remove'] = true;
            }
        }
        $output['success']['list'] = $this->list($id);
        echo json_encode($output);
    }
}
