<?php

session_start();
require_once('./elements/header_allowed.php');
require_once('./config/config.php');

if (!empty($_GET)) {
    Image::deleteById($_SESSION['user']['id'], $_GET['id']);
}

header('Location: /profile.php');
exit();

?>
