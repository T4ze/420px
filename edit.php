<?php

session_start();
require_once('./elements/header_allowed.php');

require_once('./config/config.php');
require_once('./elements/helpers.php');

if (empty($_GET)) {
    header('Location: /profile.php');
    exit();
}

$img = Image::getImageByUserAndId($_GET['id'], $_SESSION['user']['id'], $_SESSION['user']['name']);

if (!$img) { 
    header('Location: /profile.php');
    exit();
}

$filters = $_SESSION['image'][$_GET['id']] ?? [];

if (!empty($_POST)) {
    if (!empty($_POST['quit_action'])) { 
        if ($_POST['quit_action'] == 'save')
            $img->saveFilters($filters);

        $_SESSION['image'][$_GET['id']] = null;
        header('Location: /profile.php');
        exit();
    } else if (!empty($_POST['action'])) { 
        array_pop($filters);
    } else {
        foreach ($_POST as $key => $value) {
            $filters[] = $key;
        }
    }
    
    $_SESSION['image'][$_GET['id']] = $filters;
}

$title = 'Edit';

?>

<!DOCTYPE html>
<html>
<?php include('./elements/header.php'); ?>
<body>
<?php include('./elements/nav.php'); ?>
<section class="section">

<div class="column is-mobile is-half-desktop is-three-quarters-tablet container">
    
    <div class="tile is-ancestor">
        <div class="tile is-parent">
            <article class="tile is-child notification is-info tool-panel">
                <div class="content">
                    <p class="title" style="margin-bottom: 0px">Tools</p>
                    <hr style="margin: 10px"/>
                    <div class="content tools">
                        <form method="POST" action="">
                            <div class="item">
                                <button class="icon" type="submit" name="contrast_minus">
                                    <i class="fa fa-adjust" aria-hidden="true" title="- Contrast"></i>
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                                <button class="icon" type="submit" name="contrast_plus">
                                    <i class="fa fa-adjust" aria-hidden="true" title="+ Contrast"></i>
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="item">
                                <button class="icon" type="submit" name="light_minus">
                                    <i class="fa fa-sun-o" aria-hidden="true" title="- Light"></i>
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                                <button class="icon" type="submit" name="light_plus">
                                    <i class="fa fa-sun-o" aria-hidden="true" title="+ Light"></i>
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="item">
                                <button class="icon" type="submit" name="sepia">
                                    <i class="fa fa-square sepia" aria-hidden="true" title="Sépia"></i>
                                </button>
                                <button class="icon" type="submit" name="grayscale">
                                    <i class="fa fa-square grayscale" aria-hidden="true" title="Gray scale"></i>
                                </button>
                            </div>
                            <div class="item">
                                <button class="icon" type="submit" name="gauss">
                                    <i class="fa fa-square blur" aria-hidden="true" title="Gauss"></i>
                                </button>
                                <button class="icon" type="submit" name="edge">
                                    <i class="fa fa-square-o" aria-hidden="true" title="Edge"></i>
                                </button>
                            </div>
                            <div class="item" style="margin: 25px 0px">
                                <button class="icon" type="submit" name="action" value="back">
                                    <i class="fa fa-undo" aria-hidden="true" title="Back"></i>
                                </button>
                            </div>
                            <div class="item" style="margin: 10px 0px">
                                <button class="button is-danger" type="submit" name="quit_action" value="unsave">
                                    <span class="icon" type="submit" name="save">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </span>
                                </button>
                                <button class="button is-success" type="submit" name="quit_action" value="save">
                                    <span class="icon" type="submit" name="save">
                                        <i class="fa fa-save" aria-hidden="true"></i>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </article>
        </div>
        <div class="tile is-vertical column is-9 is-mobile-9">
            <div class="tile">
                <div class="box">
                    <figure class="image">
                        <?php echo '<img src="data:image/jpg;base64,'. base64_encode($img->withFilters($filters)->getImageBlob()) . '"/>'; ?>
                    </figure>
                    <div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
</div>

</section>
</body>
</html>