<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
$user = new User();
if (!Input::exist() && !Input::has('post')) {
    Page::redirect('index.php');
}
$post = new Post(Input::get('post'));
if (!$post->find(Input::get('post'))) {Page::redirect('index.php');}
$msg      = $post->getData();
$usr      = new User($msg->usr_id);
$username = $usr->getData()->usrnm;
?>
<div class="container">

    <img src="<?php echo Image::imgToBase64($msg->artCompressed) ?>" class="view-post-img border rounded img-fluid"
        alt="" srcset="">
    <center><a href="imageViewer.php?post=<?php echo $msg->post_id ?>" target="_blank">View High Quality</a></center>
    <div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
        <div class="container text-center">
            <h1 class='view-post-title'>Title : <?php echo $msg->title ?></h1>
            <p class='font-italic'>
                <?php
$follow        = new Following();
$followerCount = $follow->list($msg->usr_id);
$title         = (!$follow->has($msg->usr_id)) ? "Follow ($followerCount)" : "Unfollow ($followerCount)";
?>
                Artist : <a href="<?php echo "viewProfile.php?user=$msg->usr_id" ?>"><?php echo $username ?></a>
                <?php
if ($user->getLogin()) {
    ?>
                <button class='btn btn-primary ml-sm-2' id="follow"
                    onclick="<?php echo "follow($msg->usr_id)" ?>"><?php echo $title ?></button>
                <?php
}


if (!$post->isOwner(Session::get('id'))) {
Anchor::build(array(
    'title' => "Report",
    'link'  => "report.php?post=" . Input::get('post'),
    'class' => array("btn",
        "btn-danger",
        "m-sm-2",
    ),
));
}


if ($post->isOwner(Session::get('id'))) {
    ?>
                <a class="btn btn-info" href="post-edit.php?post=<?php echo $msg->post_id ?>">Edit</a>
                <?php
$read = new Reader();
    $read->read('yesnoModal.txt');
    $output = $read->modify(array(
        '$modalname' => 'deletePost',
        '$openbtn'   => 'Delete',
        '$modalname' => 'deletePost',
        '$title'     => 'Delete Post ?',
        '$content'   => 'Are you sure you want to delete your post ?',
        '$btnType'   => 'danger',
        '$yesID'     => 'delete',
    ));
    echo $output;
    $read = new Reader();
    $read->readBase('../js/removePost.js');
    echo "<script>" . $read->getContent() . "</script>";
}
$buy = new purchase();

// var_dump(!$buy->has($msg->post_id) && $post->getCost($msg->post_id) > 0 && !$post->isOwner(Session::get('id')) && $user->getLogin());
// var_dump(!$buy->has($msg->post_id));
// var_dump($post->getCost($msg->post_id) > 0);
// var_dump(!$post->isOwner(Session::get('id')));
// var_dump($user->getLogin());
// var_dump(!$buy->has($msg->post_id) && $post->getCost($msg->post_id) > 0 && !$post->isOwner(Session::get('id')) && $user->getLogin());

if (!$buy->has($msg->post_id) && $post->getCost($msg->post_id) > 0 && !$post->isOwner(Session::get('id')) && $user->getLogin()) {
    $read = new Reader();
    $read->read('yesnoModal.txt');
    $output = $read->modify(array(
        '$modalname' => 'buy',
        '$openbtn'   => 'Buy for ' . ($post->getCost($msg->post_id)) . ' coins',
        '$modalname' => 'buy',
        '$title'     => 'Buy art for ' . ($post->getCost($msg->post_id)) . ' coins ?',
        '$content'   => 'Are you sure you want purchase the art for ' . ($post->getCost($msg->post_id)) . ' coins ?',
        '$btnType'   => 'warning',
        '$yesID'     => 'buyArt',
    ));
    echo $output;
    $read = new Reader();
    $read->readBase('../js/buy.js');
    echo "<script>" . $read->getContent() . "</script>";

}

?>
                <div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
            </p>
            <p>
                <?php
$react         = new Reaction();
$likeandunlike = (object) $react->list(Input::get('post'));
if ($user->getLogin()) {
    ?>
                <div class="btn-group" role="group">
                    <button class='btn btn-success' id="like" onclick="like(<?php echo $msg->post_id ?>,1)">Like
                        (<?php echo $likeandunlike->like ?>)</button>
                    <button class='btn btn-danger' id="unlike" onclick="like(<?php echo $msg->post_id ?>,0)">Unlike
                        (<?php echo $likeandunlike->dislike ?>)</button>
                </div>
            </p>
            <?php
}
?>
            <hr>
            <p class='font-italic'>Catergory : <a
                    href="<?php echo "category.php?category=$msg->category_id" ?>"><?php echo $post->getCategory($msg->category_id); ?></a>
            </p>
            <?php
if (!empty($msg->collab)) {
    $list   = explode(',', $msg->collab);
    $output = array();
    foreach ($list as $key => $value) {
        $collab = new User($value);
        $data   = $collab->getData();
        if (!empty($data)) {
            $output[] = "<a href='viewProfile.php?user=$data->usr_id'>$data->usrnm</a>";
        }
    }
    echo "<p class='font-italic'>Collaboration : " . implode(',', $output) . "<p>";

}
$comment = new Comment();
?>
            <p>
                <?php echo $msg->content; ?>
            </p>
        </div>
    </div>
    <div class="container">
        <hr>
        <h5 class='view-post-comment' id="comment-count">Comment: <?php echo $comment->getCount(Input::get('post')) ?>
        </h5>
        <div class="container">
            <div class="form-group">
                <input class='form-control' onkeydown="LimitChar(this,200)" id='comment-field' maxlength="200"
                    type="text">
                <small id="character"></small>
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

$comment->getComment('comment', 1, array(
    'limit'     => '3',
    'condition' => array(
        'target'   => 'post_id',
        'operator' => '=',
        'value'    => Input::get('post'),
    ),
));
echo $comment->generateComment(Input::get('post'));
?>
        </div>

        <?php
if ($comment->pageleft(Input::get('post'), 0, 3) - 1 > 0) {
    echo "<button id='load-comment' class='btn btn-primary'>Load more (" . $comment->pageleft(Input::get('post'), 0, 3) . ")</button>";
}
Page::addFoot();
?>