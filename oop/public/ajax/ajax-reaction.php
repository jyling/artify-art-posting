<?php
require_once '../../init-nonpublic.php';
$reaction = new Reaction();
$usr      = new User();
if ($usr->getLogin() && Input::exist()) {
    ($reaction->like($_POST['id'], $_POST['choice']));
}
