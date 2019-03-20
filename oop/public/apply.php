<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
Permission::kick();

$userDetail = '';
$data       = '';
if (Input::exist('get') && Input::has('apply')) {
    $db = DB::run();
    $db->get('apply', array('apply_id', '=', Input::get('apply')));
    $data           = $db->getResult()[0];
    $usr            = new User($data->usr_id);
    $userDetail     = $usr->getData();
    $userPermission = $usr->getPermission($userDetail->usr_id);
    if (Input::has('choice')) {
        if ($userPermission->usr->accType != 'admin' || $userPermission->usr->accType != 'mod') {
            $userPermission->usr->accType    = 'artist';
            $userPermission->usr->permission = Permission::update($userPermission, array(
                'post' => true,
            ));
            $userPermission = Json_encode($userPermission);
            if (Input::has('choice') != 'Aprrove') {
                $db->update('apply', array('apply_id' => Input::get('apply')), array(
                    'approval' => 2,
                ));
            } else {
                $usr->updateGroup(array(
                    'permission' => $userPermission,
                ), $userDetail->usr_id);
                $db->update('apply', array('apply_id' => Input::get('apply')), array(
                    'approval' => 1,
                ));
                Page::redirect('Statistic.php');
            }
        }
    }
}
?>
<div class="container mt-sm-3 mb-sm-3">
    <center>
        <div id="apply-image">
        <img class=" img-target item-center-block img-thumbnail" src="<?php echo $data->imgPath ?>" style="z-index: 2;" onerror="this.src='../asset/placeholder.png';" alt="Your Image Goes Here" src="#">
        </div>
    </center>
</div>
        <div class="container " style="max-width: 701px">
            <h4><a href="#"><?php echo $userDetail->usrnm ?></a></h4>
            <p><?php echo $data->content ?></p>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <?php Page::hiddenGet()?>
            <input class='btn btn-primary btn-block' name='choice' type="submit" value="Promote">
            <input class='btn btn-danger btn-block' name='choice' type="submit" value="Dismiss">
            </form>
        </div>
<?php Page::addFoot();?>