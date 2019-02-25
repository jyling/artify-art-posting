<?php
require_once('../init.php');
Page::addHead();
Page::addNav();

?>
<div class="container">

    <!-- <img src="Image/Samuel Ling/0f78e7e09ee4f0b3791a36ec96919da31d9631ecead180b211dbe68af25b3368.jpg" class="view-post-img border rounded img-fluid" alt="" srcset=""> -->
    <!-- <img src="Image/Samuel Ling/a3d1d631a135359ccb69fd0609dae5357fdf3ad536e32dd9e5ae007e695d2b69.jpg" class="view-post-img border rounded img-fluid" alt="" srcset=""> -->
    <img src="https://placeimg.com/1920/1080/any" class="view-post-img border rounded img-fluid" alt="" srcset="">

    <div class="container text-center">
        <h1 class='view-post-title'>Title : My First Photograph</h1>
        <p class='font-italic'>Artist : <a href="">Jack Desu (0 followers)</a> <button class='btn btn-primary ml-sm-2'>Follow</button></p>
        <p class='font-italic'>Catergory : <a href="">Photography</a></p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim 
            veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
            velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit
            anim id est laborum.
        </p>
    </div>
</div>
<div class="container">
    <hr>
    <h5 class='view-post-comment'>Comment</h5>
    <div class="container">
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="get">
                <div class="form-group">
                    <input class='form-control' name='comment'  type="text">
                </div>
                <div class="form-group">
                    <input class='form-control btn btn-primary' type="submit" value='post comment'>
                </div>
        </form>

    </div>
    <div class="container" style="margin-bottom: 5%;">  
        <div class="comment-wrapper">
            <div class="row">
                    <div class="col-sm-1">
                            <div class="thumbnail">
                            <img class="img-responsive user-photo  border rounded" src="https://placeimg.com/64/64/any" width='64' height='64'>
                            </div><!-- /thumbnail -->
                    </div><!-- /col-sm-1 -->
                    <div class="col-sm-10 comment-content border rounded" style='padding:0'>
                        <div class="row-sm-1 comment-head">
                            <a href="">Username</a> <span class='text-muted'>commented just now</span>
                        </div>
                        <div class="comment-body border rounded">
                            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Fugiat vel eum, quis ad fuga maxime suscipit iure dicta dolorum porro adipisci inventore quisquam nobis deserunt minus, neque cum consequuntur id?</p>
                        </div>
                    </div>
                </div>
        </div>


    </div>
    
<?php

Page::addFoot();
?>