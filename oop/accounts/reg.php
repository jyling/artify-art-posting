<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="../css/master.css">
  <title>Register</title>
</head>
<body>
  <div class="container container-sm">

<?php
require_once '../init.php';
$username = '';
$pass = '';
$pass1 = '';
$fname = '';
$lname = '';

if (Input::exist()) {
  $valid = new Validate();
  $valid->check($_POST,array(
    'usrname' => array(
      'required' => true,
      'min' => 2,
      'max' => 30,
      'unique' => 'usr',
      'name' => 'username'
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
    'second_password' => array(
      'required' => true,
      'min' => 6,
      'max' => 32,
      'name' => 'second password field',
      'charAndNums' => true

    ),
    'fname' => array(
      'required' => true,
      'min' => 2,
      'max' => 50,
      'charOnly' => true,
      'name' => 'first name'
    ),
    'lname' => array(
      'required' => true,
      'min' => 2,
      'max' => 60,
      'charOnly' => true,
      'name' => 'last name'
    )

  ));
  $captcha = Captcha::verify($_POST);
  if ($valid->passed() && $captcha['success']) {
    $score = $captcha['score'];
    $user = new User();
    $salt = PassHasher::salty(32);
    try {
      $user->addUser(array(
        'usrname' => Input::get('usrname'),
        'fname' => Input::get('fname'),
        'lname' => Input::get('lname'),
        'passhash' => PassHasher::getHash(Input::get('password',true),$salt),
        'salt' => $salt,
        'joined' => date('Y-m-d H:i:s'),
        'user_score' => $score,
        'accountType' => 1
      ));
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

 ?>
  <form class="" action="" method="post">
    <div class="form-group">
      <h1>Sign Up</h1>
      <label><strong>Have an account ? </strong><br><i>you can <a href="login.php">Login</a> instead</i></label>
    </div>
    <div class="form-group">
      <label for="usrname">Username</label>
      <?php echo $username ?>
      <input class="form-control form-control-sm" type="text" name="usrname" id="usrname" value="<?php echo Validate::sanitize(Input::get('usrname',true)); ?>" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <?php echo $pass ?>
      <input class="form-control form-control-sm" type="password" name="password" id="password" value="" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="second_password">Password Re-enter</label>
      <?php echo $pass1 ?>
      <input class="form-control form-control-sm" type="password" name="second_password" id="second_password" value="" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="fname">FirstName</label>
      <?php echo $fname ?>
      <input class="form-control form-control-sm" type="text" name="fname" id="fname" value="<?php echo Validate::sanitize(Input::get('fname',true)); ?>" autocomplete="off"><br>
      <label for="lname">LastName</label>
      <?php echo $lname ?>
      <input class="form-control form-control-sm" type="text" name="lname" id="lname" value="<?php echo Validate::sanitize(Input::get('lname',true)); ?>" autocomplete="off"><br>
    </div>
    <div class="form-group">
      <?php Captcha::add() ?>
    </div>
    <div class="form-group">
      <p>
     This site is protected by reCAPTCHA and the Google
     <a href="https://policies.google.com/privacy">Privacy Policy</a> and
     <a href="https://policies.google.com/terms">Terms of Service</a> apply.
      </p>
    </div>
    <button class='btn btn-primary' type="submit" name="submit">Register</button>
  </form>
</div>
</body>
</html>
