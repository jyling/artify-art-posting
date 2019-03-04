<?php
require_once 'init.php';
Session::remove('usrname');
Session::remove('id');
Session::remove('fullname');

Page::redirect('public/index.php');
