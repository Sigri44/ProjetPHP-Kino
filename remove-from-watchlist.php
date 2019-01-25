<?php //remove-from-watchlist.php

include("start.php");

//si le user n'est pas connecté, il n'a rien à faire ici
if (empty($_SESSION['user'])){
    header("Location: login.php");
    die();
}

$movieId = $_GET['id'];
$userId = $_SESSION['user']['id'];

$sql = "DELETE FROM watchlist 
        WHERE movie_id = :movieId 
        AND user_id = :userId";
$stmt = $dbh->prepare($sql);
$stmt->execute([
    ":movieId" => $movieId,
    ":userId" => $userId,
]);

$_SESSION['flash'] = 'Movie removed!';
header("Location: watchlist.php");