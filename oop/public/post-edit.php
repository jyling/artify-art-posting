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
$params;
if (Input::exist() && Input::has('post')) {
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
        'cost'        => array(
            'required'  => true,
            'numsRange' => array(
                0, 50,
            ),
            'name'      => 'cost',
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
    if ($result->passed()) {
        $post = new Post();
        $post->update(array(
            'usr_id'      => Session::get('id'),
            'collab'      => $list,
            'title'       => Validate::sanitize(Input::get('title')),
            'content'     => Validate::sanitize(Input::get('description')),
            'category_id' => Input::get('category'),
            'cost'        => Input::get('cost'),
        ), Input::get('post')
        );
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
        Page::printModal('Error', 'Error you do not own this post', 'warning', 'index.php');
    }
    $Postdata = $post->getData();
}

?>
<div class="container" style="margin-top: 5%;">
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <div class="post-title form-group">
                <input class="btn-block col-md-11 form-control" maxlength="64" type="text" placeholder="Title"
                    name="title" value="<?php echo $Postdata->title ?>">

            </div>
            <div class="post-body form-group row">
                <div class="post-info col-md-7">
                    <div class="container">
                        <div class="form-group">
                            <p class="text-center">Art collaboration</p>
                            <select multiple class="form-control" name="collab[]" id="collab">
                                <?php
Page::hiddenGet();
$user       = new User();
$data       = $user->getArtist();
$collabData = explode(',', $Postdata->collab);
foreach ($data as $key => $value) {
    $a = '';
    if (in_array($key, $collabData)) {
        $a = 'selected';
    } else {
        $a = '';
    }
    echo "<option value='$key' $a>$value</option>";
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
    $select = '';
    if ($key->catergory_id == $Postdata->catergory_id) {
        $select = 'selected';
    } else {
        $select = '';
    }
    echo "<option value='$key->category_id' $select>$key->name</option>";
}
?>
                                </select>
                            </div>
                            <p class="text-center">Description</p>
                            <textarea class="col form-control" maxlength='256' placeholder="Description"
                                name="description" rows="4"
                                cols="80"><?php echo Validate::sanitize($Postdata->content) ?></textarea>
                            <p class='text-center'>Cost</p>
                            <input class="col form-control" type="number" name="cost" min="0" max="50"
                                value="<?php echo $Postdata->cost ?>">
                        </div>
                    </div>
                </div>
                <div class="post-img form-group text-center col-md-4">
                    <h3>To update post image, please go <a
                            href="post-update-img.php?post=<?php $Postdata->post_id?>">here</h3>
                </div>
            </div>
            <div class="container">
                <input type="submit" class="btn btn-primary btn-lg col-md-11" name="" value="Post">
            </div>
        </div>
    </form>
</div>

<?php
Page::addFoot();
?>