<?php
    // Début de la session
    session_start();

    // Inclusion d'une instance PDO
    include('../parametre_connexion.php');

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

    // On change le statut du devis
    $stmt = $dbh->prepare("UPDATE locbreizh._devis 
    set annule = TRUE 
    where num_devis = {$_POST['id_devis']};");
    $stmt->execute();

    header("Location: gestion_des_devis_proprio.php")
?>