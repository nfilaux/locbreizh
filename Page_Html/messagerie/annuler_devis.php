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

    // On change le statut du message
    $stmt = $dbh->prepare("UPDATE locbreizh._message_demande set accepte = False where id_message_demande = {$_GET['message']};");

    // date et heure actuelle (pour message)
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $stmt = $dbh->prepare("SELECT id_conversation from locbreizh._message m 
    join locbreizh._conversation c on m.conversation = c.id_conversation 
    where m.id_message = {$_GET['message']};");
    $stmt->execute();
    $id_conv = $stmt->fetch();

    $stmt = $dbh->prepare("UPDATE locbreizh._message_devis set accepte = false where id_message_devis = {$_GET['message']}; ");
    $stmt->execute();
    
    // ajoute le message type pour notifier le client 

    $stmt = $dbh->prepare("INSERT INTO locbreizh._message(contenu_message, date_mess, heure_mess, auteur, conversation) 
    VALUES ('DEVIS ANNULER PAR LE PROPRIETAIRE !',
    '$date', '$time', {$_SESSION['id']}, {$id_conv['id_conversation']});");
    $stmt->execute();

    header("Location: messagerie.php?conv={$id_conv['id_conversation']}")
?>