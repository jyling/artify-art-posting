<?php

require_once '../init.php';
Page::addHead();











$listedItem = array(
    'usr'         => array(
        'usrnm' => 'username',
    ),
    'report_user' => array(
        'report_id' => 'report ID',
        'report_type' => 'report type',
        'report_title' => 'report title',
        'report_content' => 'content',
    ),
);

$identifier = array(
    '=' => array(
        'usr'         => 'usr_id',
        'report_user' => 'target_id',
    ));

$condition = ' AND report_user.dismiss  < 1';

$reportedArt = new Statistic('Name');
$reportedArt->built('Reported User', $listedItem,$identifier,$condition,2,0,'ha');








// <div class="container mt-sm-5 border rounded" style='background: #f5f5f5'>
//     <?php
// $db = DB::run();

// $listedItem = array(
//     'usr'         => array(
//         'usrnm',
//     ),
//     'report_user' => array(
//         'report_id',
//         'report_type',
//         'report_title',
//         'report_content',
//     ),

// );



// $db->jointTable($listedItem, $identifier, $condition);
// ?>

<!-- <h3 class='m-2 text-center'>Reported User : <?php //echo $db->getCount(); ?></h3> -->
   <?php
// if ($db->getCount() > 0) {
//     $joint = $db->getResult();
//     echo <<<table
//             <table class='table'>
//             <thead class='table-striped'>
//             <tr>
//                 <th scope>Username</th>
//                 <th scope>Report ID</th>
//                 <th scope>Report Type</th>
//                 <th scope>Report Title</th>
//                 <th scope>Report Content</th>
//                 <th scope>Tools</th>

//             </tr>
//             <tr>
// table;
//     foreach ($joint as $index => $users) {
//         echo "<tr>";
//         foreach ($users as $value) {
//             $value = (strlen($value) > 50) ? Message::StringOverFlow($value, 30) : $value;
//             echo "<td scope='col'>$value</td>";
//         }
//         echo <<<table
//                 <td scope='col'>
//                   <a class='btn btn-primary' href='reportViewUser.php?report=$users->report_id'>View</a>
//                 </td>
//         </tr>
// table;
//     }
//     echo "</table>";
// } else {
//     echo "<h3 class=' text-center text-muted m-5'a >No User Left</h3>";
// }
?>

</div>