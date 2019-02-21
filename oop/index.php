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

<form class="" action="" method="post"  enctype="multipart/form-data">
  <input type="file" name="image" value="">
  <input type="submit" name="" value="">
</form>
<?php
  if ($_SERVER['REQUEST_METHOD']=='POST') {
    $n = new Image();
    $n->getFile('image',array(
      'minSize' => 20,
      'maxSize' => $n->Mb2byte(4),
      'minWidth' => 20,
      'minHeight' => 20,
      'maxHeight' => 5000,
      'maxWidth' => 5000,
      'type' => array(
        'png',
        'jpeg',
        'jpg',
        'svg',
      ),
    ));
    //get file receive 2 argument where first one is the name of the image and
    //another one is the rules of the image, such as the width, height, size and type
    if ($n->getPassed()) { //check if the image has passed all the requirement
      echo "Valid<br>";
      echo $n->addToPath(array('Samuel Ling')); 
      //first argument is the hirache of the image 
      //when the first argument is true it should return the path of the image on addToPath function
      //the path will need to be stored into the database
    }
    else {
      echo "Invalid";
      $n->printErr();
    }
  }



 ?>
