<?php
require_once '../init.php';

Page::addHead();
Page::addNav();
?>



<?php
if (Session::exist('success')) {
    Page::alertUser(Session::flash('success'));
}
?>
<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <div class="container text-center btn-group mt-sm-3 ">
        <a class="btn btn-primary active" href="#">Home</a>
        <a class="btn btn-primary" href="category.php">Category</a>
        <a class="btn btn-primary" href="followed.php">Followed</a>
    </div>
    <div class="container container-full container-sm">
        <center>
            <h1 class="mb-sm-5">Face of the Artify</h1>
            <form class="">
                <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                <button class="btn btn-block mt-sm-2 btn-primary btn-rounded" type="submit">Search</button>
            </form>
        </center>
        <div></div>
    </div>
</div>
<?php
$page = 1;
if (Input::get('page') !== '') {
    $page = Input::get('page');
}

$msg = new Message();
$msg->getMsg('post', Pagination::getPage(0), array(
    'limit' => '15',
));
?>
<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <?php
echo "<center><div id='post' class='text-container'>";
$msg->generateMsg();
echo "</div></center>";
$pag = new Pagination($msg->totalPage());
?>
</div>
</div>
<?php Page::addFoot();

?>