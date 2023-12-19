<?php
try {
        include('../parametre_connexion.php');

        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }
    if(isset($_GET["id"])==1){
        $id_logement = $_GET["id"];
        //echo($id_logement);

        $stmt = $dbh->prepare(
            "SELECT count(*) FROM locbreizh._reservation where logement=$id_logement;"
        );
        $stmt->execute();
        $reservations_logement = $stmt->fetchColumn();

        // ici cs représente le cas de click sur le bouton qui va être traduit par des popups en js
        if ($reservations_logement != 0){
            // au moins une réservation est liée avec le logement
            $cs = 1;
        } else {
            // aucune réservation n'est liée au logement
            $cs = 2;
        }
    } else {
        $id_logement = $_GET["idc"];
        $cs = 3;

        //on supprime les photos secondaires du logement, là photo principale se trouvant dans la classe logement

        $stmt = $dbh->prepare(
            "DELETE from locbreizh._photos_secondaires where logement=$id_logement;"
        );
        $stmt->execute();
        
        //on supprimer les charges liées au logement 

        $stmt = $dbh->prepare(
            "DELETE from locbreizh._possede_charges_associee_logement where id_logement=$id_logement;"
        );
        $stmt->execute();

        //on supprime les services qui étaient compris dans le logement

        $stmt = $dbh->prepare(
            "DELETE from locbreizh._services_compris where logement=$id_logement;"
        );
        $stmt->execute();

        //enfin on supprime le logement 

        $stmt = $dbh->prepare(
            "DELETE from locbreizh._logement where id_logement=$id_logement;"
        );
        $stmt->execute();
    }

    header("Location: ../Accueil/Tableau_de_bord.php?cs=$cs");