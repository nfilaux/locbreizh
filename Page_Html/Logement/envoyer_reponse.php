<?php
    session_start();
    include('../parametre_connexion.php');
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $stmt = $dbh->prepare("INSERT into locbreizh._reponse(contenu_reponse, avis, auteur) values(
        :message,
        :avis,
        :auteur)");
    $stmt->bindParam(':message', $_POST['repAvis']);
    $stmt->bindParam(':avis',  $_POST['avis']);
    $stmt->bindParam(':auteur', $_SESSION['id']);
    $stmt->execute();
    header("Location: logement_detaille_proprio.php?logement={$_POST['logement']}#avisTitre");
?>