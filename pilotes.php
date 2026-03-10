
<link rel="icon" href="thehouse.png" type="image/png">
<link rel="stylesheet" href="style.css">

<?php
session_start();
require "db.php";
include "nav-bar.php";

// Récupérer la recherche si elle est envoyée
$search = '';
if(isset($_GET['search'])){
    $search = trim($_GET['search']);
    $stmt = $pdo->prepare("SELECT * FROM pilotes WHERE prenom LIKE ? OR nom LIKE ? ORDER BY prenom ASC, nom ASC");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM pilotes ORDER BY prenom ASC, nom ASC");
}
$pilotes = $stmt->fetchAll();
?>

<h2 style="color:#00ffff;">Liste des pilotes</h2>

<form method="GET" style="margin-bottom:20px;">
    <input type="text" name="search" placeholder="Rechercher par prénom ou nom..." value="<?= htmlspecialchars($search) ?>" style="padding:5px; width:300px;">
    <button type="submit">Rechercher</button>
</form>

<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-color:#00ffff;" id="pilotesTable">
    <tr style="background:#111; color:#00ffff;">
        <th>Prénom</th>
        <th>Nom</th>
        <?php if(isset($_SESSION['admin'])): ?>
            <th>Numéro de téléphone</th>
        <?php endif; ?>
        <th>Profil</th>
    </tr>
    <?php foreach($pilotes as $p): ?>
    <tr style="background:#1a1a1a; color:#eee;">
        <td><?= htmlspecialchars($p['prenom']) ?></td>
        <td><?= htmlspecialchars($p['nom']) ?></td>
        <?php if(isset($_SESSION['admin'])): ?>
            <td><?= htmlspecialchars($p['telephone']) ?></td>
        <?php endif; ?>
        <td><a href="profil-pilote.php?id=<?= $p['id'] ?>" style="color:#00ffff;">Voir profil</a></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include "footer.php"; ?>