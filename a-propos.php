<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>The House - Notre Histoire</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="thehouse.png" type="image/png">
</head>
<body>

<?php include "nav-bar.php"; ?>

<!-- Section Notre Histoire -->
<div class="histoire-container">
    <h2>🏁 Notre Histoire</h2>
    <div class="histoire-content">
        <div class="histoire-image">
            <img src="la-catrina.png" alt="La Catrina">
        </div>
        <div class="histoire-text">
            <p>
                The House est né dans les rues de Mexicali, à la frontière entre le Mexique et les États-Unis.  
                À la base, c’était juste une bande de jeunes latinos perdus, tous unis par une seule chose : leur amour des voitures et de la vitesse.  
                Mais très vite, les courses de quartier sont devenues des courses illégales, puis des transports de produits pour les cartels de plus en plus gros. Par la suite, elle est devenue une organisation à part entière.
            </p>
            <p>
                Fondée par <strong>“La Catrina”</strong>, ancienne mécanicienne pour les trafiquants de drogues devenue pilote, The House s’est forgée une réputation grâce à leurs nombreuses victoires.  
                Mais après un gros coup qui a mal tourné à Tijuana, la pression des autorités est devenue trop forte et The House a dû fuir pour Los Santos.  
                Cette dernière était la ville parfaite, pleine d'opportunités notamment du fait des conflits des gangs et du manque de contrôle dans les rues.  
                Malheureusement <strong>“La Catrina”</strong> est décédée à la suite d’un 400 m qu’elle a fui et a dû laisser sa place à son meilleur pilote.  
                Ce dernier a partiellement mis de côté l’aspect transport de drogue mais a continué les courses de rues avec la nouvelle génération du groupe… 🚨
            </p>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
</body>
</html>