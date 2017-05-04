<?php

session_start();
require_once('./elements/header_allowed.php');

require_once('./config/config.php');
require_once('./elements/helpers.php');

$u = User::userById($_SESSION['user']['id']);
$title = ucfirst($u['username']);

?>

<!DOCTYPE html>
<html>
<?php include('./elements/header.php'); ?>
<body>
<?php include('./elements/nav.php'); ?>
<section class="section">


<div class="column is-half container has-text-centered">
    <a class="button is-info is-outlined" href="/upload.php">
        <span class="icon"><i class="fa fa-upload"></i></span>
        <span>Upload</span>
    </a>
</div>

<?php if (count($u['images']) > 0): ?>
<hr>

<?php if (!empty($err)): ?>
    <div class="column notification is-danger is-one-third container">
        <p><b>ERREUR:</b> <?php echo $err; ?></p>
    </div>
<?php endif; ?>

<div class="columns is-multiline is-flex profile">
    <?php
        $images = $u['images'];
        foreach ($images as $img) {
            echo itemImage($img, true);
        }
    ?>
</div>
<?php endif; ?>
</section>
</body>
</html>