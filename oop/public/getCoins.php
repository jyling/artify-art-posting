<?php
require_once '../init.php';

Page::addHead();
Page::addNav();

?>



<div class="container my-sm-5 border rounded" style='background: #f5f5f5'>
    <div class="container my-5">
        <h1 class="text-center text-muted mt-1">
            What is artify's coin ?
        </h1>
        <p class="text-center">
            Artify coin is a Artify's own currency that provide way to contribute to the fellow artist that you wish to
            support and to make sure we can pay
            our fees took keep artify alive.
        </p>
        <ul class="list-group">
            <li class="list-group-item active">Coin's benefits</li>
            <li class="list-group-item">Support artist's art</li>
            <li class="list-group-item">Can be used to buy high res of the artist art</li>
            <li class="list-group-item">Keep artify out of Ads</li>
            <li class="list-group-item">Keep artify alive</li>
        </ul>
    </div>
</div>








<div class="container my-sm-5 border rounded" style='background: #f5f5f5'>
    <h1 class="text-center text-muted mt-4">Coins</h1>
    <div class="container my-5">
        <center>
            <div class='card-deck'>

                <?php

$packages = new Package();
$packages->genPackage();

?>

            </div>
        </center>
    </div>
</div>




<?php
Page::addFoot();