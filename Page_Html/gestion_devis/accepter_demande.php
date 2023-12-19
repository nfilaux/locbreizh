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

    $stmt = $dbh->prepare("SELECT id_demande from locbreizh._message_demande m where m.id_message_demande = {$_GET['message']};");
    $stmt->execute();
    $id_demande = $stmt->fetch();

    header("Location: ../devis/formulaire_devis.php?demande={$id_demande['id_demande']}")
?>