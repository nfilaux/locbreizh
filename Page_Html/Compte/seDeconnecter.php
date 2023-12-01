<?php
    session_start();
    // unset les variables de session
    session_unset();
    // fermeture de la session
    session_destroy();
    // JE SUIS REDIRIGER VERS LA PAGE D'ACCEUIL EN TANT QUE SIMPLE VISITEUR
    header("location: ../Accueil/accueil_visiteur.php");
?>