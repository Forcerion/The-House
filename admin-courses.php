<link rel="icon" href="thehouse.png" type="image/png">
<link rel="stylesheet" href="style.css">

<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: admin-login.php");

require "db.php";
$message = "";

// Créer une nouvelle course
if (isset($_POST['create_course'])) {
    $circuit_id = $_POST['circuit_id'];
    $date_course = $_POST['date_course'];
    $cash_active = isset($_POST['cash_prize_active']) ? 1 : 0;
    $cash_top1 = $_POST['cash_top1'] ?? 0;
    $cash_top2 = $_POST['cash_top2'] ?? 0;
    $cash_top3 = $_POST['cash_top3'] ?? 0;

    $stmt = $pdo->prepare("INSERT INTO courses (circuit_id, date_course, statut, cash_prize_active, cash_top1, cash_top2, cash_top3) VALUES (?, ?, 'A venir', ?, ?, ?, ?)");
    $stmt->execute([$circuit_id, $date_course, $cash_active, $cash_top1, $cash_top2, $cash_top3]);
    $message = "Course créée !";
}

// Supprimer une course
if (isset($_POST['delete_course'])) {
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id=?");
    $stmt->execute([$_POST['course_id']]);
    $message = "Course supprimée !";
}

// Modifier une course (date, statut, circuit, cash prize)
if (isset($_POST['update_course'])) {
    $course_id = $_POST['course_id'];
    $date_course = $_POST['date_course'];
    $statut = $_POST['statut'];
    $circuit_id = $_POST['circuit_id'];
    $cash_active = isset($_POST['cash_prize_active']) ? 1 : 0;
    $cash_top1 = $_POST['cash_top1'] ?? 0;
    $cash_top2 = $_POST['cash_top2'] ?? 0;
    $cash_top3 = $_POST['cash_top3'] ?? 0;

    $stmt = $pdo->prepare("UPDATE courses SET date_course=?, statut=?, circuit_id=?, cash_prize_active=?, cash_top1=?, cash_top2=?, cash_top3=? WHERE id=?");
    $stmt->execute([$date_course, $statut, $circuit_id, $cash_active, $cash_top1, $cash_top2, $cash_top3, $course_id]);
    $message = "Course mise à jour !";
}

// Récupérer circuits pour select
$circuits = $pdo->query("SELECT * FROM circuits ORDER BY nom")->fetchAll();

// Récupérer toutes les courses pour gestion (tri du plus récent au plus ancien)
$courses = $pdo->query("
    SELECT c.*, ci.nom as circuit_nom
    FROM courses c
    JOIN circuits ci ON c.circuit_id = ci.id
    ORDER BY c.id DESC
")->fetchAll();

include "nav-bar.php";
?>

<h2>Gestion des courses</h2>
<?php if($message) echo "<p style='color:green;'>$message</p>"; ?>

<h3>Créer une course</h3>
<form method="POST" style="margin-bottom:20px;">
    <label>Circuit :</label>
    <select name="circuit_id" required>
        <?php foreach($circuits as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom']) ?></option>
        <?php endforeach; ?>
    </select>
    <label>Date :</label>
    <input type="date" name="date_course" required>

    <label>
        <input type="checkbox" name="cash_prize_active" value="1"> Cash Prize pour Top 3
    </label>
    <div>
        <label>Top 1 :</label><input type="number" name="cash_top1" value="0" min="0">
        <label>Top 2 :</label><input type="number" name="cash_top2" value="0" min="0">
        <label>Top 3 :</label><input type="number" name="cash_top3" value="0" min="0">
    </div>

    <button type="submit" name="create_course">Créer Course</button>
</form>

<hr>

<h3>Liste des courses existantes</h3>
<table border="1" cellpadding="5" cellspacing="0" style="width:100%">
<tr>
    <th>Circuit</th>
    <th>Date</th>
    <th>Statut</th>
    <th>Cash Prize</th>
    <th>Actions</th>
</tr>

<?php foreach($courses as $co): ?>
<tr>
    <form method="POST">
        <td>
            <select name="circuit_id" required>
                <?php foreach($circuits as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id']==$co['circuit_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="date" name="date_course" value="<?= $co['date_course'] ?>" required></td>
        <td>
            <select name="statut" required>
                <option value="A venir" <?= $co['statut']=="A venir"?'selected':'' ?>>A venir</option>
                <option value="En cours" <?= $co['statut']=="En cours"?'selected':'' ?>>En cours</option>
                <option value="Terminé" <?= $co['statut']=="Terminé"?'selected':'' ?>>Terminé</option>
            </select>
        </td>
        <td>
            <label>
                <input type="checkbox" name="cash_prize_active" value="1" <?= $co['cash_prize_active']?"checked":"" ?>> Oui
            </label>
            <div>
                Top1: <input type="number" name="cash_top1" value="<?= $co['cash_top1'] ?>" min="0">
                Top2: <input type="number" name="cash_top2" value="<?= $co['cash_top2'] ?>" min="0">
                Top3: <input type="number" name="cash_top3" value="<?= $co['cash_top3'] ?>" min="0">
            </div>
        </td>
        <td>
            <input type="hidden" name="course_id" value="<?= $co['id'] ?>">
            <button type="submit" name="update_course">Modifier</button>
            <button type="submit" name="delete_course" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette course ?')">Supprimer</button>
        </td>
    </form>
</tr>
<?php endforeach; ?>
</table>

<?php include "footer.php"; ?>