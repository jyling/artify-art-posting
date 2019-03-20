<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
$vali = new Validate();
$img  = new Image();

if (Input::exist()) {
    $result = $vali->check($_POST, array(
        'description' => array(
            'maxlength' => 2000,
            'minlenght' => 16,
        ),
    ));
    $img->getFile('image', array(
        'minSize'   => 20,
        'maxSize'   => $img->Mb2byte(4),
        'minWidth'  => 600,
        'minHeight' => 400,
        'maxHeight' => 5080,
        'maxWidth'  => 5080,
        'type'      => array(
            'png',
            'jpg',
        ),
    ));
    if ($img->getPassed() && $result->passed()) {
        $db   = DB::run();
        $usr  = new User(Session::get('id'));
        $path = $img->addToPath(array('Apply', $usr->getData()->usrnm));
        $db->insert('apply', array(
            'usr_id'  => Session::get('id'),
            'content' => Input::get('description'),
            'imgPath' => $path,
        ));
        $permission = new Permission();
        $permission->update($usr->getPermission(), array(
            'post' => true,
        ));
        $read = new Reader();
        $read->read('modal.txt');
        Session::flash('modal', $read->modify(array(
            '$modalTitle'   => 'Success!!!',
            '$modalcontent' => 'Your form has been submitted<br>
                                please wait for a 2 - 5 working days for our hardworking admin and moderators<br>
                                to see your form, if our form is approved/rejected, we will send you a email',
            '$buttonType'   => 'success',
        )));
        Page::redirect("index.php");
    } else {
        echo 'false';
    }

}
?>
<div class="container mt-sm-5">
    <form action="" method="post" enctype="multipart/form-data">
        <h1 class='text-center mb-sm-4'>Apply to become artist</h1>
        <p class="text-muted text-center">To apply for a artist, you will need to provide prove that you are able to
            product are<br>PSSST: it doesnt need to be good, we just making sure that there's no art thelf</p>
        <textarea class='form-control'
            placeholder="Tell us about your art\n like how do you make it, what tools did you use, software and such"
            name="description" id="" maxlenght="2000" cols="30" rows="10"></textarea>
        <center>
            <input class="form-control" style="margin: 7%; margin-left: 0%;" name="image"
                onchange="reloadImage(this,false)" type="file" name="" value="">
            <div id="apply-image" class=''>
                <img class=" img-target m-sm-3 item-center-block img-thumbnail"
                    style="z-index: 2;max-height:725px;max-width:500px;width: expression(this.width > 500 ? 500: true);"
                    onerror="this.src='../asset/placeholder.png';" alt="Your Image Goes Here" src="#">
            </div>
        </center>
        <input class='btn btn-block btn-primary mt-sm-2' type="submit" value='Submit'>
    </form>
</div>
<?php Page::addFoot();?>