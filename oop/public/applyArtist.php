<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
?>

<div class="container mt-sm-5">
    <form action="" method="post" enctype="multipart/form-data">
        <h1 class='text-center mb-sm-4'>Apply to become artist</h1>
        <textarea class='form-control' name="" id="" cols="30" rows="10"></textarea>
        <center>
            <input class='mt-sm-3' name='images' type="file">
            <div id="apply-image" class='mt-sm-3'>
                <table>
                    <tr>
                        <td><img class='img-thumbnail' src="https://placeimg.com/400/400/any" height='150' width='150'></td>
                        <td><img class='img-thumbnail' src="https://placeimg.com/400/400/any" height='150' width='150'></td>
                        <td><img class='img-thumbnail' src="https://placeimg.com/400/400/any" height='150' width='150'></td>
                        <td><img class='img-thumbnail' src="https://placeimg.com/400/400/any" height='150' width='150'></td>
                    </tr>
                </table>
            </div>       
        </center>
        <input class='btn btn-block btn-primary mt-sm-2' type="submit" value='Submit'>
    </form>
</div>