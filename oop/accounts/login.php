<?php
include_once '../init.php';
Page::addHead("Login");
$username = '';
$pass = '';
if (Input::exist()) {
  $valid = new Validate();
  $valid->check($_POST, array(
    'usrname' => array(
      'required' => true,
      'name' => 'Username'
    ),
    'password' => array(
      'required' => true,
      'name' => 'Password'
    ),
  ));
  if ($valid->passed()) {
    $user = new User();
    $login = $user->login(Input::get('usrname'),Input::get('password'));
    if ($login) {
      echo "Logined";
    }
    else {
      echo "Login Failed";
    }
  }
  else {
    foreach ($valid->getError() as $key => $val) {
      $a = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
      switch ($key) {
        case 'usrname':
          $username .= "<div class='thin-alert alert alert-danger alert-dismissible fade show'>$val $a</div>";
          break;
        case 'password':
          $pass .= "<div class='thin-alert alert alert-danger alert-dismissible fade show'>$val $a</div>";
          break;
      }
  }
}
}

 ?>

 <form class="" action="" method="post">
   <div class="form-group">
     <h1>Login</h1>
     <label><strong>Are you new here ? </strong><br><i>You can <a href="reg.php">Create an account</a> instead</i></label>
   </div>
   <div class="form-group">
     <label for="usrname">Username</label>
     <?php echo $username ?>
     <input class="form-control form-control" type="text" name="usrname" id="usrname" value="" autocomplete="off">
   </div>
   <div class="form-group">
     <label for="password">Password</label>
     <?php echo $pass ?>
     <input class="form-control form-control" type="password" name="password" id="password" value="" autocomplete="off">
   </div>
   <!-- <div class="form-group"> -->
   <?php Captcha::add() ?>
   <!-- </div> -->
   <div class="form-group">
     <p>
    This site is protected by reCAPTCHA and the Google
    <a href="https://policies.google.com/privacy">Privacy Policy</a> and
    <a href="https://policies.google.com/terms">Terms of Service</a> apply.
     </p>
   </div>
   <div class="form-group">
     <input type="checkbox" name="" id="remember" value=""> <label for="remember">Remember me</label>
   </div>
   <div class="form-group">
     <input class="btn btn-primary" type="submit" name="submit" value="Login">
     <input class="btn btn-primary" type="button" name="submit" value="Sign Up" onclick="document.location.href = 'reg.php'">
   </div>
 </form>
</div>
</body>
</html>
