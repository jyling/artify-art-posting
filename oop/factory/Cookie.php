<?php
class Cookie {
  public static function check($check) {
    return  (isset($_COOKIE[$check]))? true : false;
  }
  public static function bake($name,$val,$time) {
    if (setcookie($name,$val,time() + $time, '/')) {
    return true;
    }
    else {
      return false;
    }
  }
  public static function destroy($name) {
    self::bake($name,'', time() - 1);
  }
  public static function get($name) {
    return $_COOKIE[$name];
  }
}
