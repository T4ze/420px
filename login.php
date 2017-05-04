<?php

session_start();
$title = 'Connexion';

require_once('./config/config.php');
require_once('./elements/helpers.php');

if (!empty($_SESSION['user'])) {
    header('Location: index.php');
    exit();
} 

$err = null;
if (!empty($_POST)) {
    if (isset($_POST['login']))
        $err['login'] = User::logUser($_POST['username'], $_POST['password']);
    else if (isset($_POST['register']))
        $err['register'] = User::registerUser($_POST['username'], $_POST['password'], $_POST['confirm']);
}

?>

<!DOCTYPE html>
<html>
<?php include('./elements/header.php'); ?>
<body>
<?php include('./elements/nav.php'); ?>
<section class="section">
    <div class="tile is-ancestor">
      <div class="tile is-6 is-vertical is-child section">
        <div class="tile is-child box">
          <form method="POST" action="login.php">
            <p class="title">Connexion</p>
            <?php echo registError($err, 'login'); ?>
            <div class="field">
                <p class="control has-icons-left">
                    <input class="input" type="text" name="username" placeholder="Username" required>
                    <span class="icon is-small is-left"><i class="fa fa-user"></i></span>
                </p>
            </div>
            <div class="field">
                <p class="control has-icons-left">
                    <input class="input" type="password" name="password" placeholder="Password" required>
                    <span class="icon is-small is-left"><i class="fa fa-lock"></i></span>
                </p>
            </div>
            <div class="field is-grouped has-addons has-addons-right">
                <p class="control">
                    <button class="button is-info" name="login" type="submit">Se Connecter</button>
                </p>
            </div>
          </form>
        </div>
      </div>
      <div class="tile is-child section">
        <div class="tile is-child box">
            <form method="POST" action="login.php">
                <p class="title">Inscription</p>
                <?php echo registError($err, 'register'); ?>
                <div class="field">
                    <p class="control has-icons-left">
                        <input class="input" type="username" name="username" placeholder="Username" required>
                        <span class="icon is-small is-left"><i class="fa fa-user"></i></span>
                    </p>
                </div>
                <div class="field">
                    <p class="control has-icons-left">
                        <input class="input" type="password" name="password" placeholder="Password" required>
                        <span class="icon is-small is-left"><i class="fa fa-lock"></i></span>
                    </p>
                </div>
                <div class="field">
                    <p class="control has-icons-left">
                        <input class="input" type="password" name="confirm" placeholder="Confirm Password" required>
                        <span class="icon is-small is-left"><i class="fa fa-repeat"></i></span>
                    </p>
                </div>
                <div class="field is-grouped has-addons has-addons-right">
                    <p class="control">
                        <button class="button is-outlined is-info" name="register" type="submit">S'inscrire</button>
                    </p>
                </div>
            </form>
        </div>
      </div>
    </div>
</section>
</body>
</html>