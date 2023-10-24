<?php
session_start();
$_SESSION['id_proprietaire'] = 1; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévisualisez un logement</title>
</head>

<body>
    <?php
    $_SESSION['post_logement'] = $_POST;
    print_r($_POST);

    $nom = $_POST['nomP'];
    $ville = $_POST['villeP'];
    $code_postal = $_POST['code_postalP'];
    $tarif_de_base = $_POST['tarif_de_baseP'];
    $accroche = $_POST['accrocheP'];
    $description = $_POST['descriptionP'];
    $nature = $_POST['natureP'];
    $type = $_POST['typeP'];
    $nb_chambres = $_POST['nb_chambresP'];
    $nb_lit_simple = $_POST['nb_lit_simpleP'];
    $nb_lit_double = $_POST['nb_lit_doubleP'];
    $nb_sdb = $_POST['nb_sdbP'];
    $surface_maison = $_POST['surface_maisonP'];
    $nb_personne_max = $_POST['nb_personne_maxP'];
    $surface_jardin = $_POST['surface_jardinP'];
    $taxe_sejour = $_POST['taxe_sejourP'];
    $en_ligne = true;
    $id_proprietaire = $_SESSION['id_proprietaire'];
    //$laPhoto = $_FILES["image1P"];
    $nom_image_principale = $_FILES["image1P"]["name"];

    move_uploaded_file($_FILES["image1P"]["tmp_name"], "../Ressources/Images/" . $nom_image_principale);
    $_SESSION['post_logement']['image1P'] = $nom_image_principale;

    if (isset($_POST['balcon'])) {
        $balcon = $_POST['balcon'];
    } else {
        $balcon = 0;
    }

    if (isset($_POST['terrasse'])) {
        $terrasse = $_POST['terrasse'];
    } else {
        $terrasse = 0;
    }

    if (isset($_POST['parking_public'])) {
        $parking_public = $_POST['parking_public'];
    } else {
        $parking_public = 0;
    }

    if (isset($_POST['parking_privee'])) {
        $parking_privee = $_POST['parking_privee'];
    } else {
        $parking_privee = 0;
    }

    if (isset($_POST['sauna'])) {
        $sauna = $_POST['sauna'];
    } else {
        $sauna = 0;
    }

    if (isset($_POST['hammam'])) {
        $hammam = $_POST['hammam'];
    } else {
        $hammam = 0;
    }

    if (isset($_POST['piscine'])) {
        $piscine = $_POST['piscine'];
    } else {
        $piscine = 0;
    }

    if (isset($_POST['climatisation'])) {
        $climatisation = $_POST['climatisation'];
    } else {
        $climatisation = 0;
    }

    if (isset($_POST['jacuzzi'])) {
        $jacuzzi = $_POST['jacuzzi'];
    } else {
        $jacuzzi = 0;
    }

    if (isset($_POST['television'])) {
        $television = $_POST['television'];
    } else {
        $television = 0;
    }

    if (isset($_POST['wifi'])) {
        $wifi = $_POST['wifi'];
    } else {
        $wifi = 0;
    }

    if (isset($_POST['lave_vaiselle'])) {
        $lave_vaiselle = $_POST['lave_vaiselle'];
    } else {
        $lave_vaiselle = 0;
    }

    if (isset($_POST['lave_linge'])) {
        $lave_linge = $_POST['lave_linge'];
    } else {
        $lave_linge = 0;
    }

    $_SESSION['logement_data'] = [
        'nom' => $nom,
        'ville' => $ville,
        'code_postal' => $code_postal,
        'prix_journalier_adulte' => $taxe_sejour,
        'tarif_de_base' => $tarif_de_base,
        'accroche' => $accroche,
        'description' => $description,
        'nature' => $nature,
        'type' => $type,
        'nb_chambres' => $nb_chambres,
        'nb_lit_simple' => $nb_lit_simple,
        'nb_lit_double' => $nb_lit_double,
        'nb_sdb' => $nb_sdb,
        'surface_maison' => $surface_maison,
        'nb_personne_max' => $nb_personne_max,
        'surface_jardin' => $surface_jardin,
        'taxe_sejour' => $taxe_sejour,
        'balcon' => $balcon,
        'terrasse' => $terrasse,
        'parking_public' => $parking_public,
        'parking_privee' => $parking_privee,
        'sauna' => $sauna,
        'hammam' => $hammam,
        'piscine' => $piscine,
        'climatisation' => $climatisation,
        'jacuzzi' => $jacuzzi,
        'television' => $television,
        'wifi' => $wifi,
        'lave_vaiselle' => $lave_vaiselle,
        'lave_linge' => $lave_linge,
        'image1' => $nom_image_principale,
    ];

    if (isset($_GET['enregistrer']) && '1' == $_GET['enregistrer']) {
        /*try {
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
    
        $stmt->execute();  */
    }

    if (isset($_SESSION['logement_data'])) {
        $logement_data = $_SESSION['logement_data'];

        echo '<h1>Prévisualisation des données du logement</h1>';
        echo 'Nom du logement : ' . $logement_data['nom'] . '<br>';
        echo 'Ville : ' . $logement_data['ville'] . '<br>';
        echo 'Code postal : ' . $logement_data['code_postal'] . '<br>';
        echo 'Tarif de base : ' . $logement_data['tarif_de_base'] . '<br>';
        echo 'Phrase d\'accroche : ' . $logement_data['accroche'] . '<br>';
        echo 'Description : ' . $logement_data['description'] . '<br>';
        echo 'Nature : ' . $logement_data['nature'] . '<br>';
        echo 'Type : ' . $logement_data['type'] . '<br>';
        echo 'Nombre de chambres : ' . $logement_data['nb_chambres'] . '<br>';
        echo 'Nombre de lits simples : ' . $logement_data['nb_lit_simple'] . '<br>';
        echo 'Nombre de lits doubles : ' . $logement_data['nb_lit_double'] . '<br>';
        echo 'Nombre de salles de bain : ' . $logement_data['nb_sdb'] . '<br>';
        echo 'Surface du logement : ' . $logement_data['surface_maison'] . '<br>';
        echo 'Nombre de personnes maximum : ' . $logement_data['nb_personne_max'] . '<br>';
        echo 'Surface du jardin : ' . $logement_data['surface_jardin'] . '<br>';
        echo 'Taxe de séjour : ' . $logement_data['taxe_sejour'] . '<br>';
        echo 'Balcon : ' . $logement_data['balcon'] . '<br>';
        echo 'Terrasse : ' . $logement_data['terrasse'] . '<br>';
        echo 'Parking public : ' . $logement_data['parking_public'] . '<br>';
        echo 'Parking privée : ' . $logement_data['parking_privee'] . '<br>';
        echo 'Sauna : ' . $logement_data['sauna'] . '<br>';
        echo 'Hammam : ' . $logement_data['hammam'] . '<br>';
        echo 'Piscine : ' . $logement_data['piscine'] . '<br>';
        echo 'Climatisation : ' . $logement_data['climatisation'] . '<br>';
        echo 'Jacuzzi : ' . $logement_data['jacuzzi'] . '<br>';
        echo 'Télévision : ' . $logement_data['television'] . '<br>';
        echo 'Wifi : ' . $logement_data['wifi'] . '<br>';
        echo 'Lave vaisselle : ' . $logement_data['lave_vaiselle'] . '<br>';
        echo 'Lave linge : ' . $logement_data['lave_linge'] . '<br>';
        echo 'Image 1 : ' . $logement_data['image1'] . '<br>';
    } else {
        echo 'Aucune donnée de logement à prévisualiser.';
    }

    ?>
    <form method='POST' action='ajouter_logement.php' enctype="multipart/form-data">
        <button type='submit'>Créer le logement</button>
    </form>
</body>


</html>