<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
Permission::kick();

?>
<div class="container mt-sm-5 border rounded" style='background: #f5f5f5'>
    <?php
$db = DB::run();

$listedItem = array(
    'usr'         => array(
        'usrnm',
    ),
    'report_user' => array(
        'report_id',
        'report_type',
        'report_title',
        'report_content',
    ),

);

$identifier = array(
    '=' => array(
        'usr'         => 'usr_id',
        'report_user' => 'target_id',
    ));
$condition = ' AND report_user.dismiss  < 1';

$db->jointTable($listedItem, $identifier, $condition);
?>

    <h3 class='m-sm-2'>Reported User : <?php echo $db->getCount(); ?></h3>
    <?php
if ($db->getCount() > 0) {
    $joint = $db->getResult();
    echo <<<table
            <table class='table'>
            <thead class='table-striped'>
            <tr>
                <th scope>Username</th>
                <th scope>Report ID</th>
                <th scope>Report Type</th>
                <th scope>Report Title</th>
                <th scope>Report Content</th>
                <th scope>Tools</th>

            </tr>
            <tr>
table;
    foreach ($joint as $index => $users) {
        echo "<tr>";
        foreach ($users as $value) {
            $value = (strlen($value) > 50) ? Message::StringOverFlow($value, 30) : $value;
            echo "<td scope='col'>$value</td>";
        }
        echo <<<table
                <td scope='col'>
                  <a class='btn btn-primary' href='reportViewUser.php?report=$users->report_id'>View</a>
                </td>
        </tr>
table;
    }
    echo "</table>";
} else {
    echo "<h3>No User Left</h3>";
}
?>

</div>







<div class="container mt-sm-5 border rounded" style='background: #f5f5f5'>
    <h3 class='m-sm-2'>Reported Art : 4</h3>
    <table class="table">
        <thead class="table-striped">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Banned Since</th>
                <th scope="col">Banned Notes</th>
                <th scope="col">Tools</th>
            </tr>
        </thead>
        <tr>
            <td class="">Josh Sua</td>
            <td class="">Art Theft</td>
            <td class="">steals art</td>
            <td class="">
                <button class='btn btn-danger'>Remove</button>
                <button class='btn btn-primary'>dismiss</button>
            </td>
        </tr>
        <tr>
            <td class="">Josh Xian</td>
            <td class="">Art Theft</td>
            <td class="">steals art</td>
            <td class="">
                <button class='btn btn-danger'>Remove</button>
                <button class='btn btn-primary'>dismiss</button>
            </td>
        </tr>
        <tr>
            <td class="">Josh Ong</td>
            <td class="">Art Theft</td>
            <td class="">steals art</td>
            <td class="">
                <button class='btn btn-danger'>Remove</button>
                <button class='btn btn-primary'>dismiss</button>
            </td>
        </tr>
        <tr>
            <td class="">Josh Ang</td>
            <td class="">Art Theft</td>
            <td class="">steals art</td>
            <td class="">
                <button class='btn btn-danger'>Remove</button>
                <button class='btn btn-primary'>dismiss</button>
            </td>
        </tr>
        <tr>
            <td class="">Josh Su</td>
            <td class="">Others</td>
            <td class="">steals art</td>
            <td class="">
                <button class='btn btn-danger'>Remove</button>
                <button class='btn btn-primary'>dismiss</button>
            </td>
        </tr>
    </table>
</div>

<div class="container mt-sm-5 border rounded" style='background: #f5f5f5'>
    <h3 class='m-sm-2'>Banned User : 4</h3>
    <table class="table">
        <thead class="table-striped">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Banned Since</th>
                <th scope="col">Banned Notes</th>
                <th scope="col">Tools</th>
            </tr>
        </thead>
        <tr>
            <td class="">Josh Sua</td>
            <td class="">Art Theft</td>
            <td class="">steals art</td>
            <td class="">
                <button class='btn btn-danger'>Unban</button>
                <button class='btn btn-primary'>dismiss</button>
            </td>
        </tr>
        <tr>
            <td class="">Josh Xian</td>
            <td class="">Art Theft</td>
            <td class="">steals art</td>
            <td class="">
                <button class='btn btn-danger'>Unban</button>
                <button class='btn btn-primary'>dismiss</button>
            </td>
        </tr>
        <tr>
            <td class="">Josh Ong</td>
            <td class="">Art Theft</td>
            <td class="">steals art</td>
            <td class="">
                <button class='btn btn-danger'>Unban</button>
                <button class='btn btn-primary'>dismiss</button>
            </td>
        </tr>
        <tr>
            <td class="">Josh Ang</td>
            <td class="">Art Theft</td>
            <td class="">steals art</td>
            <td class="">
                <button class='btn btn-danger'>Unban</button>
                <button class='btn btn-primary'>dismiss</button>
            </td>
        </tr>
        <tr>
            <td class="">Josh Su</td>
            <td class="">Others</td>
            <td class="">steals art</td>
            <td class="">
                <button class='btn btn-danger'>Unban</button>
                <button class='btn btn-primary'>dismiss</button>
            </td>
        </tr>
    </table>
</div>


<div class="container mt-sm-5 border rounded" style='background: #f5f5f5'>
    <?php
$db = DB::run();

$listedItem = array(
    'usr'   => array(
        'usrnm',
    ),
    'apply' => array(
        'content', 'apply_id',
    ));

$identifier = array(
    '=' => array(
        'usr'   => 'usr_id',
        'apply' => 'usr_id',
    ));
$condition = 'AND apply.approval  < 1';

$db->jointTable($listedItem, $identifier, $condition);
?>

    <h3 class='m-sm-2'>New Artist : <?php echo $db->getCount(); ?></h3>
    <?php
if ($db->getCount() > 0) {
    $joint = $db->getResult();
    echo <<<table
            <table class='table'>
            <thead class='table-striped'>
            <tr>
                <th scope>Username</th>
                <th scope>Content</th>
                <th scope>Apply ID</th>
                <th scope>Tools</th>
            </tr>
            <tr>
table;
    foreach ($joint as $index => $users) {
        echo "<tr>";
        foreach ($users as $value) {
            echo "<td scope='col'>$value</td>";
        }

        echo <<<table
                <td scope='col'>
                  <a class='btn btn-primary' href='apply.php?apply=$users->apply_id'>View</a>
                </td>
        </tr>
table;
    }
    echo "</table>";
} else {
    echo "<h3>No User Left</h3>";
}
?>
</div>




<?php Page::addFoot();?>