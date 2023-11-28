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
$stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = {$_SESSION['id']};");
$stmt->execute();
$photo = $stmt->fetch();


$nom_image_principale = $_FILES["image1P"]["tmp_name"];
$nom_image2 = $_FILES["image2P"]["tmp_name"];
$nom_image3 = $_FILES["image3P"]["tmp_name"];
$nom_image4 = $_FILES["image2P"]["tmp_name"];
$nom_image5 = $_FILES["image5P"]["tmp_name"];
$nom_image6 = $_FILES["image6P"]["tmp_name"];


$id_photo = 0;

function id_photo($id_photo)
{
    return time() . $id_photo;
}

$nouveau_nom_image1 = id_photo($id_photo);
$id_photo++;
move_uploaded_file($nom_image_principale, "../Ressources/Images/" . $nouveau_nom_image1);


$nouveau_nom_image2 = id_photo($id_photo);
$id_photo++;
if (isset($_SESSION['post_logement']['image2P'])) {
    move_uploaded_file($nom_image2, "../Ressources/Images/" . $nouveau_nom_image2);
} else {
    $nom_image2 = null;
}

$nouveau_nom_image3 = id_photo($id_photo);
$id_photo++;
if (isset($_SESSION['post_logement']['image3P'])) {
    move_uploaded_file($nom_image3, "../Ressources/Images/" . $nouveau_nom_image3);
} else {
    $nom_image3 = null;
}

$nouveau_nom_image4 = id_photo($id_photo);
$id_photo++;
if (isset($_SESSION['post_logement']['image4P'])) {
    move_uploaded_file($nom_image4, "../Ressources/Images/" . $nouveau_nom_image4);
} else {
    $nom_image4 = null;
}

$nouveau_nom_image5 = id_photo($id_photo);
$id_photo++;
if (isset($_SESSION['post_logement']['image5P'])) {
    move_uploaded_file($nom_image5, "../Ressources/Images/" . $nouveau_nom_image5);
} else {
    $nom_image5 = null;
}

$nouveau_nom_image6 = id_photo($id_photo);
$id_photo++;
if (isset($_SESSION['post_logement']['image6P'])) {
    move_uploaded_file($nom_image6, "../Ressources/Images/" . $nouveau_nom_image6);
} else {
    $nom_image6 = null;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévisualisez un logement</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
</head>


<body>
    <?php 
        include('../header-footer/choose_header.php');
    ?>

    <main>
        <?php
        unset($_SESSION['post_logement']);

        $_SESSION['post_logement'] = $_POST;

        $_SESSION['post_logement']['image1P'] = $nouveau_nom_image1;

        if ($_FILES["image2P"]["name"] != "") {
            $_SESSION['post_logement']['image2P'] = $nouveau_nom_image2;
        }
        if ($_FILES["image3P"]["name"] != "") {
            $_SESSION['post_logement']['image3P'] = $nouveau_nom_image3;
        }
        if ($_FILES["image4P"]["name"] != "") {
            $_SESSION['post_logement']['image4P'] = $nouveau_nom_image4;
        }
        if ($_FILES["image5P"]["name"] != "") {
            $_SESSION['post_logement']['image5P'] = $nouveau_nom_image5;
        }
        if ($_FILES["image6P"]["name"] != "") {
            $_SESSION['post_logement']['image6P'] = $nouveau_nom_image6;
        }

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
        $id_proprietaire = $_SESSION['id'];
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

        if (isset($_POST['parking_priveP'])) {
            $parking_privee = 1;
            $_SESSION['post_logement']['parking_priveP'] = 1;
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

        if (isset($_POST['lave_vaisselleP'])) {
            $lave_vaiselle = 1;
            $_SESSION['post_logement']['lave_vaisselleP'] = 1;
        } else {
            $lave_vaiselle = 0;
        }

        if (isset($_POST['lave_lingeP'])) {
            $lave_linge = 1;
            $_SESSION['post_logement']['lave_lingeP'] = 1;
        } else {
            $lave_linge = 0;
        }

        if (isset($_POST['menageP'])) {
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
            'menage' => $menage,
            'navette' => $navette,
            'linge' => $linge,
            'charges1' => $charges1,
            'charges2' => $charges2,
            'charges3' => $charges3,
            'image1' => $nouveau_nom_image1,
            'image2' => $nouveau_nom_image2,
            'image3' => $nouveau_nom_image3,
            'image4' => $nouveau_nom_image4,
            'image5' => $nouveau_nom_image5,
            'image6' => $nouveau_nom_image6
        ];

        if (isset($_SESSION['logement_data'])) {
            $logement_data = $_SESSION['logement_data'];

        ?>

            <h1> <?php echo 'Prévisualisation des données du logement'; ?> </h1>
            <p> <?php echo 'Nom du logement : ' . $logement_data['nom']; ?> </p>
            <p> <?php echo 'Ville : ' . $logement_data['ville']; ?> </p>
            <p> <?php echo 'Code postal : ' . $logement_data['code_postal']; ?> </p>
            <p> <?php echo 'Tarif de base : ' . $logement_data['tarif_de_base']; ?> </p>
            <p> <?php echo 'Phrase d\'accroche : ' . $logement_data['accroche']; ?> </p>
            <p> <?php echo 'Description : ' . $logement_data['description']; ?> </p>
            <p> <?php echo 'Nature : ' . $logement_data['nature']; ?> </p>
            <p> <?php echo 'Type : ' . $logement_data['type']; ?> </p>
            <p> <?php echo 'Nombre de chambres : ' . $logement_data['nb_chambres']; ?> </p>
            <p> <?php echo 'Nombre de lits simples : ' . $logement_data['nb_lit_simple']; ?> </p>
            <p> <?php echo 'Nombre de lits doubles : ' . $logement_data['nb_lit_double']; ?> </p>
            <p> <?php echo 'Nombre de salles de bain : ' . $logement_data['nb_sdb']; ?> </p>
            <p> <?php echo 'Surface du logement : ' . $logement_data['surface_maison']; ?> </p>
            <p> <?php echo 'Nombre de personnes maximum : ' . $logement_data['nb_personne_max']; ?> </p>
            <p> <?php echo 'Surface du jardin : ' . $logement_data['surface_jardin']; ?> </p>
            <p> <?php echo 'Taxe de séjour : ' . $logement_data['taxe_sejour']; ?> </p>
            <p> <?php echo 'Balcon : ' . $logement_data['balcon']; ?> </p>
            <p> <?php echo 'Terrasse : ' . $logement_data['terrasse']; ?> </p>
            <p> <?php echo 'Parking public : ' . $logement_data['parking_public']; ?> </p>
            <p> <?php echo 'Parking privée : ' . $logement_data['parking_privee']; ?> </p>
            <p> <?php echo 'Sauna : ' . $logement_data['sauna']; ?> </p>
            <p> <?php echo 'Hammam : ' . $logement_data['hammam']; ?> </p>
            <p> <?php echo 'Piscine : ' . $logement_data['piscine']; ?> </p>
            <p> <?php echo 'Climatisation : ' . $logement_data['climatisation']; ?> </p>
            <p> <?php echo 'Jacuzzi : ' . $logement_data['jacuzzi']; ?> </p>
            <p> <?php echo 'Télévision : ' . $logement_data['television']; ?> </p>
            <p> <?php echo 'Wifi : ' . $logement_data['wifi']; ?> </p>
            <p> <?php echo 'Lave vaisselle : ' . $logement_data['lave_vaiselle']; ?> </p>
            <p> <?php echo 'Lave linge : ' . $logement_data['lave_linge']; ?> </p>
            <p> <?php echo 'Ménage : ' . $logement_data['menage']; ?> </p>
            <p> <?php echo 'Navette : ' . $logement_data['navette']; ?> </p>
            <p> <?php echo 'Linge : ' . $logement_data['linge']; ?> </p>
            <p> <?php echo 'Charges 1 : ' . $logement_data['charges1']; ?> </p>
            <p> <?php echo 'Charges 2 : ' . $logement_data['charges2']; ?> </p>
            <p> <?php echo 'Charges 3 : ' . $logement_data['charges3']; ?> </p>
            <img src="<?php echo "../Ressources/Images/$nouveau_nom_image1" ?>">

            <?php if (isset($_SESSION['post_logement']['image2P'])) {
            ?> <img src="<?php echo "../Ressources/Images/$nouveau_nom_image2" ?>"> <?php
            }

            if (isset($_SESSION['post_logement']['image3P'])) {
                ?> <img src="<?php echo "../Ressources/Images/$nouveau_nom_image3" ?>"> <?php
            }

            if (isset($_SESSION['post_logement']['image4P'])) {
                ?> <img src="<?php echo "../Ressources/Images/$nouveau_nom_image4" ?>"> <?php
            }

            if (isset($_SESSION['post_logement']['image5P'])) {
                ?> <img src="<?php echo "../Ressources/Images/$nouveau_nom_image5" ?>"> <?php
            }

            if (isset($_SESSION['post_logement']['image6P'])) {
                ?> <img src="<?php echo "../Ressources/Images/$nouveau_nom_image6" ?>"> <?php
            }

            } else {
                echo 'Aucune donnée de logement à prévisualiser.';
            }

            ?>
        <form method='POST' action='ajouter_logement.php' enctype="multipart/form-data">
            <button type='submit'>Créer le logement</button>
        </form>
        <form method='POST' action='annuler_logement.php' enctype="multipart/form-data">
            <button type='submit'>Annuler</button>
        </form>
    </main>
    <?php 
        echo file_get_contents('../header-footer/footer.html');
    ?>
</body>

</html>
