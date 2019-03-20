<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
$pack = new Package();
if (!$pack->has(Input::get('choice'))) {
    Page::redirect('getCoins.php');
}
$customerDetail;
$fname   = '';
$lname   = '';
$package = $pack->get(Input::get('choice'))[0];
$cus     = new Customer();
if ($cus->has()) {
    $customerDetail = $cus->get(Session::get('id'))[0];
    $fname          = $customerDetail->fname;
    $lname          = $customerDetail->lname;

}

?>
<div class="container my-sm-5 border rounded" style='background: #f5f5f5'>
    <h2 class="text-center text-muted"> Items :
        <?php echo "$package->package ( " . number_format((float) $package->cost / 100, 2, '.', '') . " MYR )"; ?></h2>
    <center><small class="text-danger">Notice : once the coins is bought, there's no way to refund</small></center>



    <form action="charge.php" method="post" id="payment-form">
        <div class="form-row">
            <input type="hidden" name="choice" value="<?php echo Input::get('choice') ?>">
            <input type="text" name="fname" class="form-control mb-3 StripeElement StripeElement--empty"
                placeholder="first name" value="<?php echo $fname; ?>">
            <input type="text" name="lname" class="form-control mb-3 StripeElement StripeElement--empty"
                placeholder="last name" value="<?php echo $lname; ?>">
            <input type="email" name="email" class="form-control mb-3 StripeElement StripeElement--empty"
                placeholder="email" value="<?php echo (new User())->getData()->email ?>">
            <div id="card-element" class="form-control">
                <!-- A Stripe Element will be inserted here. -->
            </div>

            <!-- Used to display form errors. -->
            <div id="card-errors" role="alert"></div>
        </div>

        <button class="mt-sm-3 btn btn-primary btn-block payment-submit">Submit Payment</button>
    </form>

</div>
<script src="https://js.stripe.com/v3/"></script>
<script src="../js/charge.js" charset="utf-8"></script>


<?php
Page::addFoot();