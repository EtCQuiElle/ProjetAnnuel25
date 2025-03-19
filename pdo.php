<?php
//variable de connexion 
$dbname = 'mysql:host=localhost; 
            dbname=smaill08; 
            port=1521; 
            charset=utf8'; 
$username = 'smaill08'; 
$password = '22404405'; 

//gestion si erreur de connexion
try {
    $pdo = new PDO($dbname, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connexion réussie';
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}
?>