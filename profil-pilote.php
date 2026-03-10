<link rel="icon" href="thehouse.png" type="image/png">
<link rel="stylesheet" href="style.css">

<?php
session_start();
require "db.php";

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    die("Pilote introuvable");
}
$pilote_id = (int)$_GET['id'];

// Récupérer infos pilote
$stmt = $pdo->prepare("SELECT * FROM pilotes WHERE id=?");
$stmt->execute([$pilote_id]);
$pilote = $stmt->fetch();
if(!$pilote) die("Pilote introuvable");

// Récupérer tous les circuits
$circuits = $pdo->query("SELECT * FROM circuits ORDER BY nom")->fetchAll();

// Initialiser stats globales
$total_courses = 0;
$top1 = $top2 = $top3 = 0;

// Préparer stats par circuit
$stats_circuit = [];
foreach($circuits as $c){
    $stats_circuit[$c['id']] = [
        'nom' => $c['nom'],
        'photo' => $c['photo'],
        'participations' => 0,
        'top1' => 0,
        'top2' => 0,
        'top3' => 0,
        'top4_7' => 0,
        'top8_10' => 0,
        'hors_top10' => 0,
        'abandon' => 0
    ];
}

// Récupérer toutes les participations du pilote
$stmt = $pdo->prepare("
    SELECT pa.position, c.circuit_id
    FROM participations pa
    JOIN courses c ON pa.course_id = c.id
    WHERE pa.pilote_id=?
");
$stmt->execute([$pilote_id]);
$participations = $stmt->fetchAll();

foreach($participations as $p){
    $cid = $p['circuit_id'];
    $pos = $p['position'];

    $stats_circuit[$cid]['participations']++;
    $total_courses++;

    if($pos === null || $pos == 0){
        $stats_circuit[$cid]['abandon']++;
    } elseif($pos == 1){
        $stats_circuit[$cid]['top1']++;
        $top1++;
    } elseif($pos == 2){
        $stats_circuit[$cid]['top2']++;
        $top2++;
    } elseif($pos == 3){
        $stats_circuit[$cid]['top3']++;
        $top3++;
    } elseif($pos >= 4 && $pos <=7){
        $stats_circuit[$cid]['top4_7']++;
    } elseif($pos >= 8 && $pos <=10){
        $stats_circuit[$cid]['top8_10']++;
    } else {
        $stats_circuit[$cid]['hors_top10']++;
    }
}

include "nav-bar.php";
?>

<h2 style="color:#00ffff;">Profil de <?= htmlspecialchars($pilote['prenom']." ".$pilote['nom']) ?></h2>

<?php if(isset($_SESSION['admin'])): ?>
    <p>Numéro de téléphone : <?= htmlspecialchars($pilote['telephone']) ?></p>
<?php endif; ?>

<p>Total courses : <?= $total_courses ?> | Top 1 : <?= $top1 ?> | Top 2 : <?= $top2 ?> | Top 3 : <?= $top3 ?></p>

<h3 style="color:#00ffff;">Participation par circuit</h3>
<?php foreach($stats_circuit as $sc): ?>
    <div style="margin-bottom:20px; border:1px solid #00ffff; padding:10px;">
        <h4><?= htmlspecialchars($sc['nom']) ?></h4>
        <?php if($sc['photo']): ?>
            <img src="<?= htmlspecialchars($sc['photo']) ?>" alt="Circuit" style="max-width:200px;">
        <?php endif; ?>
        <p>Participations : <?= $sc['participations'] ?></p>
        <ul>
            <li>Top 1 : <?= $sc['top1'] ?></li>
            <li>Top 2 : <?= $sc['top2'] ?></li>
            <li>Top 3 : <?= $sc['top3'] ?></li>
            <li>Top 4-7 : <?= $sc['top4_7'] ?></li>
            <li>Top 8-10 : <?= $sc['top8_10'] ?></li>
            <li>Hors top 10 : <?= $sc['hors_top10'] ?></li>
            <li>Abandon : <?= $sc['abandon'] ?></li>
        </ul>
    </div>
<?php endforeach; ?>
<?php include "footer.php"; ?>