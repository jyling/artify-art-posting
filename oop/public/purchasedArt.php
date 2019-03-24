<?php
require_once '../init.php';

Page::addHead();
Page::addNav();
?>




<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <div class="container text-center btn-group mt-sm-3 ">
        <a class="btn btn-primary" href="index.php">Home</a>
        <a class="btn btn-primary" href="category.php">Category</a>
        <a class="btn btn-primary" href="followed.php">Followed</a>
        <a class="btn btn-primary active" href="#">Purchased Art</a>
    </div>
    <center>
        <h1>Purchased Art</h1>
    </center>
</div>
<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <?php
$db = DB::run();
$db->get('art_purchase', array('usr_id', '=', Session::get('id')));
$purchases = $db->getResult();
// echo "<pre>";
// print_r($purchases);
// echo "</pre>";
$user = new purchase();
if ($user->getCount() > 0) {

    ?>
    <div class="card-deck mb-3">

        <?php

    foreach ($purchases as $purchase => $attr) {
        $author;
        $post_id;
        $msg;
        if ($attr->post_id == null) {
            $author  = 'Deleted';
            $post_id = 'Deleted';
        } else {
            $post       = new Post($attr->post_id);
            $postDetail = $post->getData();
            $usr        = (new User($postDetail->usr_id))->getData();
            $author     = (strlen($usr->usrnm) > 16) ? self::StringOverflow($usr->usrnm) : $usr->usrnm;
            $msg        = (new Post($attr->post_id))->getData();
            $post_id    = $postDetail->post_id;
        }

        $button = "<a target='_blank' href='downloader.php?url=" . $attr->purchase_id . "'>Download</a>";
        $read   = new Reader();
        $read->read('messagebox.txt');
        echo $read->modify(array(
            '$name'    => $author,
            '$fname'   => $author,
            '$artPath' => $attr->compressedImg,
            '$id'      => $post_id,
            '$post_id' => $post_id,
            '$msg'     => $button,
        ));
    }

    ?>
    </div>
    <?php
} else {
    echo "<h1 class='p-3 text-center text-muted'>You havent purchased any art yet</h1>";
}

?>
</div>
</div>
<?php Page::addFoot();?>