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

function disable($id){
    global $dbh;
    $stmt = $dbh->prepare(
        "UPDATE locbreizh._logement SET en_ligne = false where id_logement = $id;"
    );
    $stmt->execute();
}

function unable($id){
    global $dbh;
    $stmt = $dbh->prepare(
        "UPDATE locbreizh._logement SET en_ligne = true where id_logement = $id;"
    );
    $stmt->execute();
}

foreach($_POST as $id_log => $changer_vers_etat){
    $code = $dbh->prepare("SELECT code_planning FROM locbreizh._planning NATURAL JOIN locbreizh._logement WHERE id_logement = :id_logement;");
    $code->bindParam(':id_logement',  $id_log);
    $code->execute();
    $variable = $code->fetch();

    $plageDispo = $dbh->prepare("SELECT COUNT(*) FROM locbreizh._plage_ponctuelle INNER JOIN locbreizh._plage_ponctuelle_disponible
    ON _plage_ponctuelle.id_plage_ponctuelle = _plage_ponctuelle_disponible.id_plage_ponctuelle WHERE code_planning = :code_planning ;");
    $plageDispo->bindParam(':code_planning', $variable['code_planning']);
    $plageDispo->execute();
    $plageDispo = $plageDispo->fetchColumn();
    if ($plageDispo != 0){
        if ($changer_vers_etat == "METTRE HORS LIGNE"){
            disable($id_log);
        } else {
            unable($id_log);
        }
    }
    header("Location: Tableau_de_bord.php");
}