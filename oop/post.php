<?php
require_once 'init.php';
Page::addHead();
Page::addNav();
?>
<div class="container" style="margin-top: 5%;">
  <div class="form-group">
    <div class="post-title form-group">
      <input class="btn-block col-md-11 form-control" type="text" placeholder="Title" name="" value="">
    </div>
    <div class="post-body form-group row">
      <div class="post-info col-md-7">
        <div class="container">
          <div class="form-group">
            <p class="text-center">Character Others</p>
            <div class="form-row">
                <select class="col-6 form-control" name="" style="margin-right: 1%;">
                  <option value="Some">Some Option</option>
                </select>
                <input class="col form-control" type="text" name="" value="">
            </div>
          </div>
          <div class="form-group">
            <p class="text-center">Additional Information</p>
            <input class="col form-control" type="text" name="" value="" placeholder="Collab (Optional)" style="margin-bottom: 1%;">
            <input class="col form-control" id="tags" type="text" name="" value="" placeholder="Tags (saparate by using ',')" style="margin-bottom: 1%;">
            <input class="col form-control" type="hidden" name="Tags" value="" placeholder="Tags" style="margin-bottom: 1%;">
              <textarea class="col form-control" name="name" rows="8" cols="80"></textarea>
          </div>
        </div>
      </div>
      <div class="post-img form-group text-center col-md-4">
        <input class="form-control" style="margin: 7%; margin-left: 0%;" onchange="reloadImage(this)" type="file" name="" value="">
          <div class="post-img-container text-center col-md-4">
            <img class="post-img-content item-center-block img-thumbnail post-block" style="z-index: 2;" onerror="this.src='../oop/asset/placeholder.png';" alt="Your Image Goes Here" src="#">
          </div>
      </div>
    </div>
    <div class="container">
      <input type="submit" class="btn btn-primary btn-lg col-md-11" name="" value="Post">
    </div>
  </div>

</div>
<?php
Page::addFoot();
?>
