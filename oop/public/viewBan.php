<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
Permission::kick();


if (Input::exist('get') && Input::has('choice')) {
    $ban = new Ban();
    $ban->forcedBanlift(Input::get('user'));
    Page::printModal("Ban lifted", "Ban have been lifted ", 'caution', "statistic.php");
}



$db = DB::run();

$listedItem = array(
    'usr'   => array(
        'usrnm',
    ),
    'ban' => array(
        'ban_id','ban_date', 'ban_till', 'ban_reason'
    ));

$identifier = array(
    '=' => array(
        'usr'   => 'usr_id',
        'ban' => 'usr_id',
    ));
$condition = ' AND ban.ban_id = '. Input::get('ban');

$db->jointTable($listedItem, $identifier, $condition);
$result = $db->getResult()[0];
// print_r($result);
$reportedUserDetail = (new User($result->usrnm))->getData();



?>
<div class="container mt-sm-3 mb-sm-3">
    <center>
        <h1>Banned User</h1>
        <hr>
    </center>
</div>
<div class="container " style="max-width: 701px">
    <div class="wrapper  alert alert-danger">
        <div class="heading">
            <h6>Banned User</h6>
            <hr>
        </div>
        <div class="reportedUser row">
            <div class="col-2">
                <img class=" img-target item-center-block img-thumbnail"
                    src="<?php echo $reportedUserDetail->profileImgPath ?>" style="z-index: 2;"
                    onerror="this.src='../asset/placeholder.png';" alt="Your Image Goes Here" src="#">
            </div>
            <h6 class="col-9">Reported User : <a
                    href="viewProfile.php?user=<?php echo $reportedUserDetail->usr_id ?>"><?php echo $reportedUserDetail->usrnm ?></a>
            </h6>
        </div>
    </div>
    <hr>
    <p class="m-3">
    <?php echo nl2br($result->ban_reason); ?> 
    </p>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <?php
        $usr_id = new User($result->usrnm);
        $usr_id = $usr_id->getData()->usr_id;
        echo Page::hiddenGet();
        ?>
        <input name='user' type="hidden" value="<?php echo $usr_id ?>">
        <input class='btn btn-primary btn-block' name='choice' type="submit" value="Unban">
        <a class='btn btn-danger btn-block' href='statistic.php'>Back</a>
    </form>
</div>