<?php
class Settings {
  public static function get($path = null) {
    if ($path) {
      $prefix = $GLOBALS['settings'];
      $path = explode(">",$path);
      foreach ($path as $key) {
        if (isset($prefix[$key])) {
          $prefix = $prefix[$key];
        }
        else {
          return false;
        }
      }
      return $prefix;
    }
  }
}
