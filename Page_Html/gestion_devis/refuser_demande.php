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
    $stmt = $dbh->prepare("UPDATE locbreizh._demande_devis set accepte = False where num_demande_devis = :num;");
    $stmt->bindParam(':num', $_POST['id_demande']);
    $stmt->execute();

    header("Location: gestion_des_devis_proprio.php");
?>