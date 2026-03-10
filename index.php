<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>The House - Accueil</title>
    <link rel="icon" href="thehouse.png" type="image/png">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "nav-bar.php"; ?>

<!-- Section Accueil – The House -->
<div class="accueil-container">
    <h1>🚗 Bienvenue sur The House</h1>
    <p>
        The House est une association créée par des passionnés de voitures pour des passionnés de voitures.  
        Notre but ? Proposer des événements uniques et du fun pour tous les amateurs d’automobile ! 🏁
    </p>
    <ul>
        <li>🏎️ Courses hebdomadaires et défis officiels</li>
        <li>🎉 Rassemblements et rasso de voitures</li>
        <li>🎮 Mini-jeux et challenges entre membres</li>
        <li>🥳 Ambiance conviviale et passion partagée</li>
    </ul>
    <p>
        Rejoignez-nous pour vivre votre passion à fond, que vous soyez pilote, amateur de belles mécaniques ou fan de courses en ligne !
    </p>
</div>

<!-- Section The Grid -->
<div class="accueil-container">
    <h2>🏆 Découvrez The Grid</h2>
    <p>
        The Grid est notre plateforme officielle pour suivre le <strong>classement du Top 10 des meilleurs pilotes</strong>.  
        Même si un pilote n’est pas dans le Top 10, vous pouvez consulter son profil et suivre ses performances.  
    </p>
    <div class="grid-buttons">
        <a href="reglement.php" class="btn-grid">📜 Voir le règlement</a>
        <a href="classement.php" class="btn-grid">🏁 Voir le classement</a>
    </div>
</div>
<?php include "footer.php"; ?>
</body>
</html>