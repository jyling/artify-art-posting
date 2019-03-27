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
    $page = 1;
    if (Input::get('page') !== '') {
        $page = Input::get('page');
    }
    $msg = new Message();

    $msg->getMsg('art_purchase', Pagination::getPage(0), array(
        'limit'     => '10',
        'condition' => array(
            'target'   => 'usr_id',
            'operator' => '=',
            'value'    => Session::get('id'),
        ),
    ));
    echo "<center><div id='post' class='text-container'>";
    $msg->generateMsg('messagebox.txt', false);
    echo "</div></center>";
    $page = new Pagination($msg->totalPage('art_purchase',
        array(
            'usr_id',
            '=',
            Session::get('id'),
        )));
}
// else {
//     echo "<center><h1 class='m-5 text-muted'>Welp, there's nothing here...</h1></center>";
// }
?>
</div>
</div>
<?php Page::addFoot();?>