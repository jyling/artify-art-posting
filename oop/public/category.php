<?php
require_once '../init.php';

Page::addHead();
Page::addNav();
$category = '1';
$title    = '';

$db = DB::Run();
if (Input::has('category')) {
    $category = Input::get('category');
    $db->get('art_category', array('category_id', '=', $category));
    $title = $db->getResult()[0]->name;
} else {
    $db->getAll('art_category');
    $result = $db->getResult();
    foreach ($result as $key) {
        $db->get('post', array('category_id', '=', $key->category_id));

        if ($db->getCount() > 0) {
            $category = $key->category_id;
            $title    = $key->name;
        }
    }
}
?>




<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <div class="container text-center btn-group mt-sm-3 ">
        <a class="btn btn-primary" href="index.php">Home</a>
        <a class="btn btn-primary active" href="#">Category</a>
        <a class="btn btn-primary" href="followed.php">Followed</a>
        <a class="btn btn-primary" href="purchasedArt.php">Purchased Art</a>
    </div>

    <div class="container container-full container-sm">
        <center>
            <h1 class="m-sm-5">Category : <?php echo $title ?></h1>
            <form class="">
                <select class="form-control" name='category' id="categorySelect">
                    <?php
$db->getAll('art_category');
$result = $db->getResult();
foreach ($result as $key) {
    $db->get('post', array('category_id', '=', $key->category_id));
    $selected = '';
    if ($key->category_id == $category) {
        $selected = 'selected';
    }
    echo "<option value='$key->category_id' $selected>$key->name (" . $db->getCount() . ")</option>";
}
?>
                </select>
                <button class="btn btn-block mt-sm-2 btn-primary btn-rounded" type="submit">Search</button>
            </form>
        </center>
    </div>
</div>
<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <?php

$page = 1;
if (Input::get('page') !== '') {
    $page = Input::get('page');
}

$msg = new Message();
$msg->getMsg('post', Pagination::getPage(0), array(
    'limit'     => '10',
    'condition' => array(
        'target'   => 'category_id',
        'operator' => '=',
        'value'    => $category,
    ),
));
echo "<center><div id='post' class='text-container'>";
$msg->generateMsg();
echo "</div></center>";
$page = new Pagination($msg->totalPage(
    array(
        'category_id',
        '=',
        $category,
    )), array(
    'category' => $category,
));
?>
</div>
</div>
<?php Page::addFoot();?>