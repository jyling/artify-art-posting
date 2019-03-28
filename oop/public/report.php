<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
//array(3) { ["reportType"]=> string(6) "Others" ["title"]=> string(164) "Is it ever right " ["description"]=> string(8) "addfwfae" }
// ^ Report with others
//array(3) { ["reportType"]=> string(10) "Harassment" ["title"]=> string(0) "" ["description"]=> string(14) "Preset Example" }
// ^ Report with Preset
$string = 'username';
if (Input::has('user')) {
    $user = new User(Input::get('user'));
    if (!$user->find(Input::get('user'))) {
        Page::printModal('Error!!!', 'User not found, Please try again later', 'danger', 'index.php');
    }
    $string = $user->getData()->usrnm;
} elseif (Input::has('post')) {
    $form = new Document();
    if (!$form->has('post', array('post_id', '=', Input::get('post')))->exist) {
        Page::printModal('Error!!!', 'Invalid Data, Please try again later', 'danger', 'index.php');
    }
    $target_user = $form->has('post', array('post_id', '=', Input::get('post')))->content->usr_id;
    $user        = new User($target_user);

    $string = "Post by " . $user->getData()->usrnm;
} else {
    Page::printModal('Error!!!', 'Invalid Data, Please try again later', 'danger', 'index.php');
}

if (Input::exist()) {

    $vali = new Validate();
    if (Input::get('reportTypecbx') == 'Preset') {
        $result = $vali->check($_POST, array(
            'reportType'  => array(
                'required' => true,
                'name'     => 'report type',
            ),
            'description' => array(
                'required' => true,
                'name'     => 'description',
                'max'      => 2000,
                'min'      => 6,
            ),
            'target'      => array(
                'required' => true,
            ),
        ));
        if (Input::has('user')) {
            if ($result->passed()) {
                $db = DB::run();
                $db->insert('report_user', array(
                    'usr_id'         => Session::get('id'),
                    'target_id'      => Validate::sanitize(Input::get('target')),
                    'report_type'    => Validate::sanitize(Input::get('reportType')),
                    'report_content' => Validate::sanitize(Input::get('description')),
                ));
                Page::printModal('Success!!!', 'Thanks for your input, our moderators and admin will look into your input', 'primary', 'index.php');
            }
        }
        if (Input::has('post')) {
            $db = DB::run();
            $db->insert('report_post', array(
                'usr_id'         => Session::get('id'),
                'post_id'      => Validate::sanitize(Input::get('target')),
                'report_type'    => Validate::sanitize('Others'),
            'report_content' => Validate::sanitize(Input::get('description')),
            ));
            Page::printModal('Success!!!', 'Thanks for your input, our moderators and admin will look into your input', 'primary', 'index.php');

        }
    } elseif (Input::get('reportTypecbx') == 'Others') {
        $result = $vali->check($_POST, array(
            'reportType'  => array(
                'required' => true,
                'name'     => 'report type',
            ),
            'title'       => array(
                'required' => true,
                'name'     => 'title',
                'max'      => 64,
                'min'      => 6,
            ),
            'description' => array(
                'required' => true,
                'name'     => 'description',
                'max'      => 2000,
                'min'      => 6,
            ),
            'target'      => array(
                'required' => true,
            ),
        ));
        if (Input::has('user')) {
            if ($result->passed()) {
                $db = DB::run();
                $db->insert('report_user', array(
                    'usr_id'         => Session::get('id'),
                    'target_id'      => Validate::sanitize(Input::get('target')),
                    'report_type'    => Validate::sanitize('Others'),
                'report_title'   => Validate::sanitize(Input::get('title')),
                'report_content' => Validate::sanitize(Input::get('description')),
            ));
            Page::printModal('Success!!!', 'Thanks for your input, our moderators and admin will look into your input', 'primary', 'index.php');
        }
    }
        if (Input::has('post')) {
            if ($result->passed()) {
                $db = DB::run();
                $db->insert('report_post', array(
                    'usr_id'         => Session::get('id'),
                    'post_id'      => Validate::sanitize(Input::get('target')),
                    'report_type'    => Validate::sanitize('Others'),
                'report_title'   => Validate::sanitize(Input::get('title')),
                'report_content' => Validate::sanitize(Input::get('description')),
            ));
            Page::printModal('Success!!!', 'Thanks for your input, our moderators and admin will look into your input', 'primary', 'index.php');
        }
    }
    } else {
        Page::printModal('Error!!!', 'Invalid Data, Please try again later', 'danger', 'index.php');
    }

}
?>
<div class="container mt-sm-5">
    <h1>Report <?php echo $string ?></h1>
    <hr>
<form action="" method="post">
    <div class="form-group">
    <input onchange='toggle(this,"radios");  document.getElementById("other").checked = false;toggle(this,"txtOther")' type="checkbox" name="reportTypecbx" id="pre" value='Preset' checked> Preset <br>
        <div id="radios">
            <input type="radio" name="reportType" id="" value='Harassment' checked required> Harassment <br>
            <input type="radio" name="reportType" id="" value='Spam' required> Spam <br>
            <input type="radio" name="reportType" id="" value='Art Theft' required> Art Theft <br>
            <input type="radio" name="reportType" id="" value='Nudity' required> Nudity <br>
        </div>
        <hr>
        <input onchange='toggle(this,"txtOther"); document.getElementById("pre").checked = false; toggle(this,"radios");' type="checkbox" name="reportTypecbx" id="other" value='Others'> Others <br>
        <input type="textbox" style="display:none" class='form-control' placeholder='Others' maxlength="64" name="title" id="txtOther" value='<?php Validate::sanitize(Input::get('title'));?>'> <br>
    </div>
    <div class="form-group">
    <textarea name="description" class="form-control" maxlength="2000" cols="30" rows="10"><?php echo Validate::sanitize(Input::get('description')) ?></textarea>
    <input type="hidden" name='target' value='<?php echo Validate::sanitize(Input::get('user')) . Validate::sanitize(Input::get('post')) ?>'>
    <input class='btn btn-primary btn-block mt-sm-2' type="submit" value="Submit">
</div>
</form>
</div>

<?php Page::addFoot();?>