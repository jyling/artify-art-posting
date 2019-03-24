<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
$currentUserID = null;
if (!Session::exist('id') && !Input::get('user')) {Page::redirect('index.php');}
// if (Input::has('follow')) {
//     $userClass = new User();
//     if ($userClass->find(Input::get('follow'))) {
//         $follow = new Follow();
//         $follow->follow(Session::get('id'),Input::get('follow'));
//     }
// }
if (Input::has('user')) {
    $currentUserID = Input::get('user');
    $userClass     = new User($currentUserID);
    if (!$userClass->find($currentUserID)) {
        Page::redirect($_SERVER['PHP_SELF']);
    }
} else {
    $currentUserID = Session::get('id');
}
$userClass  = new User($currentUserID);
$detail     = $userClass->getData();
$permission = $userClass->getPermission($currentUserID)->usr->permission;
$rule       = new Permission();
?>
<center>
    <?php echo "<input type='hidden' id='userId' value='$detail->usr_id'>"; ?>
    <div class="container mt-sm-5 border rounded" style='background: #f5f5f5'>
        <div class="box text-center">
            <img class='img-thumbnail mt-sm-2' src="<?php echo Image::imgToBase64($detail->profileImgPath) ?>" alt="">
            <h1 id='profile-usrname'><?php echo $detail->usrnm . "  (  $detail->nickname  )" ?></h1>
            <?php
//follower
if (($permission->post == true) &&
    (Input::get('user') != Session::get('id')) &&
    (Input::has('user'))) {
    $follow        = new Following();
    $followerCount = $follow->list($currentUserID);
    $title         = (!$follow->has($currentUserID)) ? "Follow ($followerCount)" : "Unfollow ($followerCount)";
    Anchor::build(array(
        'title'   => $title,
        'class'   => array(
            "btn",
            "btn-primary",
            "m-sm-2",
        ),
        'id'      => array(
            'follow',
        ),
        'onclick' => "follow($currentUserID)",
    ));
}
//apply artist
$role = (new User())->getPermission(Session::get('id'))->usr->accType;
if ((Input::get('user') == Session::get('id') || !Input::has('user')) && !($role == 'artist' || $role == 'ban' || $role == 'mod' || $role == 'admin')) {
    $form   = new Document();
    $result = $form->has('apply', array(
        'usr_id', '=', Session::get('id'),
    ));
    $apply_data = DB::run()->multiCon('SELECT *', 'apply', array(
        'usr_id'   => Session::get('id'),
        'approval' => 0,
    ))->getResult();
    if (!empty($apply_data)) {
        if ($result->exist && !$apply_data[0]->approval) {
            echo Anchor::build(array(
                'title' => 'Apply for Artist (Pending)',
                'class' => array("btn", "btn-primary", "m-sm-2", "disabled"),
            ));
        }

    } else {
        Anchor::build(array(
            'title' => 'Apply for Artist',
            'link'  => 'applyArtist.php',
            'class' => array("btn",
                "btn-primary",
                "m-sm-2"),
        ));
    }
}

if ((new User)->getLogin()) {
    if (Input::has('user') &&
        (Input::get('user') != Session::get('id'))
    ) {
        Anchor::build(array(
            'title' => "Report",
            'link'  => "report.php?user=" . Input::get('user'),
            'class' => array("btn",
                "btn-danger",
                "m-sm-2",
            ),
        ));
    }
    if (
        Input::get('user') == Session::get('id') ||
        !Input::has('user')) {
        Anchor::build(array(
            "title" => 'Edit',
            "link"  => "changeInfo.php",
            "class" => array("btn",
                "btn-info",
                "m-sm-2",
            ),
        ));
    }

    if ((new User())->getPermission(Session::get('id'))->usr->accType == 'admin' &&
        Input::has('user') &&
        (Input::get('user') != Session::get('id'))
    ) {
        Anchor::build(array(
            "title" => 'Make Mod',
            "link"  => "#",
            "class" => array("btn",
                "btn-warning",
                "m-sm-2",
            ),
        ));
    }
}

?>

        </div>
    </div>

    <?php
$role = $userClass->getRole($currentUserID);

echo <<<end
<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <h1 class='m-sm-2 text-center'>$role</h1>
</div>
end;

?>

    <div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
        <div class="box text-center m-sm-5 mt-sm-2">
            <?php
$msg = new Message();
$msg->getMsg('post', Pagination::getPage(0), array(
    'limit'     => '10',
    'condition' => array(
        'target'   => 'usr_id',
        'operator' => '=',
        'value'    => $currentUserID,
    ),
));
echo "<center><div id='post' class='text-container'>";
$msg->generateMsg();
echo "</div></center>";
$page = new Pagination($msg->totalPage(
    array(
        'usr_id',
        '=',
        $currentUserID,
    )), array(
    'user' => $currentUserID,
));
?>
        </div>
    </div>





    <div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
        <h5 class='view-post-comment'>Comment</h5>
        <div class="container">
            <div class="form-group">
                <input class='form-control' id='comment-field' maxlength="200" type="text">
                <div class="error">
                </div>
            </div>
            <div class="form-group">
                <button class='form-control btn btn-primary' id="post-comment-submit">post comment</button>
            </div>
        </div>
        <hr>
        <div class="container" id="comment" style="margin-bottom: 5%;">
            <?php

$comment = new Discussion();
$comment->getDiscussion('discussion', 1, array(
    'limit'     => '3',
    'condition' => array(
        'target'   => 'target_id',
        'operator' => '=',
        'value'    => $currentUserID,
    ),
));
echo $comment->generateDiscussion($currentUserID);
?>
        </div>
        <button id="load-comment" class="btn btn-primary">Load more (
            <?php echo $comment->pageleft($currentUserID, 0, 3); ?> )</button>
        <?php Page::addFoot();?>