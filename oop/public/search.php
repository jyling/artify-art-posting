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


?>
<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <?php
echo "<center><div id='post' class='text-container'>";

$choice = 'usr';
if (Input::get('choice') !== '') {
    $choice = Input::get('choice');
}
if (strlen(Input::get('terms')) < 3) {
    echo "<h3 class='text-muted m-3' >You need to have at least 3 character when searching</h3>";
}



else {

    $page = 1;
if (Input::get('page') !== '') {
    $page = Input::get('page');
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

if (!in_array(Input::get('choice'),array_keys($terms))) {
    $choice = array_keys($terms)[0];
    Page::redirect($_SERVER['PHP_SELF'] .Page::urlGetMaker(array(
        'terms' => Input::get('terms'),
        'choice' => $choice
    )));
}

$search->combine($terms,10,$page);

$search->genCards($terms, $choice); 
$pageCount = $search->getResult()->{$choice}->page;
if ($pageCount < $page && $pageCount != 0) {
    Page::redirect($_SERVER['PHP_SELF'] .Page::urlGetMaker(array(
        'terms' => Input::get('terms'),
        'choice' => Input::get('choice')
    )));
}
$pagin = new Pagination($pageCount,array(
    'terms' => Input::get('terms'),
    'choice' => Input::get('choice')
));
}

echo "</div></center>";

 ?>
</div>
</div>
<?php Page::addFoot();

?>