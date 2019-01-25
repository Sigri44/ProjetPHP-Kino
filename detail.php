<?php //detail.php

include("start.php");

    //aller chercher ce film dans la bdd
    //en fonction de l'identifiant présent dans l'URL
    $movieId = $_GET['id'];
    $sql = "SELECT movie.* FROM movie
            WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([':id' => $movieId]);

    //utilise ->fetch() puisqu'on ne récupère qu'une ligne
    $movie = $stmt->fetch();

    if (empty($movie)){
        header("HTTP/1.0 404 Not Found");
        include("404.php");
        die();
    }

    //récupère les reviews de ce film
    $sql = "SELECT review.*, users.username AS author FROM review
            JOIN users ON users.id = review.author_id 
            WHERE movie_id = :movie_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([':movie_id' => $movieId]);
    $reviews = $stmt->fetchAll();

    //vérifie si le film est dans la watchlist de cet user
    $isInWatchlist = false;
    if (!empty($_SESSION['user'])) {
        $sql = "SELECT COUNT(*) 
                FROM watchlist 
                WHERE user_id = :user_id 
                AND movie_id = :movie_id";

        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':user_id' => $_SESSION['user']['id'],
            ':movie_id' => $movieId,
        ]);

        $isInWatchlist = (int) $stmt->fetchColumn();
    }

    showTop($movie['title'] . " | Kino");
?>
    <main class="detail">

        <div class="col-left">
            <img src="img/posters/<?= $movie['image'] ?>" alt="<?= $movie['title'] ?>">

            <nav class="detail-links">
                <a href="add-review.php?id=<?= $movie['id'] ?>">Add a review!</a>

                <?php if (!empty($_SESSION['user'])){ ?>
                    <?php if (!$isInWatchlist){ ?>
                    <a href="add-to-watchlist.php?id=<?= $movie['id'] ?>">Add to my watchlist!</a>
                        <?php } else { ?>
                    <a href="remove-from-watchlist.php?id=<?= $movie['id'] ?>">Remove from my watchlist!</a>
                    <?php } ?>
                <?php } ?>
                <a href="share.php?id=<?= $movie['id'] ?>">Share!</a>
            </nav>
        </div>

        <div class="col-right">
            <h1><?= $movie['title']; ?> (<?= $movie['year'] ?>) <span class="rating-value"><?= $movie['rating'] ?>/10</span></h1>
            <p class="genres"><?= $movie['genres'] ?></p>
            <p class="genres"><?= $movie['runtime'] ?> minutes</p>
            <h2>Directed by</h2>
            <p><?= $movie['directors'] ?></p>
            <h2>Stars</h2>
            <p><?= $movie['actors'] ?></p>
            <h2>Synopsis</h2>
            <p><?= $movie['plot'] ?></p>
            <h2>Trailer</h2>
            <div class="videoWrapper">
                <iframe width="460" height="255" src="https://www.youtube.com/embed/<?= $movie['trailer_id'] ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <h2>Last reviews</h2>
            <div class="reviews">
                <?php if (empty($reviews)){ ?>
                    <p>No reviews yet!</p>
                    <a href="add-review.php?id=<?= $movie['id'] ?>">Add a review!</a>
                <?php
                    } else {
                        foreach($reviews as $review){
                            ?>
                            <article class="review">
                                <h3 class="review-title"><?= htmlentities($review['title']) ?></h3>
                                <div class="review-date">Published by <?= $review['author'] ?> on <?= date("Y-m-d", strtotime($review['date_created'])) ?></div>
                                <div class="review-content"><?= nl2br(htmlentities($review['content'])) ?></div>
                            </article>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
    </main>

<?php showBottom(); ?>







