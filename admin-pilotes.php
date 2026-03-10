<link rel="icon" href="thehouse.png" type="image/png">
<link rel="stylesheet" href="style.css">

<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: admin-login.php");

require "db.php";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['prenom'], $_POST['nom'], $_POST['telephone'])) {
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $tel = trim($_POST['telephone']);

    $stmt = $pdo->prepare("SELECT id FROM pilotes WHERE prenom=? AND nom=?");
    $stmt->execute([$prenom, $nom]);
    $existing = $stmt->fetch();

    if ($existing) $message = "Pilote déjà existant.";
    else {
        $stmt = $pdo->prepare("INSERT INTO pilotes (prenom, nom, telephone) VALUES (?, ?, ?)");
        $stmt->execute([$prenom, $nom, $tel]);
        $message = "Pilote créé !";
    }
}

$pilotes = $pdo->query("SELECT * FROM pilotes ORDER BY nom, prenom")->fetchAll();
include "nav-bar.php";
?>

<h2>Gestion des pilotes</h2>
<?php if($message) echo "<p style='color:green;'>$message</p>"; ?>

<h3>Ajouter un pilote</h3>
<form method="POST">
    <label>Prénom :</label>
    <input type="text" name="prenom" required>
    <label>Nom :</label>
    <input type="text" name="nom" required>
    <label>Téléphone :</label>
    <input type="text" name="telephone" required>
    <button type="submit">Créer Pilote</button>
</form>

<h3>Liste des pilotes existants :</h3>
<ul>
<?php foreach($pilotes as $p): ?>
    <li><?= htmlspecialchars($p['prenom']." ".$p['nom']) ?> (<?= $p['telephone'] ?>)</li>
<?php endforeach; ?>
</ul>

<?php include "footer.php"; ?>