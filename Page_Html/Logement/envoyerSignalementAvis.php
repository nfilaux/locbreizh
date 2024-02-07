<?php
    session_start();
    include('../parametre_connexion.php');
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $today =date('Y-m-d');
    $stmt = $dbh->prepare("INSERT into locbreizh._signalement(motif, date_signalement) values(
        :motif,
        :date)
    ");
    $stmt->bindParam(':motif', $_POST['motif']);
    $stmt->bindParam(':date',  $today);
    $stmt->execute();
    $id = $dbh->lastInsertId();
    $stmt = $dbh->prepare("INSERT into locbreizh._signalement_avis(id_signalement, auteur, avis) values(
        :id,
        :auteur,
        :avis)
    ");
    $stmt->bindParam(':id',$id);
    $stmt->bindParam(':auteur',  $_SESSION['id']);
    $stmt->bindParam(':avis',  $_POST['avis']);
    $stmt->execute();
    $stmt = $dbh->prepare("SELECT id_compte from locbreizh._compte c join locbreizh._client on c.id_compte = id_client where id_compte = {$_SESSION['id']} ;");
    $stmt->execute();
    $est_client = $stmt->fetch();
    if(isset($est_client['id_compte'])){
        header("Location: logement_detaille_client.php?logement={$_POST['logement']}#avis_box");
    }
    else{
        header("Location: logement_detaille_proprio.php?logement={$_POST['logement']}#avis_box");
    }
?>