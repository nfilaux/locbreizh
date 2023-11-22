<?php
    // supprime variable de session
    session_unset();

    // ferme la session
    session_destroy();

    // je suis redirigé vers la page d'accueil en tant que simple visisteur
    header("location: ../Accueil/accueil_visiteur.php")
?>