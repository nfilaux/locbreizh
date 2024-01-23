<?php 
    session_start();
    include('../parametre_connexion.php');
    try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }

    $stmt = $dbh->prepare("SELECT id_compte from locbreizh._compte c join locbreizh._client on c.id_compte = id_client where id_compte = {$_SESSION['id']} ;");
    $stmt->execute();
    $est_client = $stmt->fetch();

    if(isset($est_client['id_compte'])){
        $stmt = $dbh->prepare(
            "UPDATE locbreizh._devis 
            set visibleC = FALSE
            WHERE num_devis = {$_GET['id']}"
        );
        $stmt->execute();
        header("Location: gestion_des_devis_client.php");
    }
    else{
        $stmt = $dbh->prepare(
            "UPDATE locbreizh._devis 
            set visibleP = FALSE
            WHERE num_devis = {$_GET['id']}"
        );
        $stmt->execute();
        header("Location: gestion_des_devis_proprio.php");
    }

    
?>