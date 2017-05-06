<?php

session_start();

if (empty($_GET['id'])) {
    header('Location: /');
    exit();
}

require_once('./config/config.php');

if (!empty($_GET['type']) && $_GET['type'] == 'rss') {
    header('Content-Type: text/rss+xml; charset=ISO-8859-1', true);
    $u = User::userById($_GET['id']);

    $rss = new SimpleXMLElement('<rss xmlns:atom="http://www.w3.org/2005/Atom"></rss>');
    $rss->addAttribute('version', '2.0');

    $channel = $rss->addChild('channel');
    $title = $channel->addChild('title','420px : Images of ' .  ucfirst($u['username']));
    $description = $channel->addChild('description','List of user images');
    $link = $channel->addChild('link','http://localhost/');
    $language = $channel->addChild('language','en-us');

    $date_f = date("D, d M Y H:i:s T", time());
    $build_date = gmdate(DATE_RFC2822, strtotime($date_f)); 
    $lastBuildDate = $channel->addChild('lastBuildDate',$build_date);

    foreach ($u['images'] as $image) {
        $item = $channel->addChild('item');
        $item_id = $item->addChild('title', $image['id']);
        $item_path = $item->addChild('link', 'http://localhost/' . $image['path']);
        $lastBuildDate = gmdate(DATE_RFC2822, strtotime($image['created_at']));
        $item_date = $item->addChild('pubDate', $lastBuildDate);
        $guid = $item->addChild('guid', 'http://localhost/' . $image['path']);
        $guid->addAttribute('isPermaLink', 'false'); 
    }

    echo $rss->asXML();
    return;
}

if (isset($_SESSION['user']) && $_GET['id'] == $_SESSION['user']['id']) {
    header('Location: /profile.php');
    exit();
}

require_once('./elements/helpers.php');

$u = User::userById($_GET['id']);
$title = ucfirst($u['username']);

?>


<!DOCTYPE html>
<html>
<?php include('./elements/header.php'); ?>
<body>
<?php include('./elements/nav.php'); ?>
<section class="section">

<div class="columns is-multiline is-flex">
    <?php
        $images = $u['images'];
        foreach ($images as $img) {
            echo itemImage($img);
        }
    ?>
</div>
</section>
</body>
</html>