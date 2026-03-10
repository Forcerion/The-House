<link rel="icon" href="thehouse.png" type="image/png">
<link rel="stylesheet" href="style.css">

<?php
session_start();
require "db.php";

if(!isset($_GET['id'])) die("Course introuvable");
$course_id = $_GET['id'];

// Récupérer infos course + circuit
$stmt = $pdo->prepare("
    SELECT c.*, ci.nom as circuit_nom, ci.photo as circuit_photo
    FROM courses c
    JOIN circuits ci ON c.circuit_id = ci.id
    WHERE c.id=?
");
$stmt->execute([$course_id]);
$course = $stmt->fetch();
if(!$course) die("Course introuvable");

// Récupérer tous les participants
$stmt = $pdo->prepare("
    SELECT p.id as pilote_id, p.prenom, p.nom, pa.position
    FROM participations pa
    JOIN pilotes p ON pa.pilote_id = p.id
    WHERE pa.course_id=?
    ORDER BY pa.position ASC
");
$stmt->execute([$course_id]);
$participants = $stmt->fetchAll();

// Séparer top 10, hors top 10 et abandon
$top10 = []; 
$hors_top10_count = 0; 
$abandon_count = 0;

foreach($participants as $p){
    if($p['position'] === null) $abandon_count++;
    elseif($p['position'] <= 10) $top10[] = $p;
    else $hors_top10_count++;
}

include "nav-bar.php";
?>

<h2><?= htmlspecialchars($course['circuit_nom']) ?></h2>
<?php if($course['circuit_photo']): ?>
    <img src="<?= htmlspecialchars($course['circuit_photo']) ?>" alt="Circuit" style="max-width:400px;">
<?php endif; ?>
<p>Date : <?= $course['date_course'] ?> | Statut : <?= $course['statut'] ?></p>
<p>Nombre de participants : <?= count($participants) ?></p>

<h3>Top 10</h3>
<table>
<tr><th>Position</th><th>Pilote</th><th>Cash Prize</th><th>Profil</th></tr>
<?php foreach($top10 as $t):
    $position = $t['position'];
    if($course['cash_prize_active']){
        $cash = $position==1?$course['cash_top1']:($position==2?$course['cash_top2']:($position==3?$course['cash_top3']:0));
    } else $cash = 0;
?>
<tr>
    <td><?= $position ?></td>
    <td><?= htmlspecialchars($t['prenom']." ".$t['nom']) ?></td>
    <td><?= $cash>0?$cash." $":"-" ?></td>
    <td>
        <a href="profil-pilote.php?id=<?= $t['pilote_id'] ?>">Voir profil</a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<h3>Hors top 10</h3>
<p><?= $hors_top10_count ?> participant(s)</p>

<h3>Abandon</h3>
<p><?= $abandon_count ?> participant(s)</p>

<?php include "footer.php"; ?>