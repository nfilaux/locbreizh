<?php 
    session_start();
    //test date
    if($_POST['dateArrivee'] > $_POST['dateDepart']){
        header("Location: demande_devis.php?animaux={$_POST['animaux']}&menage={$_POST['menage']}&nb_pers={$_POST['nb_pers']}&nb_supp={$_POST['nb_pers_supp']}&mauvais=1");
    }
    else if($_POST['dateArrivee'] < date('Y-m-d') or $_POST['dateDepart'] < date('Y-m-d')){
        header("Location: demande_devis.php?logement={$_POST['logement']}&animaux={$_POST['animaux']}&menage={$_POST['menage']}&nb_pers={$_POST['nb_pers']}&nb_supp={$_POST['nb_pers_supp']}&mauvais=2");
    }
    else{
        include('../parametre_connexion.php');
        try {
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
        

        $stmt = $dbh->prepare("INSERT INTO locbreizh._demande_devis(nb_personnes, date_arrivee, date_depart, client, logement) 
        VALUES ({$_POST['nb_pers']}, '{$_POST['dateArrivee']}', '{$_POST['dateDepart']}', {$_SESSION['id']}, {$_POST['logement']});");
        $stmt->execute();
        $id_demande = $dbh->lastInsertId();

        //cherche no attribué a la demande

        //ajout charge menage
        if(isset($_POST['menage'])){
            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._possede_charges_associee_logement where id_logement = {$_POST['logement']} and nom_charges = 'menage';");
            $stmt->execute();
            $prix_menage = $stmt->fetch();

            $stmt = $dbh->prepare("INSERT INTO locbreizh._comporte_charges_associee_demande_devis (prix_charges, num_demande_devis, nom_charges)
            VALUES ({$prix_menage['prix_charges']}, $id_demande, 'menage');");
            $stmt->execute();
        }
        // ajout charge animaux
        if(isset($_POST['animaux'])){
            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._possede_charges_associee_logement where id_logement = {$_POST['logement']} and nom_charges = 'animaux';");
            $stmt->execute();
            $prix_animaux = $stmt->fetch();

            $stmt = $dbh->prepare("INSERT INTO locbreizh._comporte_charges_associee_demande_devis (prix_charges, num_demande_devis, nom_charges)
            VALUES ({$prix_animaux['prix_charges']}, $id_demande, 'animaux');");
            $stmt->execute();
        }

        if($_POST['nb_pers_supp'] > 0){
            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._possede_charges_associee_logement where id_logement = {$_POST['logement']} and nom_charges = 'personnes_supplementaires';");
            $stmt->execute();
            $prix_supp = $stmt->fetch();

            $stmt = $dbh->prepare("INSERT INTO locbreizh._comporte_charges_associee_demande_devis (prix_charges, num_demande_devis, nom_charges, nombre)
            VALUES ({$prix_supp['prix_charges']}, $id_demande, 'personnes_supplementaires', {$_POST['nb_pers_supp']});");
            $stmt->execute();
        }

        header("Location: successful.php");
    }

?>