<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
$nick = '';
$pwd1 = '';
$oldpwd = '';
$repwd ='';
$usr = new User();

if (!$usr->getLogin()) {
  Page::redirect("index.php");
}
else {
  if (Input::exist()) {
    $vali = new Validate();
    $result = $vali->check($_POST, array(
      'nickname' => array(
        'required' => true,
        'min' => 3,
        'max' => 32,
        'charOnly' => true,
        'name' => 'nickname'
      ) ,
      'oldpass' => array(
        'required' => true,
        'min' => 2,
        'max' => 64,
        'name' => 'old password'
      ),
      'newpass' => array(
        'min' => 6,
        'max' => 64,
        'charAndNums' => true,
        'name' => 'new password',
        'match' => 'repass',
        'match_name' => 'second password field',
      ),
      'repass' => array(
        'min' => 6,
        'max' => 64,
        'charAndNums' => true,
        'name' => 'second password'
      ),
    ));
    if ($vali->passed()) {
      $usr = new User();
      $pwd = $usr->getData()->pwd;
      if ($usr->verifyPass(Input::get('oldpass'),$pwd)) {
        if(!
            (
              !Input::empty('nickname') &&
                (
                  Input::empty('newpass') &&
                  Input::empty('repass')
                  )
                )
            ){
          echo 'name';
          try {
            $usr->update(array(
              'nickname' => Validate::sanitize(Input::get('nickname'))));
            } catch (\Exception $e) {
              die('error' . $e);
            }
        }
        elseif ((!Input::empty('newpass') && !Input::empty('repass'))) {
          echo "name and pass";
          try {
          $usr->update(array(
            'nickname' => Validate::sanitize(Input::get('nickname')),
            'pwd' => PassHasher::getHash(Validate::sanitize(Input::get('password',true)))
          ));
          } catch (\Exception $e) {
            die('error' . $e);
          }
        }
        
      }
          

    }
    else {
      foreach ($result->getError() as $key => $val) {
        $a = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        switch ($key) {
          case 'nickname':
            $nick .= "<div class='thin-alert alert alert-danger alert-dismissible fade show'>$val $a</div>";
              break;
          case 'oldpass':
            $oldpwd .= "<div class='thin-alert alert alert-danger alert-dismissible fade show'>$val $a</div>";
              break;
          case 'newpass':
            $pwd1 .= "<div class='thin-alert alert alert-danger alert-dismissible fade show'>$val $a</div>";
              break;
          case 'repass':
            $repwd .= "<div class='thin-alert alert alert-danger alert-dismissible fade show'>$val $a</div>";
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
        <h1>Change Profile Information</h1>
      </div>
      <div class="form-group">
        <div class=''>
          <td><img class='img-thumbnail mr-sm-2' src="https://placeimg.com/400/400/any" height='64' width='64'><input type="file"></td>
        </div>
      </div>
      <div class="form-group">
        <label for="usrname">Nickname</label>
        <?php echo $nick ?>
        <input class="form-control form-control" type="text" name="nickname" id="nickname" value="<?php echo Validate::sanitize($usr->getData()->nickname) ?>" autocomplete="off">
      </div>
      <div class="form-group">
        <label for="usrname">Old Password</label>
        <?php echo $oldpwd ?>
        <input class="form-control form-control" type="password" name="oldpass" id="oldpass" value="" autocomplete="off">
      </div>
      <div class="form-group">
        <label for="usrname">New Password</label>
        <?php echo $pwd1 ?>
        <input class="form-control form-control" type="password" name="newpass" id="newpass" value="" autocomplete="off">
      </div>
      <div class="form-group">
        <label for="usrname">Re-enter Password</label>
        <?php echo $repwd ?>
        <input class="form-control form-control" type="password" name="repass" id="repass" value="" autocomplete="off">
      </div>
      <?php
      Captcha::add();
      ?>
      <div class="form-group">
        <input class="btn btn-primary" value="Change" name="sumit" type="submit">
    </form>
</div>
</div>  
<?php Page::addFoot(); ?>
