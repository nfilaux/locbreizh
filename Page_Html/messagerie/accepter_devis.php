<?php
    include('../parametre_connexion.php');
    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

    $stmt = $dbh->prepare("SELECT id_devis from locbreizh._message_devis m where m.id_message_devis = {$_GET['message']};");
    $stmt->execute();
    $id_devis = $stmt->fetch();

    header("Location: ../reservation/reservation.php?devis={$id_devis['id_devis']}")
?>