<?php
require_once 'init.php';
$a = new Reader();
$a->read('thumb-1920-715040.png', 'image/Samuel Ling/');
for ($i=0; $i < 30; $i++) {
  $output = '';
  if ($a->success()) {
    $output = base64_encode($a->modify());
    echo "<img src='data:image/gif;base64,$output' alt=''>";
    echo "<br>Counter : $i";
  }
}
 ?>
