<?php
require_once '../../init-nonpublic.php';
$usr = new User();
$fol = new Following();
if (Input::exist() && $usr->getLogin()) {
    $target = new User(Input::get('id'));
    if ($target->find(Input::get('id'))) {
       echo $fol->follow($usr->getData()->usr_id, $target->getData()->usr_id);
    }
}