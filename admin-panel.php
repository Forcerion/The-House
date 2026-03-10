<link rel="stylesheet" href="style.css">

<?php
include_once "db.php";

session_start();
if (!isset($_SESSION["admin"])) { header("Location: admin-login.php"); exit; }

$pilotes = $pdo->query("SELECT * FROM pilotes ORDER BY nom")->fetchAll();
?>
<?php include "nav-bar.php"; ?>

<h1>Panneau Admin</h1>

<form method="POST" action="update-score.php">
    <label>Pilote :</label>
    <select name="pilote_id">
        <?php foreach ($pilotes as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['nom'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Position :</label>
    <select name="position">
        <?php for ($i=1; $i<=10; $i++): ?>
            <option value="<?= $i ?>">Top <?= $i ?></option>
        <?php endfor; ?>
    </select>

    <button type="submit">Ajouter un résultat</button>
</form>

<?php include "footer.php"; ?>