<?php
//initizalize class
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
spl_autoload_register(function ($class) {
    require_once "factory/$class.php";
});
$a                 = null;
$a['sql']['host']  = 'localhost';
$a['sql']['pwd']   = '';
$a['sql']['dbnm']  = 'artify';
$a['sql']['dbusr'] = 'root';

$a['remberMe']['cookie_name']   = 'BEiWcC';
$a['remberMe']['cookie_expiry'] = MonthToSec(2); // 2 months
$a['api']['tinypic']            = "yf9WBQBMbfRNsVTQ1R2gBW1p5LNT0T1P";
$a['session']['u_nm']           = 'user';
$a['session']['tkn_nm']         = 'token';

$a['nav']['brand'] = array(
    'title' => 'Artify.io',
    'url'   => 'index.php',
);
$a['nav']['items'] = array(
    'Home'                => array(
        'file'       => 'index.php',
        'javascript' => 'view-post',
    ),
    'Catergory'           => array(
        'file'       => 'category.php',
        'javascript' => 'view-post',
        'visible'    => false,

    ),
    'Follow'              => array(
        'file'         => 'followed.php',
        'visible'      => false,
        'RequireLogin' => true,
        'showOnLogin'  => true,

    ),
    'Search'              => array(
        'file'         => 'search.php',
        'visible'      => false,
        'RequireLogin' => true,
        'showOnLogin'  => true,

    ),
    'Purchased Art'       => array(
        'file'         => 'purchasedArt.php',
        'visible'      => false,
        'RequireLogin' => true,
        'showOnLogin'  => true,

    ),
    'Create New Post'     => array(
        'file'         => 'post.php',
        'showOnLogin'  => true,
        'RequireLogin' => true,
        'javascript'   => 'post',
    ),
    'Edit Post'           => array(
        'file'         => 'post-edit.php',
        'visible'      => false,
        'showOnLogin'  => true,
        'RequireLogin' => true,
        'javascript'   => 'post',
    ),
    'Update Image'           => array(
        'file'         => 'post-update-img.php',
        'visible'      => false,
        'showOnLogin'  => true,
        'RequireLogin' => true,
        'javascript'   => 'post',
    ),
    'Login'               => array(
        'file'        => 'login.php',
        'showOnLogin' => false,
    ),
    'Register'            => array(
        'file'        => 'reg.php',
        'showOnLogin' => false,
    ),
    'Change Profile Info' => array(
        'file'         => 'changeInfo.php',
        'visible'      => false,
        'RequireLogin' => true,
        'javascript'   => 'post',

    ),
    'Error'               => array(
        'file'    => '404.php',
        'visible' => false,
    ),
    'View Post'           => array(
        'file'       => 'viewPost.php',
        'javascript' => 'view-post',
        'visible'    => false,
    ),
    'Profile'             => array(
        'file'        => 'viewProfile.php',
        'showOnLogin' => true,
        'javascript'  => 'view-account',
    ),
    'Report'              => array(
        'file'         => 'report.php',
        'javascript'   => 'post',
        'visible'      => false,
        'RequireLogin' => true,

    ),
    'Statistic'           => array(
        'file'         => 'statistic.php',
        'permission'   => array('admin', 'mod'),
        'RequireLogin' => true,
        'showOnLogin'  => true,

    ),
    'Apply(Artist)'       => array(
        'file'         => 'apply.php',
        'visible'      => false,
        'showOnLogin'  => true,
        'RequireLogin' => true,
    ),
    'Apply Artist'        => array(
        'file'         => 'applyArtist.php',
        'visible'      => false,
        'RequireLogin' => true,
        'javascript'   => 'post',
    ),
    'View Reported User'  => array(
        'file'         => 'reportViewUser.php',
        'visible'      => false,
        'RequireLogin' => true,
    ),
    'View Ban'  => array(
        'file'         => 'viewBan.php',
        'visible'      => false,
        'permission'   => array('admin', 'mod'),
        'RequireLogin' => true,
    ),
    "Get Coins"           => array(
        'file'         => 'getCoins.php',
        'showOnLogin'  => true,
        'RequireLogin' => true,
    ),
    'Payment'             => array(
        'file'         => 'payment.php',
        'javascript'   => 'charge',

        'RequireLogin' => true,
        'visible'      => false,
    ),
    'Logout'              => array(
        'file'        => '../logout.php',
        'showOnLogin' => true,
    ),

);

$a['reader']        = '../factory/htmlSnippets/';
$a['css']['source'] = '../css/master.css';

$GLOBALS['settings'] = $a;

function MonthToSec($month = 1)
{
    define('SECPERMONTH', '2592000');
    if ($month < 1 || !preg_match('/^[1-9][0-9]*$/', $month)) {
        return SECPERMONTH;
    } else {
        return SECPERMONTH * $month;
    }
}

date_default_timezone_set('Asia/Kuala_Lumpur');

ob_start();
Page::autoKick();
// Background::get();

header('X-Frame-Options: DENY');