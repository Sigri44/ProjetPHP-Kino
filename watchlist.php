<?php //index.php

include("start.php");

if (empty($_SESSION['user'])){

    //si on voulait interdire l'accès à certains users...
    /*
    if ($_SESSION['user']['role'] != 'admin') {
        header('HTTP/1.0 403 Forbidden');
        include("403.php");
        die();
    }
    */

    $_SESSION['flash'] = "Please log in!";
    header("Location: login.php");
    die();
}

$sql = "SELECT movie.id, movie.title, movie.image 
        FROM movie 
        JOIN watchlist 
        ON watchlist.movie_id = movie.id 
        WHERE watchlist.user_id = :user_id 
        ORDER BY watchlist.date_added DESC 
        LIMIT 200";

$stmt = $dbh->prepare($sql);
$stmt->execute([':user_id' => $_SESSION['user']['id']]);

$movies = $stmt->fetchAll();

showTop("My watchlist | Kino");

?>
<main class="posters">
    <?php

    foreach($movies as $movie){
        ?>
        <a href="detail.php?id=<?= $movie['id'] ?>">
            <img src="img/posters/<?= $movie['image'] ?>"
                 alt="<?= $movie['title'] ?>">
        </a>
        <?php
    }
    ?>
</main>

<?php showBottom(); ?>