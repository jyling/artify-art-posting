<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
$username = '';
$pass = '';
$pass1 = '';
$nickname = '';
$email = '';
if (Session::isLogin()) {
  header('Location: index.php');
}
else {
if (Input::exist()) {

  $valid = new Validate();
  $valid->check($_POST,array(
    'usrnm' => array(
      'required' => true,
      'min' => 3,
      'max' => 30,
      'unique' => 'usr',
      'name' => 'username',
      'startWithChar' => true
    ),
    'password' => array(
      'required' => true,
      'min' => 6,
      'max' => 32,
      'match' => 'second_password',
      'match_name' => 'second password field',
      'name' => 'password',
      'charAndNums' => true
    ),
    'nickname' => array(
      'required' => false,
      'min' => 3,
      'max' => 32,
      'name' => 'nickname',
    ),
    'second_password' => array(
      'required' => true,
      'min' => 6,
      'max' => 32,
      'name' => 'second password field',
      'charAndNums' => true

    ),
    'email' => array(
      'required' => true,
      'min' => '3',
      'max' => '64',
      'regex' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i',
      'name' => 'email'
    ),

  ));
  $captcha = Captcha::verify($_POST);
  if ($valid->passed() && $captcha['success']) {
    $score = $captcha['score'];
    $user = new User();
    try {
      $user->addUser(array(
        'usrnm' => Validate::sanitize(Input::get('usrnm'),true),
        'nickname' => Validate::sanitize(Input::get('nickname'),true),
        'pwd' => PassHasher::getHash(Validate::sanitize(Input::get('password',true))),
        'email' => Validate::sanitize(Input::get('email')),
        'joined' => date('Y-m-d H:i:s'),
        'score' => $score,
      ));
      Session::flash('success',"Sign Up success, you may now login");
      Page::redirect('index.php');
    } catch (\Exception $e) {
      die($e->getMessage());
    }

  }
  else {
    if ($captcha['success'] === false) {
      echo "<div class='thin-alert alert alert-warning alert-dismissible fade show'>" . $captcha['error-codes'][0]. "</div>";
    }
    
    foreach ($valid->getError() as $key => $val) {
      $a = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
      switch ($key) {
        case 'usrname':
          $username .= "<div class='thin-alert alert alert-danger alert-dismissible fade show'>$val $a</div>";
          break;
        case 'password':
          $pass .= "<div class='thin-alert alert alert-danger alert-dismissible fade show'>$val $a</div>";
          break;
        case 'second_password':
          $pass1 .= "<div class='thin-alert alert alert-danger alert-dismissible fade show'>$val $a</div>";
            break;
        case 'fname':
          $fname .= "<div class='thin-alert alert alert-danger alert-dismissible fade show'>$val $a</div>";
            break;
        case 'lname':
          $lname .= "<div class='thin-alert alert alert-danger alert-dismissible fade show'>$val $a</div>";
            break;
        default:
          // code...
          break;
      }
    }
  }
}
}
 ?>
 <div class="container container-sm">
  <form class="" action="" method="post">

      <div class="form-group">
        <h1>Sign Up</h1>
        <label><strong>Have an account ? </strong><br><i>you can <a href="login.php">Login</a> instead</i></label>
      </div>
    <div class="form-group">
      <label for="usrname">Username</label>
      <?php echo $username ?>
      <small id="usernameNote" class="form-text text-muted"><i>Please keep in mind that username <strong>cant be changed</strong> once it was set.</i></small>
      <input class="form-control form-control-sm" type="text" name="usrnm" id="usrname" value="<?php echo Validate::sanitize(Input::get('usrname',true)); ?>" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="fname">Nickname</label>
    <?php echo $nickname ?>
      <small id="nickname" class="form-text text-muted"><i>Name that people will see</i></small>
      <input class="form-control form-control-sm" type="text" name="nickname" id="nickname" value="<?php echo Validate::sanitize(Input::get('nickname',true)); ?>" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <?php echo $pass ?>
      <small id="Helppassword" class="form-text text-muted"><i>Your password <strong>must be longer than 6 character</strong> and <strong> must contain letters and numbers</strong></i></small>
      <input class="form-control form-control-sm" type="password" name="password" id="password" value="990128a" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="second_password">Password Re-enter</label>
      <?php echo $pass1 ?>
      <small id="Helppassword1" class="form-text text-muted"><i>Re-Type the password</i></small>
      <input class="form-control form-control-sm" type="password" name="second_password" id="second_password" value="990128a" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="password">Email</label>
      <?php echo $email ?>
      <small id="email" class="form-text text-muted"><i>Enter your Email</i></small>
      <input class="form-control form-control-sm" type="email" name="email" id="email" value="s.gm@" autocomplete="off">
    </div>
    <div class="form-group">
      <?php Captcha::add() ?>
    </div>
    <button class='btn btn-primary' type="submit" name="submit">Register</button>
  </form>
</div>
<?php Page::addFoot(); ?>
