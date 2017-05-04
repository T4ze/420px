<?php

session_start();
require_once('./elements//header_allowed.php');
require_once('./config/config.php');
require_once('./elements/helpers.php');

if (!empty($_FILES)) {
    $ds          = DIRECTORY_SEPARATOR;
    $storeFolder = 'public';
    $tempFile = $_FILES['file']['tmp_name'];

    $image = new Imagick($_FILES['file']['tmp_name']);
    $image->scaleImage(420, 420, 0);
    $image->setImageFormat( "jpg" );

    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds . $_SESSION['user']['id'] . $ds;
    $targetFilename = md5(time()) . ".jpg";
    Image::uploadByUser($_SESSION['user']['id'], $targetFilename, $_FILES['file']['tmp_name']);
    $image->writeImage($targetPath . $targetFilename);

    return;
}

$u = User::userById($_SESSION['user']['id']);
$title = ucfirst($u['username']);

?>

<!DOCTYPE html>
<html>
<?php include('./elements/header.php'); ?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css"/>
<body>
<?php include('./elements/nav.php'); ?>
<section class="section">
<div class="columns is-mobile">
    <div class="box column is-half-desktop is-three-quarters-tablet container upload-container">
        <form action="/upload.php" method="POST" class="dropzone dz-clickable" id="myAwesomeDropzone" style="border: none">
            <div class="upload-block media ">
                <p class="icon" style="margin: 0 auto;"><i class="fa fa-upload" style="font-size: 36px"></i></p>
            </div>
        </form>
    </div>
</div>

</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>
<script>
Dropzone.options.myAwesomeDropzone = {
  paramName: "file",
  maxFilesize: 2,
  accept: function(file, done) {
    // call done with arg to cancel upload
    done();
    setTimeout(function() {
        window.location.href = '/profile.php';
    }, 500);
  }
};
</script>
</body>
</html>