<?php
    session_start();

    // génération du token
    
    $token = bin2hex(random_bytes(20));
    
    // insertion en BDD
    
    include('../parametre_connexion.php');
    try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }

    // on regarde les elements que souhaite recevoir le proprio
    $resa = 'false';
    $demande = 'false';
    $indispo = 'false';

    if(isset($_POST['reservations_confirmees']) && $_POST['reservations_confirmees'] == 'on'){
        $resa = 'true';
    }
    if(isset($_POST['demandes_de_reservation']) && $_POST['demandes_de_reservation'] == 'on'){
        $demande = 'true';
    }
    if(isset($_POST['indisponibilites']) && $_POST['indisponibilites'] == 'on'){
        $indispo = 'true';
    }


    $stmt = $dbh->prepare("INSERT INTO locbreizh._icalendar(token, debut, fin, reservations, demandes, indisponibilites) 
    values(:token, :debut, :fin, :resa, :demande, :indispo)");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':debut', $_POST['date_debut']);
    $stmt->bindParam(':fin', $_POST['date_fin']);
    $stmt->bindParam(':resa', $resa);
    $stmt->bindParam(':demande', $demande);
    $stmt->bindParam(':indispo', $indispo);
    $stmt->execute();

    $stmt = $dbh->prepare("INSERT INTO locbreizh._proprio_possede_token values(:token, :id)");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':id', $_SESSION['id']);
    $stmt->execute();

    //creer fic token.php

    $file = "script_update/$token.php";
    $modele = './script_update/modele_script.php';
    file_put_contents($file, file_get_contents($modele));
?>