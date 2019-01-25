<?php //logout.php

session_start();
unset($_SESSION['user']);
$_SESSION['flash'] = "You have been logged out! Ciao!";
header("Location: login.php");
die();