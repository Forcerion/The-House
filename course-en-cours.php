<link rel="icon" href="thehouse.png" type="image/png">
<link rel="stylesheet" href="style.css">

<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: admin-login.php");

// Afficher toutes les erreurs pour debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "db.php";
$message = "";

// Vérifie et définit la course sélectionnée
if (isset($_POST['select_course'])) {
    $course_id = $_POST['course_id'];
} elseif (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
} elseif (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
} else {
    $course_id = null;
}

// Ajouter un pilote à la course
if (isset($_POST['add_pilote'], $course_id)) {
    $pilote_id = (int)$_POST['add_pilote'];

    $stmt = $pdo->prepare("SELECT id FROM participations WHERE course_id=? AND pilote_id=?");
    $stmt->execute([$course_id, $pilote_id]);
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO participations (course_id, pilote_id, position) VALUES (?, ?, NULL)");
        $stmt->execute([$course_id, $pilote_id]);
        $message = "Pilote ajouté à la course !";

        // Mise à jour nombre de courses du pilote
        $pdo->prepare("UPDATE pilotes SET nb_course = nb_course + 1 WHERE id=?")->execute([$pilote_id]);
    } else {
        $message = "Pilote déjà dans la course !";
    }
}

// Mettre à jour la position d’un pilote
if (isset($_POST['update_position'], $_POST['participation_id'], $_POST['position'], $course_id)) {
    $position = $_POST['position'];

    // Convertir Abandon / Hors Top10 en valeur compatible
    if ($position === "abandon") $position = null;
    elseif ($position === "hors_top10") $position = 11;
    else $position = (int)$position;

    $stmt = $pdo->prepare("UPDATE participations SET position=? WHERE id=?");
    $stmt->execute([$position, $_POST['participation_id']]);
    $message = "Position mise à jour !";
}

// Disqualifier un pilote (position = NULL)
if (isset($_POST['disqualify'], $_POST['participation_id'], $course_id)) {
    $stmt = $pdo->prepare("UPDATE participations SET position=NULL WHERE id=?");
    $stmt->execute([$_POST['participation_id']]);
    $message = "Pilote disqualifié / abandon !";
}

// Récupérer toutes les courses "A venir" ou "En cours"
$courses = $pdo->query("
    SELECT c.id, c.date_course, c.statut, ci.nom as circuit_nom
    FROM courses c
    JOIN circuits ci ON c.circuit_id = ci.id
    WHERE c.statut IN ('A venir', 'En cours')
    ORDER BY c.id DESC
")->fetchAll();

// Récupérer tous les pilotes
$pilotes = $pdo->query("SELECT * FROM pilotes ORDER BY nom, prenom")->fetchAll();

// Récupérer les participants si une course est sélectionnée
$participants = [];
if ($course_id) {
    $stmt = $pdo->prepare("
        SELECT pa.id as participation_id, p.prenom, p.nom, pa.position
        FROM participations pa
        JOIN pilotes p ON pa.pilote_id = p.id
        WHERE pa.course_id=?
        ORDER BY pa.position ASC
    ");
    $stmt->execute([$course_id]);
    $participants = $stmt->fetchAll();
}

include "nav-bar.php";
?>

<h2>Course en cours</h2>
<?php if($message) echo "<p>$message</p>"; ?>

<!-- Sélection de la course -->
<form method="POST">
    <label>Sélectionner une course :</label>
    <select name="course_id" required>
        <?php foreach($courses as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($course_id==$c['id'])?'selected':'' ?>>
                <?= htmlspecialchars($c['circuit_nom']) ?> - <?= $c['date_course'] ?> - [<?= $c['statut'] ?>]
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="select_course">Gérer cette course</button>
</form>

<?php if($course_id): ?>
<hr>

<h3>Ajouter un pilote à la course</h3>
<form method="POST">
    <input type="hidden" name="course_id" value="<?= $course_id ?>">
    <label>Pilote :</label>
    <input list="pilotes" name="add_pilote" required>
    <datalist id="pilotes">
        <?php foreach($pilotes as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['prenom'].' '.$p['nom']) ?></option>
        <?php endforeach; ?>
    </datalist>
    <button type="submit">Ajouter pilote</button>
</form>

<h3>Participants actuels</h3>
<table>
<tr>
    <th>Pilote</th>
    <th>Statut / Position</th>
    <th>Actions</th>
</tr>

<?php foreach($participants as $pa): ?>
<tr>
    <td><?= htmlspecialchars($pa['prenom']." ".$pa['nom']) ?></td>
    <td>
        <?php
        if (is_null($pa['position'])) echo "Abandon";
        elseif ($pa['position'] > 10) echo "Hors Top 10";
        else echo $pa['position'];
        ?>
    </td>
    <td>
        <form method="POST" style="display:inline">
            <input type="hidden" name="participation_id" value="<?= $pa['participation_id'] ?>">
            <input type="hidden" name="course_id" value="<?= $course_id ?>">
            <select name="position" required>
                <?php for($i=1;$i<=10;$i++): ?>
                    <option value="<?= $i ?>" <?= ($pa['position']==$i)?'selected':'' ?>><?= $i ?></option>
                <?php endfor; ?>
                <option value="hors_top10" <?= ($pa['position']>10)?'selected':'' ?>>Hors Top 10</option>
                <option value="abandon" <?= (is_null($pa['position']))?'selected':'' ?>>Abandon</option>
            </select>
            <button type="submit" name="update_position">Mettre à jour</button>
        </form>
        <form method="POST" style="display:inline">
            <input type="hidden" name="participation_id" value="<?= $pa['participation_id'] ?>">
            <input type="hidden" name="course_id" value="<?= $course_id ?>">
            <button type="submit" name="disqualify">Disqualifier</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php endif; ?>

<?php include "footer.php"; ?>