<?php
    include('../parametre_connexion.php');
    //t
    try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }


    // recupère le proprio
    $stmt = $dbh->prepare("SELECT * from locbreizh._plage_ponctuelle");
        $stmt->execute();
        $reservations = $stmt->fetchAll();

    print_r($reservations);
?>