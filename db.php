<?php
$host = "127.0.0.1";   // ou "localhost"
$port = 3306;           // port MAMP MySQL
$user = "root";         // par défaut MAMP
$pass = "root";         // mot de passe par défaut MAMP
$dbname = "the_grid";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>