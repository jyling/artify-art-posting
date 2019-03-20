<?php
require_once '../External Libary/stripe/init.php';
require_once '../init.php';

$pack = new Package();
if (!$pack->has(Input::get('choice'))) {
    Page::redirect('getCoins.php');
}

\Stripe\Stripe::setApiKey('sk_test_O3emgif3cx0G50Naxpn4ACri');
$POST  = filter_var_array($_POST, FILTER_SANITIZE_STRING);
$a     = $POST;
$a     = (object) $a;
$fname = $a->fname;
$lname = $a->lname;
$email = $a->email;
$token = $a->stripeToken;

$customer = \Stripe\Customer::create(array(
    'email'  => $email,
    'source' => $token,
));

$package = $pack->get(Input::get('choice'))[0];

$charge = \Stripe\Charge::create(array(
    'amount'        => $package->cost,
    'currency'      => 'myr',
    'description'   => $package->package,
    'customer'      => $customer->id,
    'receipt_email' => $email,
));

DB::run()->insert('usr_order', array(
    'id'         => $charge->customer,
    'usr_id'     => Session::get('id'),
    'fname'      => $fname,
    'lname'      => $lname,
    'package_id' => $a->choice,

));

DB::run()->insert('transaction', array(
    'id'       => $charge->id,
    'usr_id'   => Session::get('id'),
    'cus_id'   => $charge->customer,
    'package'  => $charge->description,
    'amount'   => $charge->amount,
    'currency' => $charge->currency,
    'status'   => $charge->status,
));

if ($charge->status == "succeeded") {
    $coin = new Coin();
    $coin->add(Session::get('id'), $package->coins);
}

Page::redirect("purchaseSuccess.php", array(
    'tid'     => $charge->id,
    'product' => $charge->description,
    'receipt' => $charge->receipt_url,
));