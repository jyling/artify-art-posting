<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
$string = 'username';
?>
<div class="container mt-sm-5">
    <h1>Report <?php echo $string ?></h1>
    <hr>
<form action="" method="get">
    <div class="form-group">
        <input type="radio" name="reportType" id="" value='Harassment' checked required> Harassment <br>
        <input type="radio" name="reportType" id="" value='Spam' required> Spam <br>
        <input type="radio" name="reportType" id="" value='Art Theft' required> Art Theft <br>
        <input type="radio" name="reportType" id="" value='Nudity' required> Nudity <br>
        <input onclick='(this.checked )? document.getElementById("txtOthers").disabled = false : document.getElementById("txtOthers").disabled = true ' type="radio" name="reportType" id="" value='Others' required> Others <br>
    </div>
    <div class="form-group">
    <input type="textbox" class='form-control' placeholder='Others' name="" id="txtOther" value='' disabled> <br>
    <textarea name="description" class="form-control" cols="30" rows="10"></textarea>
    <input class='btn btn-primary btn-block mt-sm-2' type="submit" value="Submit">
</div>
</form>
</div>