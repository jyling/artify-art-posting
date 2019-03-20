<?php
require_once '../../init-nonpublic.php';

if (Input::exist()) {
    if (Input::has('post')) {
        $post = new Post();
        $post->remove(Input::get('post'));
    }
}