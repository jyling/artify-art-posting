<?php
require_once '../init.php';

Page::addHead();
Page::addNav();
?>



<?php
if (Session::exist('success')) {
    Page::alertUser(Session::flash('success'));
}
if (!Input::has('terms') || Input::get('terms') == '') {
    // die(var_dump(!Input::has('data')) . ' ' .  var_dump(!Input::empty('data')));
    Page::redirect('index.php');
}

$search = new Search();
$terms  = array(
    'usr'  => array(
        'usrnm'    => Input::get('terms'),
        'nickname' => Input::get('terms'),
    ),
    'post' => array(
        'title'   => Input::get('terms'),
        'content' => Input::get('terms'),
    ),
);
$search->combine($terms);

// echo "<pre>";
// print_r($search->getResult());
// echo "</pre>";

?>

<div class="container container-full container-sm">
    <center>
        <h1 class="mb-sm-5">Search : <?php echo Input::get('data') ?></h1>
        <form method="">
            <input class="form-control" type="text" name="terms" minlength=3 placeholder="Search"
                value="<?php echo Input::get('terms') ?>" aria-label="Search">
            <select class="form-control mt-2" name="choice" id="">
                <option value="usr" <?php echo (Input::get('choice') == 'usr') ? 'selected' : ''; ?>>User</option>
                <option value="post" <?php echo (Input::get('choice') == 'post') ? 'selected' : ''; ?>>Post</option>
            </select>
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

?>
<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <?php
echo "<center><div id='post' class='text-container'>";

$choice = 'usr';
if (Input::get('choice') !== '') {
    $choice = Input::get('choice');
}

$search->genCards($terms, $choice);
echo "</div></center>";

// $pag = new Pagination($msg->totalPage());
 ?>
</div>
</div>
<?php Page::addFoot();

?>