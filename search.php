<?php

session_start();
$title = 'Search';

require_once('./config/config.php');
require_once('./elements/helpers.php');

if (empty($_GET)) {
    header('Location: /index.php');
    exit();
}

$color = htmlspecialchars($_GET['color']);

$r = hexdec(substr($color,0,2));
$g = hexdec(substr($color,2,2));
$b = hexdec(substr($color,4,2));

$foregroundColor = ($r + $g + $b) < 550 ? 'white' : 'black';

?>

<!DOCTYPE html>
<html>
<?php include('./elements/header.php'); ?>
<body>
<?php include('./elements/nav.php'); ?>
<section class="section">

<div class="box container column is-one-quarter" style="margin-bottom: 3em">
    <div class="colorFinder" style="background-color:#<?php echo $color; ?>; color: <?php echo $foregroundColor;?>">
        <?php echo "#$color"; ?> | <?php echo "rgb($r, $g, $b)"; ?>
    </div>
</div>

<div class="columns is-multiline is-flex listing">
    <?php
        $images = Image::getImagesByColor($color);
        foreach ($images as $img)
            echo itemImage($img);
    ?>
</div>

</section>
</body>
</html>