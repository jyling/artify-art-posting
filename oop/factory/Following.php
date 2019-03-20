<?php
class Following {
    private $_db;
    public function add($user,$target) {
        $this->_db->get('post',array('usr_id','=',$target));
        if ($this->_db->getCount() > 0) {
            $this->_db->insert('follow',array(
                'usr_id' => $user,
                'target_id' => $target
            ));
            return true;
        }
        else {
            return false;
        }
    }
    public function list($target) {
        $this->_db = DB::run();
        $this->_db->get('follow',array('target_id', '=' , $target));
        return $this->_db->getCount();
    }
    public function remove($target = array()) {
        $this->_db->multiCon('DELETE','follow',$target);
    }
    public function has($target) {
        $this->_db = DB::run();
        if (is_numeric($target)) {
            return $this->get(array(
                'usr_id' => Session::get('id'),
                'target_id' => $target
            ));
        }
        else {
            return false;
        }
    }
    public function follow($user,$target){
        $val = array();
        $usr = new User($user);
        $usr = $usr->getData()->usrnm;
        $tar_name = new User($target);
        $tar_name = $tar_name->getData()->usrnm;

        if ($this->get(array(
            'usr_id' => $user,
            'target_id' => $target
        ))) {
            $this->remove(array(
            'usr_id' => $user,
            'target_id' => $target  
            ));
            $val['success']['target'] = $tar_name;
            $val['success']['removed'] = true;
            $val['success']['count'] = $this->list($target);

        }
        else {
         if ($this->add($user,$target)) {
             $val['success']['target'] = $tar_name;
             $val['success']['removed'] = false;
             $val['success']['count'] = $this->list($target);
         }
         else {
            $val['failed'];
         }

        }
        return json_encode($val);

    }
    public function get($target = array()){
        $this->_db = DB::run();
        $this->_db->multiCon('SELECT *','follow',$target);
        if($this->_db->getCount() > 0) {
            return true;
        }
        else {
            return false;
        }
    }
    public function getCount($user){
        $this->_db = DB::run();
        $this->_db->get('follow',array('target_id','=',$user));
    }
}

