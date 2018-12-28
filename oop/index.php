<?php
require_once 'init.php';

Page::addHead();
Page::addNav();
print_r($_GET);
if (Session::exist('success')) {
  Page::alertUser(Session::flash('success'));
}
?>
<div class="container container-sm">
  <?php
  if (Session::exist('usrname')) {
    if (Session::get('usrname')) {
      echo "<h1 class='text-center'>Welcome Back, " . Session::get('fullname') . "</h1><br>";
      echo '<a class="btn btn-primary btn-outline-primary btn-block" href="logout.php">Logout</a><br>';
    }
  }
  else {
    echo "<center><h1>Hi there</h1><p>I see that you are not logged in yet</p>";
    echo<<<endl
    <a class="btn btn-primary btn-outline-primary btn-block" href="login.php">Login</a><br>
    <p><strong>Don't have an account?</strong><br><i>Please <a href="reg.php">create a new account</a> here</i></p>
    </center>
endl;
  }
   ?>
</div>
</body>
</html>
