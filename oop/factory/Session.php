<?php
class Session {
  public static function put($sessionName,$sessionval){
    return $_SESSION["$sessionName"] = $sessionval;
  }
  public static function exist($name) {
    if (isset($_SESSION[$name])) {
      return true;
    }
    else {
      return false;
    }
  }
  public static function remove($name) {
    if (self::exist($name)) {
      unset($_SESSION[$name]);
    }
  }
  public static function get($name) {
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
    else {
      return '';
    }
  }
}
