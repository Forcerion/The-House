<link rel="icon" href="thehouse.png" type="image/png">
<link rel="stylesheet" href="style.css">

<?php

session_start();
require "db.php";

// Récupérer le top 10 des pilotes selon top1 > top2 > top3
$top_pilotes = $pdo->query("
    SELECT p.id, p.prenom, p.nom,
        COUNT(pa.id) AS total_courses,
        SUM(pa.position=1) AS top1,
        SUM(pa.position=2) AS top2,
        SUM(pa.position=3) AS top3
    FROM pilotes p
    LEFT JOIN participations pa ON p.id = pa.pilote_id
    GROUP BY p.id
    ORDER BY top1 DESC, top2 DESC, top3 DESC
    LIMIT 10
")->fetchAll();

include "nav-bar.php";


?>

<?php
$trophy = "";
if ($position == 1) $trophy = "🥇";
elseif ($position == 2) $trophy = "🥈";
elseif ($position == 3) $trophy = "🥉";
?>
<td class="podium-<?= $position ?>"><?= $trophy . $position ?></td>

<h2>Classement Top 10 des pilotes</h2>

<table border="1" cellpadding="5" cellspacing="0">
<tr>
    <th>Rang</th>
    <th>Pilote</th>
    <th>Total courses</th>
    <th>Top 1</th>
    <th>Top 2</th>
    <th>Top 3</th>
    <th>Profil</th>
</tr>

<?php $rank=1; foreach($top_pilotes as $p): ?>
<tr>
    <td><?= $rank++ ?></td>
    <td><?= htmlspecialchars($p['prenom']." ".$p['nom']) ?></td>
    <td><?= $p['total_courses'] ?></td>
    <td><?= $p['top1'] ?></td>
    <td><?= $p['top2'] ?></td>
    <td><?= $p['top3'] ?></td>
    <td><a href="profil-pilote.php?id=<?= $p['id'] ?>">Voir profil</a></td>
</tr>
<?php endforeach; ?>
</table>

<?php include "footer.php"; ?>