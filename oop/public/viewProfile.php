<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
$id = null;



if(Input::has('user')) {
  if (Input::get('user') == Session::get('id')) {
    Page::redirect($_SERVER['PHP_SELF']);
  }
  $id = Input::get('user');
  $temp = new User($id);
  if (!$temp->find($id)) {
    Page::redirect($_SERVER['PHP_SELF']);
  }
}
else {
  $id = Session::get('id');
}
$user = new User($id);
$detail = $user->getData();
$permission = $user->getPermission($id)->usr->permission;
?>

<div class="container mt-sm-5 border rounded" style='background: #f5f5f5'>
    <div class="box text-center">
            <img class='img-thumbnail mt-sm-2' src="https://placeimg.com/129/129/any" alt="">
            <h1 id='profile-usrname'><?php echo $detail->usrnm . "  (  $detail->nickname  )"?></h1>
            <?php 
            if(($permission->post == true) && (Input::get('user') != Session::get('id')) && (Input::has('user'))) {
              echo "<button class='btn btn-primary m-sm-2'>Follow</button>";
            }

            if(Input::has('user') && (Input::get('user') != Session::get('id'))) {
              echo "<button class='btn btn-primary m-sm-2'>Report</button>";
            }

            if(!Input::exist('user') && (Input::get('user') == Session::get('id'))) {
            echo "<a class='btn btn-info m-sm-2' href='changeInfo.php' role='button' aria-pressed='true'>Edit</a>";
            }

            if ($permission->owner) {
              echo "<button class='btn btn-warning m-sm-2'>Make Mod</button>";
            }
            
            if($permission->ban) {
              echo "<button class='btn btn-danger m-sm-2'>Ban</button>";
            }
            ?>

    </div>
</div>

<?php 
$role = $user->getRole($id);

echo <<<end
<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <h1 class='m-sm-2 text-center'>$role</h1>
</div>
end;

?>

<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <div class="box text-center m-sm-5 mt-sm-2">
        <div class="card-deck">
            <div class="card mt-sm-3" style="width: 18rem;">
                <a class="achor-card" href="message_id">
                    <img class="card-img-top image-fluid" src="https://placeimg.com/140/480" width='190' height='142.5' alt="Card image cap">
                    <div class="card-body">
                    <h5 class="card-title" data-toggle="tooltip" data-placement="top" title="$fname">$name</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Post Id: <code>#$id</code></h6>
                    <p class="card-text">$msg</p>
                    </div>
                </a>
            </div>
            <div class="card mt-sm-3" style="width: 18rem;">
                <a class="achor-card" href="message_id">
                    <img class="card-img-top image-fluid" src="https://placeimg.com/140/480" width='190' height='142.5' alt="Card image cap">
                    <div class="card-body">
                    <h5 class="card-title" data-toggle="tooltip" data-placement="top" title="$fname">$name</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Post Id: <code>#$id</code></h6>
                    <p class="card-text">$msg</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>





<div class="container mt-sm-3 border rounded" style='background: #f5f5f5'>
    <h3 class='view-post-comment mt-sm-3 text-muted'>Comment</h3>
        <div class="container">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                    <div class="form-group">
                        <input class='form-control' name='comment'  type="text">
                    </div>
                    <div class="form-group">
                        <input class='form-control btn btn-primary' type="submit" value='post comment'>
                    </div>
            </form>

        </div>
    <div class="container mt-sm-3" style="margin-bottom: 5%;">  
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
</div>
<?php Page::addFoot(); ?>