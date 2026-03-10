<link rel="icon" href="thehouse.png" type="image/png">
<link rel="stylesheet" href="style.css">

<?php

session_start();
require "db.php";

// Récupérer toutes les courses du plus récent au plus ancien
$courses = $pdo->query("
    SELECT c.id, c.date_course, c.statut, ci.nom as circuit_nom
    FROM courses c
    JOIN circuits ci ON c.circuit_id = ci.id
    ORDER BY c.id DESC
")->fetchAll();

include "nav-bar.php";
?>

<h2>Historique des courses</h2>

<table border="1" cellpadding="5" cellspacing="0" style="width:100%">
<tr>
    <th>Date</th>
    <th>Circuit</th>
    <th>Statut</th>
    <th>Action</th>
</tr>

<?php foreach($courses as $c): ?>
<tr>
    <td><?= htmlspecialchars($c['date_course']) ?></td>
    <td><?= htmlspecialchars($c['circuit_nom']) ?></td>
    <td><?= htmlspecialchars($c['statut']) ?></td>
    <td>
        <a href="course-detail.php?id=<?= $c['id'] ?>">Plus d'information</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
<?php include "footer.php"; ?>