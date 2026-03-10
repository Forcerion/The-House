<link rel="icon" href="thehouse.png" type="image/png">
<link rel="stylesheet" href="style.css">


<?php
session_start();
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username=? AND password=?");
    $stmt->execute([$username, $password]);
    $admin = $stmt->fetch();

    if ($admin) {
        $_SESSION["admin"] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Identifiants incorrects.";
    }
}

include "nav-bar.php";
?>

<h2>Connexion Admin</h2>
<form method="POST">
    <label>Nom d'utilisateur :</label>
    <input type="text" name="username" required>
    <label>Mot de passe :</label>
    <input type="password" name="password" required>
    <button type="submit">Connexion</button>
    <?php if (!empty($error)) echo "<p>$error</p>"; ?>
</form>

<?php include "footer.php"; ?>