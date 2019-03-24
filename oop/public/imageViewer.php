<?php
$starttime = microtime(true); // Top of page
require_once '../init-nonpublic.php';
use PHPImageWorkshop\ImageWorkshop;
if (Input::exist('get')) {
    $img     = new Image();
    $post    = (new Post(Input::get('post')))->getData();
    $artist  = (new User($post->usr_id))->getData();
    $dataUri = '';
    $buy     = new purchase();

    if ($artist->usr_id != Session::get('id') && (new Post(Input::get('post')))->getCost($post->post_id) > 0 && !$buy->has($post->post_id)) {
        $image                = $post->artPathRaw;
        list($width, $height) = getimagesize($image);
        $fontSize             = '100';
        $text                 = "$artist->usrnm's Works";
        //
        $xPosition = $width / 2;
        $yPosition = $height / 2;

        $info = getimagesize($image);
        if ($info['mime'] == 'image/jpeg') {
            $newImg = imagecreatefromjpeg($image);
        } elseif ($info['mime'] == 'image/gif') {
            $newImg = imagecreatefromgif($image);
        } elseif ($info['mime'] == 'image/png') {
            $newImg = imagecreatefrompng($image);
            $bg     = imagecreatetruecolor(imagesx($newImg), imagesy($newImg));
            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            imagealphablending($bg, true);
            imagecopy($bg, $newImg, 0, 0, 0, 0, imagesx($newImg), imagesy($newImg));
            imagedestroy($newImg);
            $newImg = $bg;
        }

        $fontColor = imagecolorallocatealpha($newImg, 255, 255, 255, 70);
        $black     = imagecolorallocatealpha($newImg, 0, 0, 0, 30);

        $imageHeight     = imagesy($newImg);
        $distanceY       = 400;
        $maxImageStrings = max(8, $imageHeight / $distanceY);
        $x               = 400;
        for ($i = 0; $i < $maxImageStrings; $i++) {
            $y = $i * $distanceY;
            $x = $i * 100;
            imagettfstroketext($newImg, $fontSize, 45, $x + 115, $y, $fontColor, $black, 'C:\Windows\Fonts\arial.ttf', $text, 3);
        }

        for ($i = 0; $i < $maxImageStrings; $i++) {
            $y = $i * $distanceY;
            $x = $i * 100;
            imagettfstroketext($newImg, $fontSize, 45, $x + 1615, $y, $fontColor, $black, 'C:\Windows\Fonts\arial.ttf', $text, 3);
        }

        ob_start();
        imagepng($newImg);
        $contents = ob_get_contents();
        ob_end_clean();
        $type    = pathinfo($post->artPathRaw, PATHINFO_EXTENSION);
        $dataUri = "data:image/$type;base64," . base64_encode($contents);

        imagedestroy($newImg);
    } else {
        $read = new Reader();
        $read->readBase($post->artPathRaw);
        $type    = pathinfo($post->artPathRaw, PATHINFO_EXTENSION);
        $dataUri = "data:image/" . $type . ";base64," . base64_encode($read->getContent());
        // var_dump($dataUri);
    }
    echo "<img style='width='100%'; height='100%';' src='$dataUri'><img>";
}

function imagettfstroketext(&$image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px)
{

    for ($c1 = ($x - abs($px)); $c1 <= ($x + abs($px)); $c1++) {
        for ($c2 = ($y - abs($px)); $c2 <= ($y + abs($px)); $c2++) {
            $bg = imagettftext($image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);
        }
    }

    return imagettftext($image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
}
$endtime = microtime(true); // Bottom of page

printf("Page loaded in %f seconds", $endtime - $starttime);