<?php
    // lancement de la session
    session_start();
    // inclusion d'une instance PDO
    include('../parametre_connexion.php');
    try {
        // prend les parametres de connexion dans le fichiers importés plus haut
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    // change le statut du message
    $stmt = $dbh->prepare("UPDATE locbreizh._message_demande set accepte = False where id_message_demande = :message;");
    $stmt->bindParam(':message', $_GET['message']);
    $stmt->execute();

    // date et heure actuelle (pour message)
    $date = date('Y-m-d');
    $time = date('H:i:s');

    $stmt = $dbh->prepare("SELECT id_conversation from locbreizh._message m 
    join locbreizh._conversation c on m.conversation = c.id_conversation 
    where m.id_message = :message;");
    $stmt->bindParam(':message', $_GET['message']);
    $stmt->execute();
    $id_conv = $stmt->fetch();

    // ajoute le message type pour une demande de devis
    $stmt = $dbh->prepare("INSERT INTO locbreizh._message(contenu_message, date_mess, heure_mess, auteur, conversation) 
    VALUES ('DEMANDE REFUSEE PAR LE PROPRIETAIRE VOUS POURREZ TROUVER D’AUTRE LOGEMENT ICI : <a href=\"accueil.php\">voir catalogue</a>',
    ':date', ':time', :id, :id_conversation);");
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':id', $_SESSION['id']);
    $stmt->bindParam(':id_conversation', $id_conv['id_conversation']);
    $stmt->execute();
    header("Location: messagerie.php?conv={$id_conv['id_conversation']}");
?>