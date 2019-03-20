<?php
//initizalize class
session_start();
$a                 = null;
$a['sql']['host']  = 'localhost';
$a['sql']['pwd']   = '';
$a['sql']['dbnm']  = 'artify';
$a['sql']['dbusr'] = 'root';

$a['remberMe']['cookie_name']   = 'BEiWcC';
$a['remberMe']['cookie_expiry'] = MonthToSec(2); // 2 months

$a['session']['u_nm']   = 'user';
$a['session']['tkn_nm'] = 'token';

$a['reader']        = '../factory/htmlSnippets/';
$a['css']['source'] = 'css/master.css';

$GLOBALS['settings'] = $a;
date_default_timezone_set('Asia/Kuala_Lumpur');
function MonthToSec($month = 1)
{
    define('SECPERMONTH', '2592000');
    if ($month < 1 || !preg_match('/^[1-9][0-9]*$/', $month)) {
        return SECPERMONTH;
    } else {
        return SECPERMONTH * $month;
    }
}

require_once 'factory/Input.php';
require_once 'factory/DB.php';
require_once 'factory/Settings.php';
require_once 'factory/Image.php';
require_once 'factory/User.php';
require_once 'factory/Post.php';

ob_start();
header('X-Frame-Options: DENY');

// if (Cookie::check(Settings::get('remberMe>cookie_name')) && !Session::isLogin()) {
//   $hash = Cookie::get(Settings::get('remberMe>cookie_name'));
//   $hashCheck = DB::run()->get('session',array('hash','=',$hash));
//
//   // if ($hashCheck->getCount()) {
//   //   $user = new User($hashCheck->getData()->usr_id);
//   //   $
//   // }
// }