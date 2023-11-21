<?php
    // supprime variable de session
    session_unset();
    // ferme la session
    session_destroy();
    // JE SUIS REDIRIGER VERS LA PAGE D'ACCEUIL EN TANT QUE SIMPLE VISITEUR
    header("location: ../Accueil/accueil_visiteur.php")
?>