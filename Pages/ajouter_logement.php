<?php
session_start();

$_SESSION['id_proprietaire'] = 1;
$nom = $_SESSION['post_logement']['nomP'];
$ville = $_SESSION['post_logement']['villeP'];
$code_postal = $_SESSION['post_logement']['code_postalP'];
$tarif_de_base = $_SESSION['post_logement']['tarif_de_baseP'];
$accroche = $_SESSION['post_logement']['accrocheP'];
$description = $_SESSION['post_logement']['descriptionP'];
$nature = $_SESSION['post_logement']['natureP'];
$type = $_SESSION['post_logement']['typeP'];
$nb_chambres = $_SESSION['post_logement']['nb_chambresP'];
$nb_lit_simple = $_SESSION['post_logement']['nb_lit_simpleP'];
$nb_lit_double = $_SESSION['post_logement']['nb_lit_doubleP'];
$nb_sdb = $_SESSION['post_logement']['nb_sdbP'];
$surface_maison = $_SESSION['post_logement']['surface_maisonP'];
$nb_personne_max = $_SESSION['post_logement']['nb_personne_maxP'];
$surface_jardin = $_SESSION['post_logement']['surface_jardinP'];
$taxe_sejour = $_SESSION['post_logement']['taxe_sejourP'];
$en_ligne = true;
$id_proprietaire = $_SESSION['id_proprietaire'];
//$laPhoto = $_FILES["image1P"];
$nom_image_principale = $_SESSION['post_logement']['image1P'];

if (isset($_SESSION['post_logement']['balcon'])) {
    $balcon = $_SESSION['post_logement']['balcon'];
} else {
    $balcon = 0;
}

if (isset($_SESSION['post_logement']['terrasse'])) {
    $terrasse = $_SESSION['post_logement']['terrasse'];
} else {
    $terrasse = 0;
}

if (isset($_SESSION['post_logement']['parking_public'])) {
    $parking_public = $_SESSION['post_logement']['parking_public'];
} else {
    $parking_public = 0;
}

if (isset($_SESSION['post_logement']['parking_privee'])) {
    $parking_privee = $_SESSION['post_logement']['parking_privee'];
} else {
    $parking_privee = 0;
}

if (isset($_SESSION['post_logement']['sauna'])) {
    $sauna = $_SESSION['post_logement']['sauna'];
} else {
    $sauna = 0;
}

if (isset($_SESSION['post_logement']['hammam'])) {
    $hammam = $_SESSION['post_logement']['hammam'];
} else {
    $hammam = 0;
}

if (isset($_SESSION['post_logement']['piscine'])) {
    $piscine = $_SESSION['post_logement']['piscine'];
} else {
    $piscine = 0;
}

if (isset($_SESSION['post_logement']['climatisation'])) {
    $climatisation = $_SESSION['post_logement']['climatisation'];
} else {
    $climatisation = 0;
}

if (isset($_SESSION['post_logement']['jacuzzi'])) {
    $jacuzzi = $_SESSION['post_logement']['jacuzzi'];
} else {
    $jacuzzi = 0;
}

if (isset($_SESSION['post_logement']['television'])) {
    $television = $_SESSION['post_logement']['television'];
} else {
    $television = 0;
}

if (isset($_SESSION['post_logement']['wifi'])) {
    $wifi = $_SESSION['post_logement']['wifi'];
} else {
    $wifi = 0;
}

if (isset($_SESSION['post_logement']['lave_vaiselle'])) {
    $lave_vaiselle = $_SESSION['post_logement']['lave_vaiselle'];
} else {
    $lave_vaiselle = 0;
}

if (isset($_SESSION['post_logement']['lave_linge'])) {
    $lave_linge = $_SESSION['post_logement']['lave_linge'];
} else {
    $lave_linge = 0;
}

try {
    include('../Connexion/page_connexion.php');

    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    print "Erreur !:" . $e->getMessage() . "<br/>";
    die();
}

$stmt = $dbh->prepare(
    "INSERT INTO locbreizh._taxe_sejour (prix_journalier_adulte)
        VALUES (:prix_journalier_adulte)"
);

$stmt->bindParam(':prix_journalier_adulte', $taxe_sejour);
$stmt->execute();

$id_taxe_sejour = $dbh->lastInsertId();

$stmt = $dbh->prepare(
    "INSERT INTO locbreizh._adresse ( ville, code_postal)
        VALUES (:ville, :code_postal)"
);

$stmt->bindParam(':ville', $ville);
$stmt->bindParam(':code_postal', $code_postal);
$stmt->execute();

$id_adresse = $dbh->lastInsertId();

$stmt = $dbh->prepare(
    "INSERT INTO locbreizh._photo (url_photo)
        VALUES (:image1)"
);

$stmt->bindParam(':image1', $nom_image_principale);
$stmt->execute();

$stmt = $dbh->prepare(
    "INSERT INTO locbreizh._logement (libelle_logement, tarif_base_HT, accroche_logement, descriptif_logement, nature_logement, type_logement, nb_chambre, lit_simple, lit_double, nb_salle_bain, surface_logement, nb_personnes_logement, jardin, balcon, terrasse, parking_public, parking_privee, sauna, hammam, piscine, climatisation, jacuzzi, television, wifi, lave_vaiselle, lave_linge, photo_principale, taxe_sejour, en_ligne, id_proprietaire, id_adresse)
        VALUES (:libelle_logement, :tarif_de_base, :accroche, :description, :nature, :type, :nb_chambres, :nb_lit_simple, :nb_lit_double, :nb_sdb, :surface_maison, :nb_personne_max, :surface_jardin, :balcon, :terrasse, :parking_public, :parking_privee, :sauna, :hammam, :piscine, :climatisation, :jacuzzi, :television, :wifi, :lave_vaiselle, :lave_linge, :image1, :id_taxe_sejour, :en_ligne, :id_proprietaire, :id_adresse)"
);

$stmt->bindParam(':id_taxe_sejour', $id_taxe_sejour);
$stmt->bindParam(':libelle_logement', $nom);
$stmt->bindParam(':tarif_de_base', $tarif_de_base);
$stmt->bindParam(':accroche', $accroche);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':nature', $nature);
$stmt->bindParam(':type', $type);
$stmt->bindParam(':nb_chambres', $nb_chambres);
$stmt->bindParam(':nb_lit_simple', $nb_lit_simple);
$stmt->bindParam(':nb_lit_double', $nb_lit_double);
$stmt->bindParam(':nb_sdb', $nb_sdb);
$stmt->bindParam(':surface_maison', $surface_maison);
$stmt->bindParam(':nb_personne_max', $nb_personne_max);
$stmt->bindParam(':surface_jardin', $surface_jardin);
$stmt->bindParam(':balcon', $balcon);
$stmt->bindParam(':terrasse', $terrasse);
$stmt->bindParam(':parking_public', $parking_public);
$stmt->bindParam(':parking_privee', $parking_privee);
$stmt->bindParam(':sauna', $sauna);
$stmt->bindParam(':hammam', $hammam);
$stmt->bindParam(':piscine', $piscine);
$stmt->bindParam(':climatisation', $climatisation);
$stmt->bindParam(':jacuzzi', $jacuzzi);
$stmt->bindParam(':television', $television);
$stmt->bindParam(':wifi', $wifi);
$stmt->bindParam(':lave_vaiselle', $lave_vaiselle);
$stmt->bindParam(':lave_linge', $lave_linge);
$stmt->bindParam(':image1', $nom_image_principale);
$stmt->bindParam(':en_ligne', $en_ligne);
$stmt->bindParam(':id_proprietaire', $id_proprietaire);
$stmt->bindParam(':id_adresse', $id_adresse);

$stmt->execute();
