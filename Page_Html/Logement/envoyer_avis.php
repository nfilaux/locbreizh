<?php
    session_start();
    include('../parametre_connexion.php');
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $stmt = $dbh->prepare("INSERT into locbreizh._avis(contenu_avis, note_avis, auteur, logement) values(
        :message,
        :note,
        :auteur,
        :logement)
    ");
    $stmt->bindParam(':message', $_POST['contenu']);
    $stmt->bindParam(':note',  $_POST['rate']);
    $stmt->bindParam(':auteur', $_SESSION['id']);
    $stmt->bindParam(':logement',  $_POST['logement']);
    $stmt->execute();
    header("Location: logement_detaille_client.php?logement={$_POST['logement']}#avis_box");
?>