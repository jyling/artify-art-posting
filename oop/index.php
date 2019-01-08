<?php
require_once 'init.php';

Page::addHead();
Page::addNav();
if (Session::exist('success')) {
  Page::alertUser(Session::flash('success'));
}
?>
<div class="container container-full container-sm">
  <?php
  $user = new User();
  if ($user->getLogin()) {
    echo "<h1 class='text-center'>Welcome Back, " . $user->getData()->fname . ' ' . $user->getData()->lname . "</h1><br>";
    echo '<a class="btn btn-primary btn-outline-primary btn-block" href="logout.php">Logout</a><br>';
    echo '<a class="btn btn-primary btn-warning btn-block" href="changeInfo.php">Change Profile Info</a><br>';
    echo '<a class="btn btn-primary btn-outline-danger btn-block" href="logout.php">Logout</a><br>';
  }
  else {
    echo "<center><h1>Hi there</h1><p>I see that you are not logged in yet</p>";
    echo<<<endl
    <a class="btn btn-primary btn-outline-primary btn-block" href="login.php">Login</a><br>
    <p><strong>Don't have an account?</strong><br><i>Please <a href="reg.php">create a new account</a> here</i></p>
    </center>
endl;
  }
  $page = 1;
  if (Input::get('page') !== '') {
    $page = Input::get('page');
  }

  $msg = new Message();
  $msg->getMsg('msgdummy', Pagination::getPage(0) ,array(
    'limit' => '10'
  ));
  echo "<div class='container'>";
  $msg->generateMsg();
  echo "</div>";
  $pag = new Pagination($msg->totalPage());
   ?>
</div>
</body>
</html>
