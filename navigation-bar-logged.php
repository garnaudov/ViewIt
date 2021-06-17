<!DOCTYPE html>
    <?php
            if (isset($_GET['logout'])) {
                session_start();
                unset($_SESSION);
                session_destroy();
                session_write_close();
                header("Location: login.php?logoutsuccess");
                die;
              }
    ?>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Navbar</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="navigation-bar.styles.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:500&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <nav>
                <ul class="nav__links">
                    <li><a href="homepage.php">Начало</a></li>
                    <li><a href="vied-galleries.php">Виж галерии</a></li>
                    <li><a href="create-gallery.php">Създай галерия</a></li>
                </ul>
            </nav>
            <a class="cta" href="navigation-bar-logged.php?logout=true">Изход</a>
            <p class="menu cta">Меню</p>
        </header>
        <div class="overlay">
            <a class="close">&times;</a>
            <div class="overlay__content">
                <li><a href="homepage.php">Начало</a></li>
                <li><a href="vied-galleries.php">Виж галерии</a></li>
                <li><a href="create-gallery.php">Създай галерия</a></li>
            </div>
        </div>
        <script type="text/javascript" src="mobile.js"></script>
    </body>
    
</html>