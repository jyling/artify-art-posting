<?php
require_once '../init.php';

Page::addHead();
Page::addNav();
?>
<div class="container container-full container-sm">
  <?php
  echo<<<endl
    <center><h1>Category : XXX</h1>
      <form class="">
        <input class="form-control" type="text" placeholder="Search" aria-label="Search">
        <button class="btn btn-block mt-sm-2 btn-primary btn-rounded" type="submit">Search</button>
      </form>
    </center>
endl;


  $page = 1;
  if (Input::get('page') !== '') {
    $page = Input::get('page');
  }

  $msg = new Message();
  $msg->getMsg('msgdummy', Pagination::getPage(0) ,array(
    'limit' => '10'
  ));
  echo "<center><div id='post' class='text-container'>";
  $msg->generateMsg();
  echo "</div></center>";
  $pag = new Pagination($msg->totalPage());
   ?>
</div>
<?php Page::addFoot(); ?>
