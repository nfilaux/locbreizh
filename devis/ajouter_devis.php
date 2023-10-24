<?php
session_start();
$_SESSION['erreurs'] = [];
$_SESSION['valeurs_complete'] = [];
include('../parametre_connexion.php');
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$d_d = new DateTime($_POST["date_depart"]);
$d_a = new DateTime($_POST["date_arrivee"]);

$_SESSION['valeurs_complete']['nb_pers'] = $_POST['nb_pers'];
$_SESSION['valeurs_complete']['delais_accept'] = $_POST['delais_accept'];
$_SESSION['valeurs_complete']['date_val'] = $_POST['date_val'];
$_SESSION['valeurs_complete']['annulation'] = $_POST['annulation'];
$_SESSION['valeurs_complete']['tarif_loc'] = $_POST['tarif_loc'];
$_SESSION['valeurs_complete']['charges'] = $_POST['charges'];

// test pour vérifier que les données soit bien entrée au format attendu

if ($d_d < $d_a){
    //echo "y a une erreur de date";
    $_SESSION['erreurs']['valide_dates'] = "la date d'arrivee se trouve après la date de départ !";
} else {
    $_SESSION['valeurs_complete']['date_depart'] = $_POST["date_depart"];
    $_SESSION['valeurs_complete']['date_arrivee'] = $_POST["date_arrivee"];
}

if (preg_match('/[});]+/', $_POST["annulation"])){
    $_SESSION['erreurs']['cond_annul'] = "Erreur, pour des mesures de sécurité vous ne pouvez pas mettre les caractères suivant dans vos conditions d'annulation.";
} else {
    $_SESSION['valeurs_complete']['annulation'] = $_POST["date_arrivee"];
}
print_r($_SESSION['erreurs']);
if ($_SESSION['erreurs'] != []){
    header("location:devis.php?num=1");
}
else {
$prix_loc = $_POST['tarif_loc'];
$prix_charges = $_POST['charges'];
$total_HT = $prix_loc + $prix_charges;
$total_TTC = $total_HT * 1.1;
// gestion de la taxe de séjour à voir
$taxe_sejour = 120;
$total_montant_devis = $total_TTC + $taxe_sejour;
$total_plateforme_HT = $total_montant_devis*1.01;
$total_plateforme_TTC = $total_plateforme_HT * 1.2;
$date_devis = date("Y-m-d");

$reqNomClient = $dbh->prepare("SELECT pseudo FROM locbreizh._compte where id_compte = {$_SESSION['id']} ");
$reqNomClient->execute();
$pseudo = $reqNomClient->fetch();    

$reg_devis = $dbh->prepare("INSERT INTO locbreizh._devis
(pseudo_client_devis, prix_total_devis, tarif_ht_location_nuitee_devis, sous_total_ht_devis, sous_total_ttc_devis, frais_service_platforme_ht_devis, fras_service_platforme_ttc_devis, date_devis, date_validite, condition_annulation, num_demande_devis, taxe_sejour) 
values (
'{$pseudo['pseudo']}', $total_montant_devis, $prix_loc, $total_HT, $total_TTC, $total_plateforme_HT, $total_plateforme_TTC, '$date_devis', {$_POST['date_val']}, '{$_POST['annulation']}', {$_POST['id_demande']}, 1);");
$reg_devis->execute();
}
?>