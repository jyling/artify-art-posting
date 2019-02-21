<?php
require_once 'init.php';
Page::addHead();
Page::addNav();
?>
<style media="screen">
  .vertical-center-flex {
    min-height: 100%;
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
  .vertical-center-block {
    display: block;
    margin-left: 0 auto;
    margin-right: 0 auto;
  }
</style>
  <div class="container">
    <h1 class="text-center display-1 text-muted" style="margin-top: 20%">Error 404</h1>
    <p class="text-center text-muted">An Error has occurred, we are worried
    about you, so we place you here</p>
    <a class="btn btn-primary vertical-center-block" href="index.php">Go To Home Page</a>
  </div>

<?php
Page::addFoot();
?>
