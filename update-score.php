<link rel="stylesheet" href="style.css">

<?php
include_once "db.php";

session_start();
if (!isset($_SESSION["admin"])) { header("Location: admin-login.php"); exit; }



$pilote_id = $_POST["pilote_id"];
$position = $_POST["position"];

$stmt = $pdo->prepare("INSERT INTO resultats (pilote_id, position) VALUES (?, ?)");
$stmt->execute([$pilote_id, $position]);

header("Location: admin-panel.php?success=1");
exit;
?>