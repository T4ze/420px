<?php

session_start();
$title = 'Accueil';

require_once('./config/config.php');
require_once('./elements/helpers.php');

?>

<!DOCTYPE html>
<html>
<?php include('./elements/header.php'); ?>
<body>
<?php include('./elements/nav.php'); ?>
<section class="section">

<div class="columns is-multiline is-flex listing">
    <?php
        $images = Image::getRecent(100);
        foreach ($images as $img) {
            echo itemImage($img);
        }
    ?>
</div>

</section>
</body>
</html>