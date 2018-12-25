<?php
//initizalize class
session_start();
$a = NULL;
$a['sql']['host'] = 'localhost';
$a['sql']['pwd'] = '';
$a['sql']['dbnm'] = 'FYPTestGround';
$a['sql']['dbusr'] = 'root';

$a['remberMe']['cookie_name'] = '$BEiWcC{IWd*RN 5v[u1#e|7fdeq(5vn~6}^45sv%C*,KP-XJTwrZ%f~6vQ;g1-q';
$a['remberMe']['cookie_expiry'] = MonthToSec(2); // 2 months

$a['session'][`u_nm`] = 'user';



$GLOBALS['settings'] = $a;

function MonthToSec($month = 1){
  define('SECPERMONTH', '2592000');
  if ($month < 1 || !preg_match('/^[1-9][0-9]*$/',$month)) {
    return SECPERMONTH;
  }
  else {
    return SECPERMONTH * $month;
  }
}



spl_autoload_register(function($class){
  require_once "factory/$class.php";
});
