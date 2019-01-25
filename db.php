<?php
 
    //paramètres de connexion en constantes
    define("DBHOST", "localhost");  //soit localhost, soit l'IP du serveur
    define("DBUSER", "root");       //utilisateur de la base (différent de PHPmyAdmin)
    define("DBPASS", "");           //mot de passe
    define("DBNAME", "kino");           //nom de la base de données
 
    try {
        //connexion à la base avec la classe PDO et le dsn
        $dbh = new PDO(
            'mysql:host='.DBHOST.';dbname='.DBNAME, 
            DBUSER, 
            DBPASS, 
            [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4", //on s'assure de communiquer en utf8
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //on récupère nos données en array associatif par défaut
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING         //on affiche les erreurs. À modifier en prod. 
            ]
        );
    } catch (PDOException $e) { //attrappe les éventuelles erreurs de connexion
        echo 'Erreur de connexion : ' . $e->getMessage();
    }