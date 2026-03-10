<link rel="stylesheet" href="style.css">

<nav>

<div class="logo">
        <a href="index.php"><img src="thehouse.png" alt="The House" class="logo-img"></a>
    <!-- Liens publics -->
    <a href="index.php">Accueil</a>
    <a href="reglement.php">Réglement</a>
    <a href="classement.php">Classement</a>
    <a href="circuits.php">Circuits</a>
    <a href="pilotes.php">Pilotes</a>
    <a href="historique-courses.php">Historique des courses</a>
    <a href="a-propos.php">A propos de nous</a>

    <?php if(isset($_SESSION['admin'])): ?>
        <!-- Liens admin -->
        <a href="admin-courses.php">Gérer Courses</a>
        <a href="admin-circuits.php">Ajouter / Modifier Circuit</a>
        <a href="admin-pilotes.php">Ajouter Pilote</a>
        <a href="course-en-cours.php">Course en cours</a>

        <div style="float:right;">
            <span>Admin : <?= htmlspecialchars($_SESSION['admin']) ?></span>
            <a href="admin-logout.php">Déconnexion</a>
        </div>
    <?php else: ?>
        <!-- Bouton de connexion si admin pas connecté -->
        <div style="float:right;">
            <a href="admin-login.php">Connexion Admin</a>
        </div>
    <?php endif; ?>
</nav>