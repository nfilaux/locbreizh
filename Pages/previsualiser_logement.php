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
    if (isset($_FILES["image1P"]["name"])) {
        $_SESSION['post_logement']['image1P'] = $_FILES["image1P"]["tmp_name"];
    }
    if (isset($_FILES["image2P"]["name"])) {
        $_SESSION['post_logement']['image2P'] = $_FILES["image2P"]["tmp_name"];
    }
    if (isset($_FILES["image3P"]["name"])) {
        $_SESSION['post_logement']['image3P'] = $_FILES["image3P"]["tmp_name"];
    }
    if (isset($_FILES["image4P"]["name"])) {
        $_SESSION['post_logement']['image4P'] = $_FILES["image4P"]["tmp_name"];
    }
    if (isset($_FILES["image5P"]["name"])) {
        $_SESSION['post_logement']['image5P'] = $_FILES["image5P"]["tmp_name"];
    }
    if (isset($_FILES["image6P"]["name"])) {
        $_SESSION['post_logement']['image6P'] = $_FILES["image6P"]["tmp_name"];
    }


    print_r($_SESSION);

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
    $menage = $_POST['menageP'];
    $navette = $_POST['navetteP'];
    $linge = $_POST['lingeP'];
    $en_ligne = true;
    $id_proprietaire = $_SESSION['id_proprietaire'];
    //$laPhoto = $_FILES["image1P"];
    $charges1 = $_POST['charges1P'];
    $charges2 = $_POST['charges2P'];
    $charges3 = $_POST['charges3P'];
    $nom_image_principale = $_FILES["image1P"]["name"];
    $nom_image2 = $_FILES["image2P"]["name"];
    $nom_image3 = $_FILES["image3P"]["name"];
    $nom_image4 = $_FILES["image4P"]["name"];
    $nom_image5 = $_FILES["image5P"]["name"];
    $nom_image6 = $_FILES["image6P"]["name"];

    if (isset($_POST['balconP'])) {
        $balcon = 1;
        $_SESSION['post_logement']['balconP'] = 1;
    } else {
        $balcon = 0;
    }

    if (isset($_POST['terrasseP'])) {
        $terrasse = 1;
        $_SESSION['post_logement']['terrasseP'] = 1;
    } else {
        $terrasse = 0;
    }

    if (isset($_POST['parking_publicP'])) {
        $parking_public = 1;
        $_SESSION['post_logement']['parking_publicP'] = 1;
    } else {
        $parking_public = 0;
    }

    if (isset($_POST['parking_priveeP'])) {
        $parking_privee = 1;
        $_SESSION['post_logement']['parking_priveeP'] = 1;
    } else {
        $parking_privee = 0;
    }

    if (isset($_POST['saunaP'])) {
        $sauna = 1;
        $_SESSION['post_logement']['saunaP'] = 1;
    } else {
        $sauna = 0;
    }

    if (isset($_POST['hammamP'])) {
        $hammam = 1;
        $_SESSION['post_logement']['hammamP'] = 1;
    } else {
        $hammam = 0;
    }

    if (isset($_POST['piscineP'])) {
        $piscine = 1;
        $_SESSION['post_logement']['piscineP'] = 1;
    } else {
        $piscine = 0;
    }

    if (isset($_POST['climatisationP'])) {
        $climatisation = 1;
        $_SESSION['post_logement']['climatisationP'] = 1;
    } else {
        $climatisation = 0;
    }

    if (isset($_POST['jacuzziP'])) {
        $jacuzzi = 1;
        $_SESSION['post_logement']['jacuzziP'] = 1;
    } else {
        $jacuzzi = 0;
    }

    if (isset($_POST['televisionP'])) {
        $television = 1;
        $_SESSION['post_logement']['televisionP'] = 1;
    } else {
        $television = 0;
    }

    if (isset($_POST['wifiP'])) {
        $wifi = 1;
        $_SESSION['post_logement']['wifiP'] = 1;
    } else {
        $wifi = 0;
    }

    if (isset($_POST['lave_vaiselleP'])) {
        $lave_vaiselle = 1;
        $_SESSION['post_logement']['lave_vaiselleP'] = 1;
    } else {
        $lave_vaiselle = 0;
    }

    if (isset($_POST['lave_lingeP'])) {
        $lave_linge = 1;
        $_SESSION['post_logement']['lave_lingeP'] = 1;
    } else {
        $lave_linge = 0;
    }

    if (isset($_POST['menageP'])){
        $menage = 1;
        $_SESSION['post_logement']['menageP'] = 1;

    } else {
        $menage = 0;
    }

    if (isset($_POST['navetteP'])) {
        $navette = 1;
        $_SESSION['post_logement']['navetteP'] = 1;
    } else {
        $navette = 0;
    }

    if (isset($_POST['lingeP'])) {
        $linge = 1;
        $_SESSION['post_logement']['lingeP'] = 1;
    } else {
        $linge = 0;
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
        'menage'=> $menage,
        'navette'=> $navette,
        'linge'=> $linge,
        'charges1' => $charges1,
        'charges2' => $charges2,
        'charges3' => $charges3,
        'image1' => $nom_image_principale,
        'image2' => $nom_image2,
        'image3' => $nom_image3,
        'image4' => $nom_image4,
        'image5' => $nom_image5,
        'image6' => $nom_image6
    ];

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
        echo 'Ménage : ' . $logement_data['menage'] . '<br>';
        echo 'Navette : ' . $logement_data['navette'] . '<br>';
        echo 'Linge : ' . $logement_data['linge'] . '<br>';
        echo 'Charges 1 : ' . $logement_data['charges1'] . '<br>';
        echo 'Charges 2 : ' . $logement_data['charges2'] . '<br>';
        echo 'Charges 3 : ' . $logement_data['charges3'] . '<br>';
        echo 'Image 1 : ' . $logement_data['image1'] . '<br>';
        echo 'Image 2 : ' . $logement_data['image2'] . '<br>';
        echo 'Image 3 : ' . $logement_data['image3'] . '<br>';
        echo 'Image 4 : ' . $logement_data['image4'] . '<br>';
        echo 'Image 5 : ' . $logement_data['image5'] . '<br>';
        echo 'Image 6 : ' . $logement_data['image6'] . '<br>';
    } else {
        echo 'Aucune donnée de logement à prévisualiser.';
    }

    ?>
    <form method='POST' action='ajouter_logement.php' enctype="multipart/form-data">
        <button type='submit'>Créer le logement</button>
    </form>
</body>


</html>