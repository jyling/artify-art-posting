<?php

class User {
  private $_db;
  private $_data;
  public function __construct($user = null)
  {
    $this->_db = DB::run();
  }
  public function addUser($params = array()){
    if (!$this->_db->insert('usr',$params)) {
      throw new \Exception("Error Processing Request");
    }
  }
  public function find($term = null) {
    $data = DB::run()->get('usr', array('usrname','=',$term));
    if ($data->getCount()) {
      $this->_data = $data->getResult()[0];
      return true;
    }

  }
  public function verifyPass($pass,$passhash) {
    $passSalt = $pass.$this->_data->salt;
    if (password_verify($passSalt,$passhash)) {
      return true;
    }
    return false;
  }
  public function Login($usrname = null,$pass = null) {
    if($this->find($usrname)) {
      $salt = $this->_data->passhash;
      if ($this->verifyPass($pass,$salt)) {
        return true;
      }
    }
    return false;
  }
}
