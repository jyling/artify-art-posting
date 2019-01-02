<?php
//initizalize class
session_start();
$a = NULL;
$a['sql']['host'] = 'localhost';
$a['sql']['pwd'] = '';
$a['sql']['dbnm'] = 'fyptestground';
$a['sql']['dbusr'] = 'root';

$a['remberMe']['cookie_name'] = 'BEiWcC';
$a['remberMe']['cookie_expiry'] = MonthToSec(2); // 2 months

$a['session']['u_nm'] = 'user';
$a['session']['tkn_nm'] = 'token';

$a['nav']['brand'] = array(
  'title' => 'FYP',
  'url' => 'index.php'
);

$a['nav']['items'] = array(
  'Home' => 'index.php',
  'Login' => 'login.php',
  'Register' => 'reg.php'
);

$a['reader'] = 'factory/htmlSnippets/';
$a['css']['source'] = 'css/master.css';



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


if (Cookie::check(Settings::get('remberMe>cookie_name')) && !Session::isLogin()) {
  $hash = Cookie::get(Settings::get('remberMe>cookie_name'));
  $hashCheck = DB::run()->get('session',array('hash','=',$hash));

  if ($hashCheck->getCount()) {
    $user = new User($hashCheck->getData()->usr_id);
    $
  }
}
