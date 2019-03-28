<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
Permission::kick();

?>


    <?php

    function isActive($input) {
        echo (Input::get('choice') == $input)? 'active' : '';
    }
$limit = 2;
$page = 1;
if (Input::get('page') !== '') {
    $page = Input::get('page');
}

// if (!is_numeric($page) || $page <= 0) {
//     Page::redirect($_SERVER['PHP_SELF'].Page::urlGetMaker(array(
//         'choice' => Input::get('choice')
//     )));
// }


if (!Input::has('choice')) {
    $_POST['choice'] = 'user';
}


?>

<center>
<div class="btn-group">
<a class="btn btn-primary <?php isActive('user') ?>" href="<?php echo $_SERVER['PHP_SELF'] . Page::urlGetMaker(array(
    'choice' => 'user'
)) ?>" >Reported User</a>
<a class="btn btn-primary <?php isActive('post') ?>" href="<?php echo $_SERVER['PHP_SELF'] . Page::urlGetMaker(array(
    'choice' => 'post'
)) ?>" >Reported Art</a>
<a class="btn btn-primary <?php isActive('ban') ?> " href="<?php echo $_SERVER['PHP_SELF'] . Page::urlGetMaker(array(
    'choice' => 'ban'
)) ?>" >Banned user</a>
<a class="btn btn-primary <?php isActive('apply') ?>" href="<?php echo $_SERVER['PHP_SELF'] . Page::urlGetMaker(array(
    'choice' => 'apply'
)) ?>" >Apply Artist</a>
</div>
</center>

<?php
if (Input::get('choice') == 'user') {
?>
<div class="container mt-sm-5 border rounded" style='background: #f5f5f5'>
<?php
    # code...
    $listedItem = array(
        'usr'         => array(
            'usrnm' => 'username',
        ),
        'report_user' => array(
            'report_id' => 'report ID',
        'report_type' => 'report type',
        'report_title' => 'report title',
        'report_content' => 'content',
    ),
);

$identifier = array(
    '=' => array(
        'usr'         => 'usr_id',
        'report_user' => 'target_id',
    ));
    
    $condition = ' AND report_user.dismiss  < 1';

    $table = new Statistic();
    $table->built('Reported User',$listedItem, $identifier, $condition,$limit, $page,$tool = 'user');
    $pageCount = $table->getPageCount($limit);

    if ($pageCount <  $page && $pageCount > 0) {
        Page::redirect($_SERVER['PHP_SELF'].Page::urlGetMaker(array(
            'choice' => Input::get('choice')
        )));
    }
    
    $pagin = new Pagination($pageCount,array(
        'choice' => Input::get('choice')
    ));
    ?>
</div>
<?php
}
?>





<?php
if (Input::get('choice') == 'post') {
    # code...
    ?>
<div class="container mt-sm-5 border rounded" style='background: #f5f5f5'>
<?php
$listedItem = array(
    'post'         => array(
        'post_id' => 'username',
    ),
    'report_post' => array(
        'report_id' => 'report ID',
        'report_type' => 'report type',
        'report_title' => 'report title',
        'report_content' => 'content',
    ),
    
);

$identifier = array(
    '=' => array(
        'post'         => 'post_id',
        'report_post' => 'post_id',
    ));
    $condition = ' AND report_post.dismiss  < 1';
    
    $table = new Statistic();
    $table->built('Reported Art',$listedItem, $identifier, $condition,$limit, $page,$tool = 'post');
    $pageCount = $table->getPageCount($limit);

    if ($pageCount <  $page && $pageCount > 0) {
        Page::redirect($_SERVER['PHP_SELF'].Page::urlGetMaker(array(
            'choice' => Input::get('choice')
        )));
    }

    $pagin = new Pagination($pageCount,array(
        'choice' => Input::get('choice')
    ));
    ?>
</div>
<?php
}
?>





<?php
if (Input::get('choice') == 'ban') {
    # code...
    ?>
<div class="container mt-sm-5 border rounded" style='background: #f5f5f5'>
    
<?php
$db = DB::run();

$listedItem = array(
    'usr'   => array(
        'usrnm' => 'username',
    ),
    'ban' => array(
        'ban_id' => 'ban id',
        'ban_date' => 'ban since',
        'ban_till' => 'ban till',
        'ban_reason' => 'reason'
    ));
    
    $identifier = array(
        '=' => array(
            'usr'   => 'usr_id',
            'ban' => 'usr_id',
        ));
$condition = 'AND ban.valid  = 1';

$table = new Statistic();
$table->built('Banned User',$listedItem, $identifier, $condition,$limit, $page,$tool = 'ban');
$pageCount = $table->getPageCount($limit);

if ($pageCount <  $page && $pageCount > 0) {
    Page::redirect($_SERVER['PHP_SELF'].Page::urlGetMaker(array(
        'choice' => Input::get('choice')
    )));
}

$pagin = new Pagination($pageCount,array(
    'choice' => Input::get('choice')
));
?>

</div>
<?php
}
?>




<?php
if (Input::get('choice') == 'apply') {
?>
<div class="container mt-sm-5 border rounded" style='background: #f5f5f5'>
    <?php
$db = DB::run();

$listedItem = array(
    'usr'   => array(
        'usrnm' => 'username',
    ),
    'apply' => array(
        'content' => 'content',
        'apply_id' => 'apply id',
    ));

    $identifier = array(
        '=' => array(
        'usr'   => 'usr_id',
        'apply' => 'usr_id',
    ));
    $condition = 'AND apply.approval  < 1';
    $table = new Statistic();
    $table->built('Applying artist',$listedItem, $identifier, $condition,$limit, $page,$tool = 'apply');
    $pageCount = $table->getPageCount($limit);

    if ($pageCount <  $page && $pageCount > 0) {
        Page::redirect($_SERVER['PHP_SELF'].Page::urlGetMaker(array(
            'choice' => Input::get('choice')
        )));
    }

    $pagin = new Pagination($pageCount,array(
        'choice' => Input::get('choice')
    ));
    ?>
</div>
<?php
}
?>


<?php Page::addFoot();?>