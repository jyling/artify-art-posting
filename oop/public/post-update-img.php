<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
$usr = new User();
// print_r($usr->getData());

$ban = new Ban();
if ($ban->has(Session::get('id'))) {
    if ($ban->valid(Session::get('id'))) {
        Page::printModal("Banned", "You are currently been banned for " . $ban->banleft(Session::get('id')) . ", You can not post and comment", 'info', 'index.php');
    }

}

var_dump($_POST);

if (Input::exist()) {
    $img = new Image();
    $img->getFile('image', array(
        'minSize'   => 20,
        'maxSize'   => $img->Mb2byte(4),
        'minWidth'  => 600,
        'minHeight' => 400,
        'maxHeight' => 5080,
        'maxWidth'  => 5080,
        'type'      => array(
            'png',
            'jpg',
            'jpeg',
        ),
    ));
    if ($img->getPassed()){
        $post = new Post();
        $path       = $img->addToPath(array('Post', $usr->getData()->usrnm));
        $thumbnail  = $img->compress($path, array('Post', $usr->getData()->usrnm), 'thumbnail', 30);
        $Compressed = $img->compress($path, array('Post', $usr->getData()->usrnm), 'compressed', 70);
        $post->update(array(
            'artPathRaw'    => $path,
            'artThumbnail'  => $thumbnail,
            'artCompressed' => $Compressed,
        ), Input::get('post')
    );
    $post = new Post(Input::get('post'));
    $read = new Reader();
    $read->read('modal.txt');
    if ($read->success()) {
        Session::flash('modal', $read->modify(array(
            '$modalTitle'   => 'Success!!!',
            '$modalcontent' => "Post Updated",
            '$buttonType'   => 'success',
        )));
    }
    Page::redirect("viewPost.php?post=" . Input::get('post'));
} else {
    die(print_r($result->getError()) . print_r($img->getError()));
}   
}
$Postdata;
if (Input::has('post')) {
    $post = new Post(Input::get('post'));
    if (!$post->isOwner()) {
       Page::printModal("Banned", "You are currently been banned for " . $ban->banleft(Session::get('id')) . ", You can not post and comment", 'info', 'index.php');
    }
    $Postdata = $post->getData();
}

?>
<div class="container mt-5">
<center>
<form method="post"  enctype="multipart/form-data">
<input class="form-control mb-2" style="width: 50%" name="image"
                        onchange="reloadImage(this ,true)" type="file" name="" value="">
                    <div class="post-img-container text-center col-md-4">
                        <img class="post-img-content img-target item-center-block img-thumbnail post-block"
                            style="z-index: 2;" onerror="this.src='<?php echo Image::imgToBase64($Postdata->artPathRaw); ?>';"
                            alt="Your Image Goes Here" src="#">
                    </div>
                    <?php Page::hiddenGet();?> 
                    <input class=" form-control btn btn-primary mt-2"  style="width: 50%"  type="submit" name="submit" value="update">
</form>
</center>

</div>
<?php



Page::addFoot();