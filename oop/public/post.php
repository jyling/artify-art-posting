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
if ($usr->getPermission()->usr->permission->post) {
    # code...
    $artistList = $usr->getArtist();
    $usr->find(Session::get('id'));
    if (Input::exist()) {
        $vali   = new Validate();
        $img    = new Image();
        $result = $vali->check($_POST, array(
            'title'       => array(
                'required' => true,
                'min'      => 6,
                'max'      => 64,
                'name'     => 'post title',
            ),
            'category'    => array(
                'required'        => true,
                'existInDatabase' => array(
                    'table'   => 'art_category',
                    'colName' => 'category_id',
                ),
                'name'            => 'category',
            ),
            'description' => array(
                'required' => true,
                'min'      => 6,
                'max'      => 256,
                'name'     => 'post description',
            ),

        ));

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
        $list = array();
        if (Input::has('collab') && !(count(Input::get('collab') <= 0))) {
            foreach (Input::get('collab') as $key => $value) {
                $list[] = $value;
            }
            $list = implode(',', $list);
        }

        if (count($list) <= 0) {
            $list = '';
        }

        if ($result->passed() && $img->getPassed()) {
            $post       = new Post();
            $path       = $img->addToPath(array('Post', $usr->getData()->usrnm));
            $thumbnail  = $img->compress($path, array('Post', $usr->getData()->usrnm), 'thumbnail', 10);
            $Compressed = $img->compress($path, array('Post', $usr->getData()->usrnm), 'compressed', 60);

            $post->insert(array(
                'usr_id'        => Session::get('id'),
                'collab'        => $list,
                'title'         => Validate::sanitize(Input::get('title')),
                'content'       => Validate::sanitize(Input::get('description')),
                'category_id'   => Input::get('category'),
                'artPathRaw'    => $path,
                'artThumbnail'  => $thumbnail,
                'artCompressed' => $Compressed,

            )
            );
            $read = new Reader();
            $read->read('modal.txt');
            if ($read->success()) {
                Session::flash('modal', $read->modify(array(
                    '$modalTitle'   => 'Success!!!',
                    '$modalcontent' => "Post Added",
                    '$buttonType'   => 'success',
                )));
            }
            Page::redirect("index.php");
        } else {
            die(print_r($result->getError()) . print_r($img->getError()));
        }
    }

    ?>
<div class="container" style="margin-top: 5%;">
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <div class="post-title form-group">
                <input class="btn-block col-md-11 form-control" maxlength="64" type="text" placeholder="Title"
                    name="title" value="">
            </div>
            <div class="post-body form-group row">
                <div class="post-info col-md-7">
                    <div class="container">
                        <div class="form-group">
                            <p class="text-center">Art collaboration</p>
                            <select multiple class="form-control" name="collab[]" id="collab">
                                <?php
$user = new User();
    $data = $user->getArtist();
    foreach ($data as $key => $value) {
        echo "<option value='$key'>$value</option>";
    }
    ?>
                            </select>
                            <div class="form-group">
                                <p for="categorySelect" class="text-center">Choose a category</p>
                                <select class="form-control" name='category' id="categorySelect">
                                    <?php
$db = DB::Run();
    $db->getAll('art_category');
    foreach ($db->getResult() as $key) {
        echo "<option value='$key->category_id'>$key->name</option>";
    }
    ?>
                                </select>
                            </div>
                            <p class="text-center">Description</p>
                            <textarea class="col form-control" maxlength='256' placeholder="Description"
                                name="description" rows="4" cols="80"></textarea>
                        </div>
                    </div>
                </div>
                <div class="post-img form-group text-center col-md-4">
                    <input class="form-control" style="margin: 7%; margin-left: 0%;" name="image"
                        onchange="reloadImage(this ,true)" type="file" name="" value="">
                    <div class="post-img-container text-center col-md-4">
                        <img class="post-img-content img-target item-center-block img-thumbnail post-block"
                            style="z-index: 2;" onerror="this.src='../asset/placeholder.png';"
                            alt="Your Image Goes Here" src="#">
                    </div>
                </div>
            </div>
            <div class="container">
                <input type="submit" class="btn btn-primary btn-lg col-md-11" name="" value="Post">
            </div>
        </div>
    </form>
</div>

<?php
} else {
    ?>
<div class="container" style="margin-top: 10%">
    <center>
        <h3 class="text-muted text-center">You dont have the permission to do this</h3>
        <small>Please request to apply to be a artist on your profile inorder to make artpost</small>
    </center>
</div>


<?php
}
Page::addFoot();
?>