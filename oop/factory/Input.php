<?php
class Input {
  public static function exist($type ='post') {
    $type = strtolower($type);
    switch ($type) {
      case 'post':
        return (empty($_POST))? false : true;
      break;
      case 'get':
          return (empty($_GET))? false : true;
      break;
      default:
        return false;
      break;
    }
  }
  public static function has($term){
      if (isset($_POST[$term])) {
        return true;
      }
      elseif (isset($_GET[$term])) {
        return true;
      }
      else {
        return false;
      }
    }
  public static function get($term,$sec = false){
      if (isset($_POST[$term])) {
        return $_POST[$term];
      }
      elseif (isset($_GET[$term]) && $sec === false) {
        return $_GET[$term];
      }
      else {
        return '';
      }
    }
  public static function empty($term) {
    if (empty($_POST[$term])) {
      return true;
    }
    elseif (empty($_GET[$term])) {
      return true;
    }
    else {
      return false;
    }
  }


}
