<?php
require_once '../../init-nonpublic.php';
if (Input::exist()) {
    $usr = new User();
    $img = new Image();
    $path = $img->addToPath64(array('Profile', $usr->getData()->usrnm),'../',Input::get('img'));
    $usr->update(array(
        'profileImgPath' => substr($path,3,strlen($path))
    ));
}