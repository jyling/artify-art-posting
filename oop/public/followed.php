<?php
require_once '../init.php';

Page::addHead();
Page::addNav();
$category = '1';
$title    = '';
$artist   = '';
$empty    = true;

$db   = DB::Run();
$usr  = new User();
$user = $usr->getData();
if (Input::has('artist')) {
    $artist = Input::get('artist');
    $fol    = new Following();
    if (!$fol->get(array(
        'usr_id'    => Session::get('id'),
        'target_id' => $artist,
    ))) {
        Page::redirect($_SERVER['PHP_SELF']);
    }
    $db->get('usr', array('usr_id', '=', $artist));
    $target = new User($artist);
    $artist = $target->getData();
    $title  = $db->getResult()[0]->usrnm;
    if ($db->getCount() > 0) {
        $empty = false;
    } else {
        $empty = true;
    }
} else {
    $db->get('follow', array('usr_id', '=', $user->usr_id));
    $result = $db->getResult();
    foreach ($result as $key) {
        $target = new User($key->target_id);
        $artist = $target->getData();
        $db->get('post', array('usr_id', '=', $key->target_id));
        echo "$key->target_id + " . $db->getCount() . "<br>";
        if ($db->getCount() > 0) {
            $target = $key->target_id;
            $title  = $artist->usrnm;
            $empty  = false;
            break;
        } else {
            $empty = true;
        }
    }
}
?>





<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <div class="container text-center btn-group mt-sm-3 ">
        <a class="btn btn-primary" href="index.php">Home</a>
        <a class="btn btn-primary" href="category.php">Category</a>
        <a class="btn btn-primary active" href="#">Followed</a>
        <a class="btn btn-primary" href="purchasedArt.php">Purchased Art</a>
    </div>
    <center>
        <h1><?php echo $title ?></h1>
        <form class="">
            <select class="form-control" name='artist' id="categorySelect">
                <?php
$db->get('follow', array('usr_id', '=', $user->usr_id));
$result = $db->getResult();
foreach ($result as $key) {
    $selected = '';
    $temp     = new User($key->target_id);
    $temp     = $temp->getData();
    $db->get('post', array('usr_id', '=', $temp->usr_id));
    if ($key->target_id == $target) {
        $selected = 'selected';
    }
    echo "<option value='$key->target_id' $selected>$temp->usrnm (" . $db->getCount() . ")</option>";
}
?>
            </select>
            <button class="btn btn-block mt-sm-2 btn-primary btn-rounded" type="submit">Search</button>
        </form>
    </center>
</div>
<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>

    <?php

$page = 1;
if (Input::get('page') !== '') {
    $page = Input::get('page');
}
$msg = new Message();
if (!$empty) {
    $msg->getMsg('post', Pagination::getPage(0), array(
        'limit'     => '10',
        'condition' => array(
            'target'   => 'usr_id',
            'operator' => '=',
            'value'    => $artist->usr_id,
        ),
    ));
    echo "<center><div id='post' class='text-container'>";
    $msg->generateMsg();

    echo "</div></center>";
    $page = new Pagination($msg->totalPage('post',
        array(
            'usr_id',
            '=',
            $artist->usr_id,
        )), array(
        'artist' => $artist->usr_id,
    ));
} else {
    echo "<center><h1 class='m-5 text-muted'>Welp, there's nothing here...</h1></center>";
}

?>
</div>
</div>
<?php Page::addFoot();?>