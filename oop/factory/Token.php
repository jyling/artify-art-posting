<?php
class Token {
  public static function GenToken(){
    return Session::put(Settings::get('session>tkn_nm'),md5(uniqid()));
  }
  public static function tokenVerify($token = 'token'){
    if () {
      // code...
    }
  }
}
