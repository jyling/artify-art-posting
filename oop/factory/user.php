<?php

class User {
  private $_db,
          $_data,
          $_isLoggedIn,
          $_cookieName;
  public function __construct($user = null)
  {
    $this->_cookieName = Settings::get('remberMe>cookie_name');
    $this->_db = DB::run();
    if (!$user) {
        $user = Session::get('id');
        if ($this->find($user)) {
          $this->_isLoggedIn = true;
        }
    } else {
      return $this->find($user);
    }
  }
  public function addUser($params = array()){
    if (!$this->_db->insert('usr',$params)) {
      throw new \Exception("Error Processing Request");
    }
  }
  public function find($term = null) {
    if (is_numeric($term)) {
      $data = DB::run()->get('usr', array('id','=',$term));
    }
    else {
      $data = DB::run()->get('usr', array('usrname','=',$term));
    }
    if ($data->getCount()) {
      $this->_data = $data->getResult()[0];
      return true;
    }

  }
  public function getData(){
      return $this->_data;
  }
  public function verifyPass($pass,$passhash) {
    $passSalt = $pass.$this->_data->salt;
    if (password_verify($passSalt,$passhash)) {
      return true;
    }
    return false;
  }
  public function Login($usrname = null,$pass = null, $remember = false) {
    $username = Validate::sanitize($usrname);
    $pass = Validate::sanitize($pass);
    if($this->find($usrname)) {
      $salt = $this->_data->passhash;
      if ($this->verifyPass($pass,$salt)) {
        if ($remember) {
          $hash = PassHasher::randHash();
          $hashCheck = $this->_db->get('session', array(
            'usr_id','=',$this->getData()->id));
          if (!$hashCheck->getCount()) {
            $this->_db->insert('session',array(
              'usr_id' => $this->getData()->id,
              'hash' => $hash
            ));
          } else {
            $hash = $hashCheck->getResult()[0]->hash;
          }
          Cookie::bake($this->_cookieName,$hash,Settings::get('remberMe>cookie_expiry'));
        }
        return true;
      }
    }
    return false;
  }
  public function getLogin(){
    return $this->_isLoggedIn;
  }
}
