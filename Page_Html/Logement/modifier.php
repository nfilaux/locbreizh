<?php
session_start();

$_SESSION['erreurs'] = [];
$_SESSION['valeurs_complete'] = [];

$libelle = $_POST["nomP"];
$tarif = $_POST["tarif_de_baseP"];
$description = $_POST["descriptionP"];
$ville = $_POST["villeP"];
$cdp = $_POST["code_postalP"];
$id_logement = $_GET["id_logement"];
$prix_max = 999;

$_SESSION['valeurs_complete']['libelle'] = $libelle;
$_SESSION['valeurs_complete']['code_postal'] = $cdp;
$_SESSION['valeurs_complete']['ville'] = $ville;
$_SESSION['valeurs_complete']['descriptif_logement'] = $description;
$_SESSION['valeurs_complete']['tarif_base_ht'] = $tarif;

if (preg_match('/[^a-zA-Z1-9-" "\'À-ÖØ-öø-ÿ]+/', $libelle)){
    $_SESSION['erreurs']['libelle'] = 'Le libelle ne doit pas contenir de caractères spéciaux autre que des - et des \' il peut contenir des chiffres';
}

if (preg_match('/[^a-zA-Z-À-ÖØ-öø-ÿ]+/', $ville)){
    $_SESSION['erreurs']['ville'] = 'la nom de ville de doit contenir que des lettres des espaces ou tirets';
}

if ((preg_match('/^[0-9]{5}$/', $cdp))){
} else {
    $_SESSION['erreurs']['code_postal'] = 'le code postal doit être entier, ne doit pas contenir d\'espaces et ne doit pas avoir plus de 5 chiffres';
}

if ($tarif > $prix_max){
    $_SESSION['erreurs']['tarif_base_ht'] = 'le prix doit être entier et inférieur à 999 € ';
}

if (preg_match('/[^a-zA-Z1-9-" "\'À-ÖØ-öø-ÿ]+/', $description)){
    $_SESSION['erreurs']['descriptif_logement'] = 'erreur la description ne pas contenir de caractères autres que des \' ou des tirets';
}
if ($_SESSION['erreurs'] != []){
    header("location:modifier_logement.php?id_logement=" . $id_logement);
}
else {

    include('../parametre_connexion.php');
try {

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $reqadresse = $dbh->prepare("SELECT id_adresse FROM locbreizh._logement WHERE id_logement = $id_logement");
    $reqadresse->execute();
    $id_adresse = $reqadresse->fetchColumn();
    //echo $id_adresse;
    $stmt = $dbh->prepare(
        "UPDATE locbreizh._logement SET libelle_logement = '$libelle',tarif_base_ht = $tarif,descriptif_logement = '$description' WHERE id_logement = $id_logement"
    );
    $stmt->execute();
    $stmt = $dbh->prepare(
        "UPDATE locbreizh._adresse SET code_postal = $cdp , ville = '$ville' where id_adresse = $id_adresse"
    );
    $stmt->execute();
} catch (PDOException $e) {
    print "Erreur !:" . $e->getMessage() . "<br/>";
    die();
}
}
    header("Location: ../Accueil/Tableau_de_bord.php");
?>