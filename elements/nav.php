<nav class="nav">
    <div class="nav-left">
        <a class="nav-item" href="/">
            <h1 class="title"><b>420</b>px</h1>
        </a>
    </div>
    <div class="nav-center">
        <div class="nav-item">
            <p class="title is-3"><?php echo $title; ?></p>
        </div>
    </div>

    <div class="nav-right">
        
        <div class="nav-item">
            <div class="field is-grouped">
                <?php if (!empty($_SESSION['user'])): ?>
                    <p class="control">
                        <a class="button is-info" href="/profile.php">
                            <span class="icon"><i class="fa fa-user"></i></span>
                            <span class="is-hidden-mobile"><?php echo $_SESSION['user']['name']; ?></span>
                        </a>
                    </p>
                    <p class="control">
                        <a class="button is-danger is-outlined" href="/logout.php">
                            <span class="icon"><i class="fa fa-sign-out"></i></span>
                        </a>
                    </p>
                <?php else: ?>
                    <p class="control">
                        <a class="button is-success is-outlined" href="/login.php">
                            <span class="icon"><i class="fa fa-sign-out"></i></span>
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>