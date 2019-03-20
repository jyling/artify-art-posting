<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
Permission::kick();

$reportDetails        = null;
$reportFromUserDetail = null;
$reportedUserDetail   = null;
if (!Input::has('report')) {
    page::printModal("Error", "Report ID Required", "danger", 'Statistic.php');
} else {
    $report_id = Input::get('report');
    $form      = new Document();
    // die(var_dump($form->has('report_user', array('report_id', '=', $report_id))));
    if ($form->has('report_user', array('report_id', '=', $report_id))->exist) {
        $reportDetails        = $form->has('report_user', array('report_id', '=', $report_id))->content;
        $reportedUser         = new User($reportDetails->target_id);
        $reportFromUser       = new User($reportDetails->usr_id);
        $reportFromUserDetail = $reportFromUser->getData();
        $reportedUserDetail   = $reportedUser->getData();
    }
}
//2019-03-06 06:09:05
if (input::has('length') && Input::get('report')) {
    $vali   = new Validate();
    $result = $vali->check($_GET, array(
        'reason' => array(
            'min' => 41,
            'max' => 2000,
        ),
    ));
    if ($result->passed()) {
        if (Input::get('choice') == 'Ban') {
            $db = DB::run();
            $db->update('report_user', array('report_id' => Input::get('report')), array(
                'dismiss' => 0,
            ));
            $ban = new Ban();
            $ban->doBan(Input::get('target'), Input::get('length'), Input::get('reason'));
            Page::printModal("Banned", "$reportedUserDetail->usrnm is been banned for " . Input::get('length') . " Days", 'caution', "statistic.php");
        } else {
            $db = DB::run();
            $db->update('report_user', array('report_id' => Input::get('report')), array(
                'dismiss' => 2,
            ));
            Page::printModal("Dismissed", "The report has been dismissed", 'info', "statistic.php");
        }
    }
}

?>

<div class="container mt-sm-3 mb-sm-3">
    <center>
        <h1>Reported User</h1>
        <hr>
    </center>
</div>
<div class="container " style="max-width: 701px">
    <div class="wrapper  alert alert-danger">
        <div class="heading">
            <h6>Reported User</h6>
            <hr>
        </div>
        <div class="reportedUser row">
            <div class="col-2">
                <img class=" img-target item-center-block img-thumbnail"
                    src="<?php echo $reportedUserDetail->profileImgPath ?>" style="z-index: 2;"
                    onerror="this.src='../asset/placeholder.png';" alt="Your Image Goes Here" src="#">
            </div>
            <h6 class="col-9">Reported User : <a
                    href="viewProfile.php?user=<?php echo $reportedUserDetail->usr_id ?>"><?php echo $reportedUserDetail->usrnm ?></a>
            </h6>
        </div>
    </div>
    <hr>
    <div class="wrapper  alert alert-success">
        <div class="heading">
            <h6>Reported By</h6>
            <hr>
        </div>
        <div class="row">
            <div class="col-2">
                <img class=" img-target item-center-block img-thumbnail"
                    src="<?php echo $reportFromUserDetail->profileImgPath ?>" style="z-index: 2;"
                    onerror="this.src='../asset/placeholder.png';" alt="Your Image Goes Here" src="#">
            </div>
            <h6 class="col-9">Reported By : <a
                    href="viewProfile.php?user=<?php echo $reportFromUserDetail->usr_id ?>"><?php echo $reportFromUserDetail->usrnm ?></a>
            </h6>
        </div>
    </div>
    <h6>Report Title :
        <?php echo ($reportDetails->report_title == null) ? 'Preset' : $reportDetails->report_title; ?></a></h6>
    <h6>Report Type : <?php echo $reportDetails->report_type ?></a></h6>
    <p><?php echo $reportDetails->report_content ?></p>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <?php Page::hiddenGet()?>
        <div class="form-group">
            <label for="days">Ban Length</label>
            <select class="form-control" name="length" id="days">
                <option value="1">1 Day</option>
                <option value="2">2 Day</option>
                <option value="3">3 Day</option>
                <option value="4">4 Day</option>
                <option value="5">5 Day</option>
                <option value="6">6 Day</option>
                <option value="7">7 Week</option>
                <option value="14">2 Week</option>
                <option value="21">3 Week</option>
                <option value="365">1 Year</option>
            </select>
        </div>
        <div class="form-group">
            <textarea name="reason" class="form-control" cols="10" rows="9">
Reason for Ban:
You broke the Rules

Report Type : <?php echo $reportDetails->report_type; ?>

Description :
<?php echo $reportDetails->report_content; ?>
                </textarea>
        </div>
        <input type="hidden" name="target" value="<?php echo $reportDetails->target_id ?>">
        <input class='btn btn-primary btn-block' name='choice' type="submit" value="Ban">
        <input class='btn btn-danger btn-block' name='choice' type="submit" value="Dismiss">
    </form>
</div>