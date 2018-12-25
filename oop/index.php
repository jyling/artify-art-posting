<?php
require_once 'init.php';
DB::Run();
DB::Run();
DB::Run();
$usr = DB::Run()->update('usr',2,array(
  'usrname'=>'Dane',
  'fname'=>'brian',
  'lname'=>'desu'
));
// if(!$usr->getCount()) {
//   echo 'user not found';
// }
// else {
//   echo 'user found';
//   foreach($usr->getResult() as $usr) {
//     echo "<br>Username : ". $usr->usrname;
//   }
// }
