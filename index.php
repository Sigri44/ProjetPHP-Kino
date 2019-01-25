<?php //index.php

    include("start.php");

    $sql = "SELECT id, title, image 
            FROM movie 
            ORDER BY RAND() LIMIT 40";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $movies = $stmt->fetchAll();

    showTop("Kino | Only good movies!");

?>
    <main class="posters">
    <?php

foreach($movies as $movie){
    /*
    echo '<a href="detail.php?id=' . $movie['id'] . '">';
    echo '<img alt="' . $movie['title'] . '"
    src="img/posters/' . $movie['image'] . '">';
    echo '</a>';
    */
    //ou...
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






