<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
$fname = '';
$lname = '';
$usr = new User();
if (!$usr->getLogin()) {
  Page::redirect("index.php");
}
else {
  if (Input::exist()) {
    $vali = new Validate();
    $result = $vali->check($_POST, array(
      'fname' => array(
        'required' => true,
        'min' => 2,
        'max' => 50,
        'charOnly' => true,
        'name' => 'first name'
      ) ,
      'lname' => array(
        'required' => true,
        'min' => 2,
        'max' => 50,
        'charOnly' => true,
        'name' => 'last name'
      )
    ));
    if ($vali->passed()) {
      try {
        $usr->update(array(
          'fname' => Validate::sanitize(Input::get('fname')),
          'lname' => Validate::sanitize(Input::get('lname'))
        ));
      } catch (\Exception $e) {
        die('error' . $e);
      }

    }
    else {
      foreach ($result->getError() as $key => $val) {
        $a = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        switch ($key) {
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
        <h1>Change Profile Information</h1>
      </div>
      <div class="form-group">
        <div class=''>
          <td><img class='img-thumbnail mr-sm-2' src="https://placeimg.com/400/400/any" height='64' width='64'><input type="file"></td>
        </div>
      </div>
      <div class="form-group">
        <label for="usrname">Nickname</label>
        <?php echo $fname ?>
        <input class="form-control form-control" type="text" name="fname" id="fname" value="<?php echo Validate::sanitize($usr->getData()->fname) ?>" autocomplete="off">
      </div>
      <div class="form-group">
        <label for="usrname">Old Password</label>
        <?php echo $lname ?>
        <input class="form-control form-control" type="text" name="lname" id="lname" value="<?php echo Validate::sanitize($usr->getData()->lname) ?>" autocomplete="off">
      </div>
      <div class="form-group">
        <label for="usrname">New Password</label>
        <?php echo $lname ?>
        <input class="form-control form-control" type="password" name="lname" id="lname" value="<?php echo Validate::sanitize($usr->getData()->lname) ?>" autocomplete="off">
      </div>
      <div class="form-group">
        <label for="usrname">Re-enter Password</label>
        <?php echo $lname ?>
        <input class="form-control form-control" type="password" name="lname" id="lname" value="<?php echo Validate::sanitize($usr->getData()->lname) ?>" autocomplete="off">
      </div>
      <?php
      Captcha::add();
      ?>
      <div class="form-group">
        <input class="btn btn-primary" value="Change" name="sumit" type="submit">
    </form>
</div>
<?php Page::addFoot(); ?>
