<?php
session_start();
include('../parametre_connexion.php');
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
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
$nom_image4 = $_FILES["image4P"]["tmp_name"];
$nom_image5 = $_FILES["image5P"]["tmp_name"];
$nom_image6 = $_FILES["image6P"]["tmp_name"];

$id_photo = 1;

function id_photo($id_photo)
{
    return time() . $id_photo;
}
$extension_img_1 = explode('/',$_FILES['image1P']['type'])[1];
$nouveau_nom_image1 = id_photo($id_photo) . '.' . $extension_img_1;
$id_photo++;
move_uploaded_file($nom_image_principale, "../Ressources/Images/" . $nouveau_nom_image1);

if ($_FILES['image2P']['name']!= '') {
    $extension_img_2 = explode('/',$_FILES['image2P']['type'])[1];
    $nouveau_nom_image2 = id_photo($id_photo) . '.' . $extension_img_2;
    $id_photo++;
    move_uploaded_file($nom_image2, "../Ressources/Images/" . $nouveau_nom_image2);
} else {
    $nom_image2 = null;
    $nouveau_nom_image2 = null;
}

if ($_FILES['image3P']['name']!= '') {
    $extension_img_3 = explode('/',$_FILES['image3P']['type'])[1];
    $nouveau_nom_image3 = id_photo($id_photo) . '.' . $extension_img_3;
    $id_photo++;
    move_uploaded_file($nom_image3, "../Ressources/Images/" . $nouveau_nom_image3);
} else {
    $nom_image3 = null;
    $nouveau_nom_image3 = null;
}

if ($_FILES['image4P']['name']!= '') {
    $extension_img_4 = explode('/',$_FILES['image4P']['type'])[1];
    $nouveau_nom_image4 = id_photo($id_photo) . '.' . $extension_img_4 ;
    $id_photo++;
    move_uploaded_file($nom_image4, "../Ressources/Images/" . $nouveau_nom_image4);
} else {
    $nom_image4 = null;
    $nouveau_nom_image4 = null;
}

if ($_FILES['image5P']['name']!= '') {
    $extension_img_5 = explode('/',$_FILES['image5P']['type'])[1];
    $nouveau_nom_image5 = id_photo($id_photo) . '.' . $extension_img_5 ;
    $id_photo++;
    move_uploaded_file($nom_image5, "../Ressources/Images/" . $nouveau_nom_image5);
} else {
    $nom_image5 = null;
    $nouveau_nom_image5 = null;
}

if ($_FILES['image6P']['name']!= '') {
    $extension_img_6 = explode('/',$_FILES['image6P']['type'])[1];
    $nouveau_nom_image6 = id_photo($id_photo) . '.' . $extension_img_6;
    $id_photo++;
    move_uploaded_file($nom_image6, "../Ressources/Images/" . $nouveau_nom_image6);
} else {
    $nom_image6 = null;
    $nouveau_nom_image6 = null;
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévisualisez un logement</title>
    <link rel="stylesheet" href="../style.css">
    <script src="./scriptCalendrier.js" defer></script>
    <script src="../scriptPopup.js"></script>
</head>


<body>
    <?php 
        include('../header-footer/choose_header.php');
    ?>

    <main class="MainTableau">
        <?php
        unset($_SESSION['post_logement']);

        $_SESSION['post_logement'] = $_POST;

        $_SESSION['post_logement']['image1P'] = $nouveau_nom_image1;
        $cpt = 1;
        if ($_FILES["image2P"]["name"] != "") {
            $_SESSION['post_logement']['image2P'] = $nouveau_nom_image2;
            $cpt++;
        }
        if ($_FILES["image3P"]["name"] != "") {
            $_SESSION['post_logement']['image3P'] = $nouveau_nom_image3;
            $cpt++;
        }
        if ($_FILES["image4P"]["name"] != "") {
            $_SESSION['post_logement']['image4P'] = $nouveau_nom_image4;
            $cpt++;
        }
        if ($_FILES["image5P"]["name"] != "") {
            $_SESSION['post_logement']['image5P'] = $nouveau_nom_image5;
            $cpt++;
        }
        if ($_FILES["image6P"]["name"] != "") {
            $_SESSION['post_logement']['image6P'] = $nouveau_nom_image6;
            $cpt++;
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
            'image6' => $nouveau_nom_image6,
        ];

        if (isset($_SESSION['logement_data'])) {
            $logement_data = $_SESSION['logement_data'];


?>
        <div class="headtabloP"> 
            <h1 class="policetitre"> Prévisualisation des données du logement </h1>
        </div>

        <div class="logpc">
                        <h3 class="logtitre"><?php echo $logement_data['accroche'];;?></h3>
                        <div class="logrowb">
                            <div class="logrowt">
                                <h3 class="policetitre"><?php echo $logement_data['nom']; ?></h3>
                            </div>
                        </div>


                        <div class="logrowb">
                            <div class="logcolumn">
                                <div class="slider-container">
                                    <div class="slider">
                                        <div class="slide">
                                                <img class="photosecondaireP" src="../Ressources/Images/<?php echo $nouveau_nom_image1;?> ">
                                            </div><?php
                                        for ($i = 1; $i < 5; $i++) {
                                            if (isset($_SESSION['post_logement']['image' .$i . 'P'] )){?>
                                                <div class="slide">
                                                    <img src="../Ressources/Images/<?php echo $_SESSION['post_logement']['image' . $i . 'P'] ;?>">
                                                </div><?php
                                            }
                                        };?>
                                    </div>

                                    <div class="controls">
                                        <button class="left"><img src="../svg/arrow-left.svg"></button>
                                        <ul></ul>
                                        <button class="right"><img src="../svg/arrow-right.svg"></button>
                                    </div>
                                </div>
                            </div>
                            <div class="logcolumn logdem">
                                <h3 class="policetitre">Description</h3>
                                <p class="description-detail"><?php echo $logement_data['description']; ?></p>
                                <?php /*<p>Arrivée echo $info['debut_plage_ponctuelle'] Départ echo $info['fin_plage_ponctuelle'] </p>*/ ?> 
                            </div>
                        </div>


                        <div class="logrowb">
                    <div class="logcolumn">
                        <h3 class="policetitres">Calendrier</h3>
                        <div class="corpsCalendrier">
                            <div class="fondP">
                                <div class="teteCalendrier">
                                    <div class="fleches flechesP">
                                        <svg id="precedent" xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14">
                                            <path fill="#274065" d="m2.828 7 4.95 4.95-1.414 1.415L0 7 6.364.637 7.778 2.05 2.828 7Z"/>
                                        </svg>
                                    </div>
                                    <p class="date_actuelle date_actuelleP"></p>
                                </div>
                                <div class="calendrier">
                                    <ul class="semaines semainesP">
                                        <li>Lun</li>
                                        <li>Mar</li>
                                        <li>Mer</li>
                                        <li>Jeu</li>
                                        <li>Ven</li>
                                        <li>Sam</li>
                                        <li>Dim</li>
                                    </ul>
                                    <ul class="jours"></ul>
                                </div>
                            </div>
                            <div class="fondP">
                                <div class="teteCalendrier">
                                    <p class="date_actuelle date_actuelleP"></p>
                                    <div class="fleches flechesP">
                                        <svg id="suivant" xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14">
                                            <path fill="#274065" d="m2.828 7 4.95 4.95-1.414 1.415L0 7 6.364.637 7.778 2.05 2.828 7Z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="calendrier">
                                    <ul class="semaines semainesP">
                                        <li>Lun</li>
                                        <li>Mar</li>
                                        <li>Mer</li>
                                        <li>Jeu</li>
                                        <li>Ven</li>
                                        <li>Sam</li>
                                        <li>Dim</li>
                                    </ul>
                                    <ul class="jours"></ul>
                                </div>
                            </div>
                    </div>
                </div>

                    <div class="logdem">
                        <div class="logrowb" id="datesPlage">
                            <p class="dateresa demresaP"></p>
                            <p class="dateresa demresaP"></p>
                        </div>
                        <form action="../Redirection/redirection_visiteur_demande_devis.php?logement=<?php echo $_GET['logement']; ?>" method="post">
                            <button class="btn-demlognoP" type="submit" disabled>Demander un devis</button>
                            <div class="logrowt">
                                <p class="nuit"><?php echo $logement_data['tarif_de_base'];?> €/nuit</p>
                            </div>
                        </form>
                    </div>
    
                </div>


        <div class="logI">
                <h3 class="policetitres">Informations du logement</h3>
                <?php
                            $stmt = $dbh->prepare(
                                'SELECT nb_chambre, nb_salle_bain, lave_vaisselle, wifi, piscine, sauna, hammam, climatisation, jacuzzi, television, lave_linge, parking_public, parking_privee, balcon, terrasse, jardin FROM locbreizh._logement'
                            );
                            $stmt->execute();
                            $info = $stmt->fetch();
                        ?>
                    <?php

                    } else {
                        echo 'Aucune donnée de logement à prévisualiser.';
                    }?>
                <div class="logrow">
                    <div class="logcp">
                        <h4 class="potitres">Equipements</h4>
                        <p><img src="../svg/tree-fill.svg"> jardin   <?php  echo $info['jardin']; ?> m<sup>2</sup></p>
                        <?php
                        if ($info['balcon'] == true) {
                            ?><p><img src="../svg/balcon.svg"><?php  echo 'Balcon'; ?></p><?php
                        }

                        if ($info['terrasse'] == true) {
                            ?><p><img src="../svg/terasse.svg"><?php  echo 'Terrasse'; ?></p><?php
                        }
                        if ($info['parking_privee'] == true) {
                            ?><p><img src="../svg/PARKING.svg"><?php  echo 'Parking privée'; ?></p><?php
                        }

                        if ($info['parking_public'] == true) {
                            ?><p><img src="../svg/PARKING.svg"><?php  echo 'Parking public'; ?></p><?php
                        }
                        if ($info['television'] == true) {
                            ?><p><img src="../svg/TELEVISION.svg"><?php  echo 'Television'; ?></p><?php
                        }
                        if ($info['wifi'] == true) {
                            ?><p><img src="../svg/WIFI.svg"><?php  echo 'Wifi'; ?></p><?php
                        }
                        if ($info['lave_linge'] == true) {
                            ?><p><img src="../svg/contrast-drop-2-fill.svg"><?php  echo 'Lave-linge'; ?></p><?php
                        }
                        if ($info['lave_vaisselle'] == true) {
                            ?><p><img src="../svg/CUISINE.svg"><?php  echo 'Cuisine équipée'; ?></p><?php
                        }
                        
                        ?>
                    </div>
                    <hr class="hr">
                    <div class="logcp">
                        <h4 class="potitres">Installations</h4>
                        <?php

                        if ($info['climatisation'] == true) {
                            ?><p><img src="../svg/windy-line.svg"><?php  echo 'Climatisation'; ?></p><?php
                        }
                        if ($info['piscine'] == true) {
                            ?><p><img src="../svg/PISCINE.svg"> <?php  echo 'Piscine'; ?></p><?php
                        }

                        if ($info['sauna'] == true) {
                            ?><p><img src="../svg/PISCINE.svg"><?php  echo 'Sauna'; ?></p><?php
                        }

                        if ($info['hammam'] == true) {
                            ?><p><img src="../svg/PISCINE.svg"><?php  echo 'Hammam'; ?></p><?php
                        }

                        if ($info['jacuzzi'] == true) {
                            ?><p><img src="../svg/PISCINE.svg"><?php  echo 'Jacuzzi'; ?></p><?php
                        }
                        ?>
                    </div>
                </div>
                <hr class="hr">
                <div class="logrow">
                    <div class="logcp">
                        <p><img src="../svg/CHAMBRE.svg"> <?php  echo $logement_data['nb_lit_simple'] ?> lit(s) simple(s)</p>
                        <p><img src="../svg/CHAMBRE.svg"><?php  echo $logement_data['nb_lit_double'] ?> lit(s) double(s)</p>
                        <p><img src="../svg/ruler.svg" width="24px" height="24px"><?php echo $logement_data['surface_maison'];?>m<sup>2<sup></p>
                    </div>
                    <div class="logcp">
                        <p><img src="../svg/CHAMBRE.svg"><?php  echo $logement_data['nb_chambres'] ?> chambre(s)</p>
                        <p><img src="../svg/SALLE_DE_BAIN.svg"><?php  echo $logement_data['nb_sdb'] ?> salle(s) de bain</p>
                        <p><img src="../svg/group.svg" width="24px" height="24px"><?php echo $logement_data['nb_personne_max'];?> personnes  </p>
                    </div>
                </div>
        </div>
        <hr class="hrP">
        
        <div class="logcarte">
            <h3 class="policetitre">Localisation</h3>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1364671.57561899!2d-4.397375693978974!3d48.08372166501683!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4811ca61ae7e8eaf%3A0x10ca5cd36df24b0!2sBretagne!5e0!3m2!1sfr!2sfr!4v1702909132704!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php

            $stmt->execute();
            $info = $stmt->fetch();
            ?>
            <p><?php echo 'Adresse : ' . $ville . ' ( ' . $code_postal . ' )' ?></p>   
            
        </div>
        
        <div class="logrow" style="margin-top:2em;">
            <form method='POST' action='annuler_logement.php' enctype="multipart/form-data">
                <button class="btn-desactive" type='submit'>Annuler</button>
            </form>
            <form method='POST' action='ajouter_logement.php' enctype="multipart/form-data">
                <button class="btn-demlogP" type='submit'>Créer le logement</button>
            </form>
        </div>
    </main>


    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>

</html>
