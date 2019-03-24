<?php
require_once '../init-nonpublic.php';
if (Input::has('url')) {
    $file  = Input::get('url');
    $buyer = new Purchase();
    if ($buyer->Own($file)) {
        $file = $buyer->getById($file)[0]->artPath;
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        echo "<script>window.close();</script>";
        exit;
    }
}