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

  public static function get($term){
      if (isset($_POST[$term])) {
        return $_POST[$term];
      }
      elseif (isset($_GET[$term])) {
        return $_GET[$term];
      }
      else {
        return '';
      }
    }



}
