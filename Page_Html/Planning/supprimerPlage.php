<?php
session_start();
include('../parametre_connexion.php');

try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $code = $dbh->prepare("DELETE FROM locbreizh._plage_ponctuelle WHERE id_plage_ponctuelle = {$_POST['id_plage_ponctuelle']};");
    $code->execute();

} catch (PDOException $e) {
    print "Erreur !:" . $e->getMessage() . "<br/>";
    die();
}

header("location: ../Accueil/Tableau_de_bord.php?popup={$_POST['nomPopUp']}&overlay={$_POST['overlayPopUp']}");