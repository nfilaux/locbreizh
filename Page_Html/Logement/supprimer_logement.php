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

$id_logement = $_GET["id"];
echo($id_logement);

    $stmt = $dbh->prepare(
        "DELETE from locbreizh._possede_charges_associee_logement where id_logement=$id_logement;"
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