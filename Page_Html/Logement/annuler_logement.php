<?php
    session_start();

    unlink("../Ressources/Images/{$_SESSION['post_logement']['image1P']}");
    if (isset($_SESSION['post_logement']['image2P'])) {
        unlink("../Ressources/Images/{$_SESSION['post_logement']['image2P']}");
    }

    if (isset($_SESSION['post_logement']['image3P'])) {
        unlink("../Ressources/Images/{$_SESSION['post_logement']['image3P']}");
    }

    if (isset($_SESSION['post_logement']['image4P'])) {
        unlink("../Ressources/Images/{$_SESSION['post_logement']['image4P']}");
    }

    if (isset($_SESSION['post_logement']['image5P'])) {
        unlink("../Ressources/Images/{$_SESSION['post_logement']['image5P']}");
    }

    if (isset($_SESSION['post_logement']['image6P'])) {
        unlink("../Ressources/Images/{$_SESSION['post_logement']['image6P']}");
    }
    header("Location: remplir_formulaire.php");
?>