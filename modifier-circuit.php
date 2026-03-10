<?php
session_start();
require "db.php";
include "nav-bar.php";

if(!isset($_SESSION['admin'])) die("Accès refusé");

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    die("Circuit introuvable");
}

$cid = (int)$_GET['id'];

// Récupérer circuit
$stmt = $pdo->prepare("SELECT * FROM circuits WHERE id=?");
$stmt->execute([$cid]);
$circuit = $stmt->fetch();
if(!$circuit) die("Circuit introuvable");

// Traitement du formulaire
if(isset($_POST['nom'])){
    $nom = trim($_POST['nom']);
    $photo = trim($_POST['photo']); // URL de l'image ou chemin

    $stmt = $pdo->prepare("UPDATE circuits SET nom=?, photo=? WHERE id=?");
    $stmt->execute([$nom, $photo, $cid]);

    header("Location: circuits.php");
    exit;
}
?>

<h2 style="color:#00ffff;">Modifier le circuit</h2>

<form method="POST">
    <label>Nom du circuit :</label><br>
    <input type="text" name="nom" value="<?= htmlspecialchars($circuit['nom']) ?>" required><br><br>

    <label>URL ou chemin de l'image :</label><br>
    <input type="text" name="photo" value="<?= htmlspecialchars($circuit['photo']) ?>"><br><br>

    <button type="submit">Modifier</button>
</form>
<?php include "footer.php"; ?>