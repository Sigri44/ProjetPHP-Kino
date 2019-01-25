<?php //detail.php

include("start.php");

if (empty($_SESSION['user'])){

    $_SESSION['flash'] = "Please log in!";
    header("Location: login.php");
    die();
}

//traitement du formulaire
$title = "";
$content = "";
$movieId = $_GET['id'];
$errors = [];

if (!empty($_POST)){
    $title = strip_tags($_POST['title']);
    $content = strip_tags($_POST['content']);

    //validation
    if (empty($title)){
        $errors['title'] = "Please provide a title for your review!";
    }
    elseif(strlen($title) < 3){
        $errors['title'][] = "Minimum 3 characters please!";
    }
    elseif(strlen($title) > 255){
        $errors['title'][] = "Maximum 255 characters please!";
    }

    if (empty($content)){
        $errors['content'] = "Please write a review!";
    }
    elseif(strlen($content) < 12){
        $errors['content'][] = "Minimum 12 characters please!";
    }
    elseif(strlen($content) > 10000){
        $errors['content'][] = "Maximum 10,000 characters please!";
    }

    //all's good ?
    if (empty($errors)){
        //insert in db
        $sql = "INSERT INTO review (movie_id, author_id, title, content, date_created) 
                VALUES (:movie_id, :author_id, :title, :content, NOW())";

        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':movie_id' => $movieId,
            ':author_id' => $_SESSION['user']['id'],
            ':title' => $title,
            ':content' => $content
        ]);

        $_SESSION['flash'] = "Review published! Thanks!";
        header("Location: detail.php?id=" . $movieId);
        die();
    }
}

//aller chercher ce film dans la bdd
//en fonction de l'identifiant présent dans l'URL
$sql = "SELECT * FROM movie
        WHERE id = :id";
$stmt = $dbh->prepare($sql);
$stmt->execute([':id' => $movieId]);

//utilise ->fetch() puisqu'on ne récupère qu'une ligne
$movie = $stmt->fetch();

showTop($movie['title'] . " | Kino");
?>
<main class="detail add-review">
    <!-- affichage des détails du film -->
    <div class="col-left">
        <img src="img/posters/<?= $movie['image'] ?>" alt="<?= $movie['title'] ?>">

        <nav class="detail-links">
            <a href="detail.php?id=<?= $movie['id'] ?>">Back to the movie!</a>
            <a href="add-to-watchlist.php?id=<?= $movie['id'] ?>">Add to my watchlist!</a>
            <a href="share.php?id=<?= $movie['id'] ?>">Share!</a>
        </nav>
    </div>
    <div class="col-right">
        <h1>Leave a review for <em><?= $movie['title']; ?></em></h1>

        <form method="post" novalidate>
            <div>
                <label for="title">Give your review a title!</label>
                <input type="text" name="title" id="title"
                       maxlength="300" placeholder="Super movie"
                       value="<?= $title ?>">
                <div class="error">
                    <?php
                    if(!empty($errors['title'])) {
                        foreach ($errors['title'] as $message) {
                            echo "<p>$message</p>";
                        }
                    }
                    ?>
                </div>
            </div>
            <div>
                <label for="content">Your review</label>
                <textarea name="content" id="content"
                       maxlength="10000" placeholder="Wow, pretty intense!"></textarea>
                    <div class="error">
                    <?php
                    if(!empty($errors['email'])) {
                        foreach($errors['email'] as $message){
                            echo "<p>$message</p>";
                        }
                    }
                    ?>
                </div>
            </div>
            <button>Send!</button>
        </form>

        <h2>Last reviews</h2>
        <div class="reviews">
            <?php if (empty($reviews)){ ?>
                <p>No reviews yet!</p>
            <?php } ?>
        </div>
    </div>
</main>

<?php showBottom(); ?>