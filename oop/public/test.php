<?php

require_once '../init.php';
$db = DB::run()->jointTable(array(
    'usr'  => array(
        'usrnm',
    ),
    'post' => array(
        'title',
    ),
), array(
    '=' => array(
        'usr'  => 'usr_id',
        'post' => 'usr_id',
    ),
)

)->getResult();
Table::build($db);