<?php
    // lancement de la session
    session_start(); 
    print_r($_GET);

    // import parametre de connexion + nouvelle instance de PDO
    include('../parametre_connexion.php');
    // id fictif pour les tests
    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    //ajout facture
    $stmt = $dbh->prepare("INSERT into locbreizh._facture(num_devis, url_facture) values(:devis, :url_facture);");
    $stmt->bindParam(':devis', $_GET['devis']);
    $stmt->bindParam(':url_facture', "facture{$_GET['devis']}.pdf");
    $stmt->execute();
    $id_facture = $dbh->lastInsertId();

    // cherche le logement associé au devis
    $stmt = $dbh->prepare("SELECT id_logement, id_proprietaire from locbreizh._devis d
    join locbreizh._demande_devis dd on dd.num_demande_devis = d.num_demande_devis
    join locbreizh._logement l on l.id_logement = dd.logement
    where d.num_devis = :devis;");
    $stmt->bindParam(':devis', $_GET['devis']);
    $stmt->execute();
    $logement = $stmt->fetch();

    // change le statut du message du devis car il a été accepte
    $stmt = $dbh->prepare("UPDATE locbreizh._message_devis set accepte = TRUE where id_devis = :devis;");
    $stmt->bindParam(':devis', $_GET['devis']);
    $stmt->execute();

    // date et heure actuelle (pour message)
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $stmt = $dbh->prepare("SELECT id_conversation from locbreizh._conversation 
    where (compte1 = :id and compte2 = :id_proprio) or (compte2 = :id and compte1 = :id_proprio);");
    $stmt->bindParam(':id', $_SESSION['id']);
    $stmt->bindParam(':id_proprio', $logement['id_proprietaire']);
    $stmt->execute();
    $id_conv = $stmt->fetch();

    $stmt = $dbh->prepare("INSERT into locbreizh._message(contenu_message, date_mess, heure_mess, auteur, conversation)
    values('DEVIS ACCEPTER LA RESERVATION A ETE FAITE !', :date, :time, :id, :id_conversation);");
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':id', $_SESSION['id']);
    $stmt->bindParam(':id_conversation', $id_conv['id_conversation']);
    $stmt->execute();

    // ajout reservation
    $stmt = $dbh->prepare("INSERT into locbreizh._reservation(reservation_annulee, client, logement, facture) values(False, :id, :id_logement, :id_facture);");
    $stmt->bindParam(':id', $_SESSION['id']);
    $stmt->bindParam(':id_logement', $logement['id_logement']);
    $stmt->bindParam(':id_facture', $id_facture);
    $stmt->execute();
    
    header("Location: reussite_payement.html");
?>