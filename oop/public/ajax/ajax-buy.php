<?php
require_once '../../init-nonpublic.php';

$user = new User();
if (!$user->getLogin()) {
    echo json_encode(array('success' => false, 'msg' => 'you are not logged in'));
    die();
}

$coin = new Coin();
$post = new Post(Input::get('post'));
$msg  = $post->getData();
$cost = $msg->cost;
if ($coin->checkCoin() >= $cost) {
    $coin->remove(Session::get('id'), $cost);
    echo json_encode(array('success' => true, 'msg' => 'Post Has be post have been bought, thanks for supporting the artist'));
    die();
} else {
    echo json_encode(array('success' => false, 'msg' => 'insufficient coins'));
    die();
}