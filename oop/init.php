<?php
//initizalize class
session_start();
$a = NULL;
$a['sql']['host'] = 'localhost';
$a['sql']['pwd'] = '';
$a['sql']['dbnm'] = 'artify';
$a['sql']['dbusr'] = 'root';

$a['remberMe']['cookie_name'] = 'BEiWcC';
$a['remberMe']['cookie_expiry'] = MonthToSec(2); // 2 months

$a['session']['u_nm'] = 'user';
$a['session']['tkn_nm'] = 'token';

$a['nav']['brand'] = array(
  'title' => 'Artify.io',
  'url' => 'index.php'
);


$a['nav']['items'] = array(
  'Home' => array(
    'file' => 'index.php'
  ),
  'Catergory' => array(
    'file' => 'category.php',
  ),
  'Create New Post' => array(
    'file' => 'post.php',
    'showOnLogin' => true
  ),
  'Login' => array(
    'file' => 'login.php',
    'showOnLogin' => false
  ),
  'Register' => array(
    'file' => 'reg.php',
    'showOnLogin' => false
  ),
  'Change Profile Info' => array(
    'file' => 'changeInfo.php',
    'visible' => false
  ),
  'Error' => array(
    'file' => '404.php',
    'visible' => false
  ),
  'View Post' => array(
    'file' => 'viewPost.php',
    'visible' => false
  ),
  'Profile' => array(
    'file' => 'viewProfile.php',
    'showOnLogin' => true
  ),
  'Report' => array(
    'file' => 'report.php',
    'visible' => false
  ),
  'Statistic' => array(
    'file' => 'statistic.php',
    'showOnLogin' => true
  ),
  'Apply' => array(
    'file' => 'apply.php',
    'showOnLogin' => true
  ),
  'Apply Artist' => array(
    'file' => 'applyArtist.php',
    'visible' => false
  ),
  'Logout' => array(
    'file' => '../logout.php',
    'showOnLogin' => true
  ),
);

$a['reader'] = '../factory/htmlSnippets/';
$a['css']['source'] = '../css/master.css';


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
ob_start();
header('X-Frame-Options: DENY');
// die(var_dump($_SESSION));

// if (Cookie::check(Settings::get('remberMe>cookie_name')) && !Session::isLogin()) {
//   $hash = Cookie::get(Settings::get('remberMe>cookie_name'));
//   $hashCheck = DB::run()->get('session',array('hash','=',$hash));
//
//   // if ($hashCheck->getCount()) {
//   //   $user = new User($hashCheck->getData()->usr_id);
//   //   $
//   // }
// }
