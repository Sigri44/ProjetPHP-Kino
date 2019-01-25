<?php //add-to-watchlist.php

include("start.php");

//si le user n'est pas connecté, il n'a rien à faire ici
if (empty($_SESSION['user'])){
    header("Location: login.php");
    die();
}

$movieId = $_GET['id'];
$userId = $_SESSION['user']['id'];

$sql = "INSERT INTO watchlist 
        VALUES (:movieId, :userId, NOW())";
$stmt = $dbh->prepare($sql);
$stmt->execute([
    ":movieId" => $movieId,
    ":userId" => $userId,
]);

$_SESSION['flash'] = 'Movie added!';
header("Location: watchlist.php");