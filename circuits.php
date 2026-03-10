<link rel="icon" href="thehouse.png" type="image/png">
<link rel="stylesheet" href="style.css">

<?php

session_start();
require "db.php";
include "nav-bar.php";

// Supprimer un circuit si admin clique sur Supprimer
if(isset($_GET['supprimer']) && isset($_SESSION['admin'])){
    $cid = (int)$_GET['supprimer'];
    $stmt = $pdo->prepare("DELETE FROM circuits WHERE id=?");
    $stmt->execute([$cid]);
    header("Location: circuits.php");
    exit;
}

// Récupérer tous les circuits
$circuits = $pdo->query("SELECT * FROM circuits ORDER BY nom")->fetchAll();
?>

<h2 style="color:#00ffff;">Liste des circuits</h2>

<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-color:#00ffff;">
    <tr style="background:#111; color:#00ffff;">
        <th>Nom du circuit</th>
        <th>Photo</th>
        <?php if(isset($_SESSION['admin'])): ?>
            <th>Actions</th>
        <?php endif; ?>
    </tr>

    <?php foreach($circuits as $c): ?>
    <tr style="background:#1a1a1a; color:#eee;">
        <td><?= htmlspecialchars($c['nom']) ?></td>
        <td>
            <?php if($c['photo']): ?>
                <img src="<?= htmlspecialchars($c['photo']) ?>" alt="Circuit" style="max-width:150px;">
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
        <?php if(isset($_SESSION['admin'])): ?>
        <td>
            <a href="modifier-circuit.php?id=<?= $c['id'] ?>">Modifier</a> |
            <a href="circuits.php?supprimer=<?= $c['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce circuit ?');">Supprimer</a>
        </td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
</table>

<?php include "footer.php"; ?>