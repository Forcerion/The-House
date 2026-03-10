<link rel="icon" href="thehouse.png" type="image/png">
<link rel="stylesheet" href="style.css">

<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: admin-login.php");

require "db.php";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['nom']) && isset($_FILES['photo'])) {
    $nom = trim($_POST['nom']);
    if ($_FILES['photo']['error'] === 0) {
        $photo_name = time() . "_" . basename($_FILES['photo']['name']);
        $target_dir = "uploads/circuits/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        move_uploaded_file($_FILES['photo']['tmp_name'], $target_dir . $photo_name);

        $stmt = $pdo->prepare("INSERT INTO circuits (nom, photo) VALUES (?, ?)");
        $stmt->execute([$nom, $target_dir.$photo_name]);
        $message = "Circuit créé !";
    } else $message = "Veuillez sélectionner une photo.";
}

$circuits = $pdo->query("SELECT * FROM circuits ORDER BY nom")->fetchAll();
include "nav-bar.php";
?>

<h2>Gestion des circuits</h2>
<?php if($message) echo "<p style='color:green;'>$message</p>"; ?>

<h3>Ajouter un circuit</h3>
<form method="POST" enctype="multipart/form-data">
    <label>Nom :</label>
    <input type="text" name="nom" required>
    <label>Photo :</label>
    <input type="file" name="photo" accept="image/*" required>
    <button type="submit">Créer Circuit</button>
</form>

<h3>Liste des circuits existants :</h3>
<ul>
<?php foreach($circuits as $c): ?>
    <li><?= htmlspecialchars($c['nom']) ?><br>
        <?php if($c['photo']) echo "<img src='{$c['photo']}' style='max-width:150px; max-height:100px;'>"; ?>
    </li>
<?php endforeach; ?>
</ul>
<?php include "footer.php"; ?>