<?php //functions.php

function showTop($title){
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
        <link rel="stylesheet" href="css/app.css">
        <link rel="icon" href="img/logo-black.png">
        <title><?= $title ?></title>
    </head>
    <body>

        <header>
            <div class="container">
                <div class="site-title"><a href="index.php"><img src="img/logo-white.png" alt="Kino's logo">Kino!</a></div>
                <nav>
                    <a href="index.php">Home</a>
                    <?php if (empty($_SESSION['user'])) { ?>
                    <a href="register.php">Register</a>
                    <a href="login.php">Login</a>
                    <?php } else { ?>
                    <a href="watchlist.php">My watchlist</a>
                    <a href="logout.php">Logout <?= $_SESSION['user']['username'] ?></a>
                    <?php } ?>
                </nav>
            </div>
        </header>

        <?php
            //si on a un message dans la session...
            if (!empty($_SESSION['flash'])){
                //alors on l'affiche...
                echo '<div class="alert">';
                echo $_SESSION['flash'];
                echo '</div>';

                //puis on le supprime
                unset($_SESSION['flash']);
            }
        ?>
        <div class="container main-container">
    <?php
}

function showBottom(){
    ?>
    </div> <!-- fermeture du container -->
        <footer>
            <div class="container">
                <p>&copy; Kino!</p>
                <nav>
                    <a href="legal.php">Legal stuff</a>
                    <a href="contact.php">Contact us</a>
                    <a href="faq.php">FAQ</a>
                </nav>
            </div>
        </footer>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/app.js"></script>
    </body>
    </html>
    <?php
}