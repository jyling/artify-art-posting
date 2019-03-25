<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
$image  = '';
$nick   = '';
$pwd1   = '';
$oldpwd = '';
$repwd  = '';

$usr = new User();

if (Input::exist()) {
    $failed  = array();
    $params  = array();
    $usr     = new User();
    $pwd     = $usr->getData()->pwd;
    $captcha = Captcha::verify($_POST);
    if ($captcha['success'] === false) {
        echo "<div class='text-center thin-alert alert alert-danger alert-dismissible fade show'>" . Captcha::errorCode($captcha['error-codes'][0]) . "</div>";
    }
    if ($usr->verifyPass(Input::get('oldpass'), $pwd) && $captcha['success']) {
        // $img = new Image();
        // if (Input::has('cbxProfilePic')) {
        //     $img->getFile('image', array(
        //         'minSize'   => 20,
        //         'maxSize'   => $img->Mb2byte(4),
        //         'minWidth'  => 64,
        //         'minHeight' => 64,
        //         'maxHeight' => 128,
        //         'maxWidth'  => 128,
        //         'type'      => array(
        //             'png',
        //             'jpg',
        //         ),
        //     ));
        //     if ($img->getPassed()) { //check if the image has passed all the requirement
        //         $path = $img->addToPath(array('Profile', $usr->getData()->usrnm));

        //         $params['profileImgPath'] = $path;
        //     } else {
        //         $failed['image'] = $img->getError();
        //     }

        // }
        if (Input::has('cbxNickname')) {
            $vali   = new Validate();
            $result = $vali->check($_POST, array(
                'nickname' => array(
                    'required' => true,
                    'min'      => 3,
                    'max'      => 32,
                    'charOnly' => true,
                    'name'     => 'nickname',
                ),
                'oldpass'  => array(
                    'required' => true,
                    'min'      => 2,
                    'max'      => 64,
                    'name'     => 'old password',
                )));
            if ($vali->passed()) {
                $params['nickname'] = Validate::sanitize(Input::get('nickname'));
            } else {
                $failed['nickname'] = $vali->getError();
            }
        }
        if (Input::has('cbxNewPassword')) {
            $vali   = new Validate();
            $result = $vali->check($_POST, array(
                'oldpass' => array(
                    'required' => true,
                    'min'      => 6,
                    'max'      => 32,
                    'name'     => 'old password',
                ),

                'newpass' => array(
                    'required'    => true,
                    'min'         => 6,
                    'max'         => 32,
                    'match'       => 'repass',
                    'match_name'  => 'second password field',
                    'name'        => 'password',
                    'charAndNums' => true,
                ),
                'repass'  => array(
                    'required'    => true,
                    'min'         => 6,
                    'max'         => 32,
                    'name'        => 'second password field',
                    'charAndNums' => true,
                ),

            ));
            if ($result->passed()) {
                $params['pwd'] = PassHasher::getHash(Validate::sanitize(Input::get('password', true)));
            } else {
                $failed['password'] = $result->getError();
            }
        }

        if (count($failed) > 0) {
            foreach ($failed as $i) {

                foreach ($i as $key => $val) {
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
                        case 'image':
                            $image .= "<div class='thin-alert alert alert-danger alert-dismissible fade show'>$val $a</div>";
                            break;
                        default:
                            // code...
                            break;
                    }
                }

            }

        } else {
            if (count($params)) {
                try {
                    $params['score'] = $captcha['score'];
                    if ($usr->update($params)) {
                        $read = new Reader();
                        $read->read('modal.txt');
                        if ($read->success()) {
                            Session::flash('modal', $read->modify(array(
                                '$modalTitle'   => 'Success!!!',
                                '$modalcontent' => 'Profile has been successfully updated',
                                '$buttonType'   => 'success',
                            )));
                            Page::redirect("index.php");
                        }
                    }
                } catch (\Exception $e) {
                    die('error' . $e);
                }
            }
        }

    } else {
        $oldpwd = "<div class='thin-alert alert alert-danger alert-dismissible fade show'>Your Password is incorrect</div>";
    }

}

?>

<div class="container container-sm">
    <div class="form-group">
        <h1>Change Profile Information</h1>
    </div>
    <div class="form-group">
        <label for="Profile Pic"><Input type='checkbox' name="cbxProfilePic" id="cbxProfilePic"
                onclick='toggle(this,"pic-toggle")'> <label id="lblcbxProfilePic" for="cbxProfilePic">Profile
                Picture</label> </label>
        <?php echo $image ?>
        <div id='pic-toggle' style='display:none'>
            <?php
                $read = new Reader();
    $read->read('cancelModal.txt');
    $output = $read->modify(array(
        '$modalname' => 'updateProfileImg',
        '$openbtn'   => 'Update',
        '$modalname' => 'updateProfileImg',
        '$title'     => 'Update Profile ?',
        '$content'   => '<img class="my-image" src="../asset/placeholder.png" />
        <input class="form-control m-2" type="file" name="image-test" id="image-test">',
        '$choiceBtn'   => 'Upload',
        '$choiceId'     => 'upload',

    ));
    echo $output;
    $read = new Reader();
    $read->readBase('../js/removePost.js');
    echo "<script>" . $read->getContent() . "</script>";
    ?>
        </div>
        <small class='form-text text-muted'>Change your profile picture here, the <b>requirement</b> are max 128 pixel,
            png and jpg <b>only</b></small>
    </div>
    <hr>
    <form class="" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="usrname"><Input type='checkbox' name="cbxNickname" id="cbxNickname"
                    onclick='toggle(this,"nick-toggle")'> <label id="lblcbxNickname"
                    for="cbxNickname">Nickname</label></label>
            <?php echo $nick ?>
            <input class="form-control form-control" style="display:none" type="text" name="nickname" id="nick-toggle"
                value="<?php echo Validate::sanitize($usr->getData()->nickname) ?>" autocomplete="off">
            <small class='form-text text-muted'>Change your nickname here, it <b>should be only</b> letters and
                space</small>
        </div>
        <hr>

        <div class="wrapper">
            <input type="checkbox" id="cbxNewPassword" name="cbxNewPassword" onclick='toggle(this,"pass-toggle")'>
            <label id="lblcbxNewPassword" for="cbxNewPassword">New Password</label></label>
            <?php echo $pwd1 ?>
            <?php echo $repwd ?>
            <div id="pass-toggle" style="display:none">
                <div class="form-group">
                    <label for="usrname">New Password</label>
                    <input class="form-control form-control" type="password" name="newpass" id="newpass" value=""
                        autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="usrname">Re-enter Password</label>
                    <input class="form-control form-control" type="password" name="repass" id="repass" value=""
                        autocomplete="off">
                </div>
            </div>
            <small class='form-text text-muted'>Change your password here, it <b>must</b> contain atleast a number and a
                letter</small>
        </div>

        <hr>

        <div class="form-group">
            <label for="usrname">Old Password</label>
            <?php echo $oldpwd ?>
            <input class="form-control form-control" type="password" name="oldpass" id="oldpass" value="990128a"
                autocomplete="off">
        </div>

        <?php
Captcha::add();
?>
        <div class="form-group">
            <input class="btn btn-primary" value="Change" name="sumit" type="submit">
    </form>
</div>
</div>
<script>
window.onload = function() {
    initizalize('.my-image', '#image-test', '#upload', '#dummy', 'ajax/ajax-pfp.php');

    function initizalize(target, input, button, output, php) {
        var basic = $(target).croppie({
            enableExif: true,
            viewport: {
                width: 128,
                height: 128
            },
            boundary: {
                width: 256,
                height: 256
            },

        });;

        $(input).on('change', function() {
            var read = new FileReader();
            read.onload = function(e) {
                basic.croppie('bind', {
                    url: e.target.result
                }).then(function() {
                    console.log('binded')
                })
            }
            read.readAsDataURL(this.files[0])
        })
        $(button).on('click', function(e) {
            basic.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(ea) {
                $.ajax({
                    url: php,
                    type: 'post',
                    data: {
                        img: ea
                    },
                    success: function(data) {
                        alert('uploaded');
                        alert('you will be redirected to your profile page');
                        location.href = "viewProfile.php";
                    }
                })
            })
        })




    }
}
</script>
<?php Page::addFoot();?>