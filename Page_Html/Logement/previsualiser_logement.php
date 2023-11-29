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
</head>
<header>
    <a href="../Accueil/Tableau_de_bord.php">
        <img class="logot" src="../svg/logo.svg">
        <h2>Loc'Breizh</h2>
    </a>
        <div class="brecherche">
            <img src="../svg/filtre.svg">
            <input id="searchbar" type="text" name="search">
            <img src="../svg/loupe.svg">
        </div>

        <img src="../svg/booklet-fill 1.svg">
        <a href="../Accueil/Tableau_de_bord.php"><h4>Accéder à mon tableau de bord</h4></a>

        <div class="imghead">
            <a href="../messagerie/messagerie.php"><img src="../svg/message.svg"></a>
            <a onclick="openPopup()"><img id="pp" class="imgprofil" src="../Ressources/Images/<?php echo $photo['photo']; ?>" width="50" height="50"></a> 
        </div>
        <div id="popup" class="popup">
        <a href="">Accéder au profil</a>
        <br>
        <a href="../Compte/SeDeconnecter.php">Se déconnecter</a>
        <a onclick="closePopup()">Fermer la fenêtre</a>
    </div>
    </header>

<body>
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

//////////////////////////////////////////////////////////////////////////////////////////////


?>
       <h1 class="policetitre"> <?php echo 'Prévisualisation des données du logement'; ?> </h1>

        <div class="logpc">
                        <h3 class="logtitre"><?php echo $logement_data['accroche'];;?></h3>
                        <div class="logrowb">
                            <div class="logrowt">
                                <h3 class="policetitre"><?php echo $logement_data['nom']; ?></h3>
                                <p>pour <?php echo $logement_data['nb_personne_max'];?>  personnes</p>
                                <p>logement de <?php echo $logement_data['surface_maison'];?> m<sup>2</sup> </p>
                            </div>
                            <div class="logrowt">
                                <p class="nuit"><?php echo $logement_data['tarif_de_base'];?> €/nuit</p>
                                <!--
                                <img src="/Ressources/Images/star-fill 1.svg"><h4> echo $info['note_avis'];,0</p>
                                -->
                            </div>
                        </div>
                        <img src="<?php echo "../Ressources/Images/$nouveau_nom_image1" ?>">

                        <!-- 
                        <img src="<?php// echo $info['photo_url'];?> ">
                        <img src="<?php// echo $info['photo_url'];?> ">
                        -->
                        <div class="logrowt">  
                            <div class="logcolumn">
                                <h3 class="policetitre">Description</h3>
                                <p><?php echo $logement_data['description']; ?></p>
                                <?php /*<p>Arrivée echo $info['debut_plage_ponctuelle'] Départ echo $info['fin_plage_ponctuelle'] </p>*/ ?>
                            </div>
                        
                        </div>
                    </div>
                

                <div class="logrow">
                    <div class="logcolumn">
                        <h3 class="potitre">Services et équipements du logement</h3>
                        <?php
                            $stmt = $dbh->prepare(
                                'SELECT nb_chambre, nb_salle_bain, lave_vaisselle, wifi, piscine, sauna, hammam, climatisation, jacuzzi, television, lave_linge, parking_public, parking_privee, balcon, terrasse, jardin FROM locbreizh._logement'
                            );
                            $stmt->execute();
                            $info = $stmt->fetch();
                        ?>

                        <div class="logrow">
                            <div class="logcp">
                                <p><?php  echo $logement_data['nb_chambre'] ?> Chambres</p><?php


                            } else {
                                echo 'Aucune donnée de logement à prévisualiser.';
                            }

                                if ($logement_data['lave_vaisselle'] == true) {
                                    ?><p><?php  echo 'Cuisine équipée'; ?></p><?php
                                }

                                if ($logement_data['wifi'] == true) {
                                    ?><p><?php  echo 'Wifi inclus'; ?></p><?php
                                }

                                if ($logement_data['piscine'] == true) {
                                    ?><p><?php  echo 'Piscine incluse'; ?></p><?php
                                }

                                if ($logement_data['sauna'] == true) {
                                    ?><p><?php  echo 'Sauna inclus'; ?></p><?php
                                }

                                if ($logement_data['hammam'] == true) {
                                    ?><p><?php  echo 'Hammam inclus'; ?></p><?php
                                }

                                if ($logement_data['jacuzzi'] == true) {
                                    ?><p><?php  echo 'Jacuzzi inclus'; ?></p><?php
                                }

                                if ($logement_data['climatisation'] == true) {
                                    ?><p><?php  echo 'Climatisation incluse'; ?></p><?php
                                }?>
                            </div>
                            <div class="logcp">
                                <p><?php  echo $logement_data['nb_salle_bain'] ?> Salles de bain</p><?php
                                if ($logement_data['television'] == true) {
                                    ?><p><?php  echo 'Television inclus'; ?></p><?php
                                }

                                if ($logement_data['lave_linge'] == true) {
                                    ?><p><?php  echo 'Lave-linge inclus'; ?></p><?php
                                }

                                if ($logement_data['parking_privee'] == true) {
                                    ?><p><?php  echo 'Parking privée inclus'; ?></p><?php
                                }

                                if ($logement_data['parking_public'] == true) {
                                    ?><p><?php  echo 'Parking public inclus'; ?></p><?php
                                }

                                if ( $logement_data['balcon'] == true) {
                                    ?><p><?php  echo 'Balcon inclus'; ?></p><?php
                                }

                                if ($logement_data['terrasse'] == true) {
                                    ?><p><?php  echo 'Terrasse incluse'; ?></p><?php
                                }?>
                            </div>
                        </div>
                        <p>Surface du jardin : <?php  echo $info['jardin']; ?> m<sup>2</sup></p>
                    </div>
                    <hr class="hr">
                    <div class="logcolumn">
                        <h3 class="potitre">Calendrier</h3>
                    </div>
                </div>
        <div class="logrow" style="margin-top:2em   ;">
            <form method='POST' action='annuler_logement.php' enctype="multipart/form-data">
                <button class="btn-demlog" type='submit'>Annuler</button>
            </form>
            <form method='POST' action='ajouter_logement.php' enctype="multipart/form-data">
                <button class="btn-demlog" type='submit'>Créer le logement</button>
            </form>
        </div>
    </main>

    <footer>
            <div class="tfooter">
                <p><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
                <p><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
                <a class="margintb" href="connexion.html"><img src="../svg/instagram.svg">  <p>@LocBreizh</p></a>
                <a  class="margintb" href="connexion.html"><img src="../svg/facebook.svg">  <p>@LocBreizh</p></a>
            </div>
            <hr>  
            <div class="bfooter">
                <p>©2023 Loc’Breizh</p>
                <p style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p>Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
    </footer>
</body>

</html>

<script>
    // Ouvrir la popup
    function openPopup() {
        var popup = document.getElementById('popup');
        popup.style.display = 'block';
    }

    // Fermer la popup
    function closePopup() {
        var popup = document.getElementById('popup');
        popup.style.display = 'none';
    }

    // Ajouter des gestionnaires d'événements aux boutons
    var profilButton = document.getElementById('profilButton');
    profilButton.addEventListener('click', function() {
        alert('Accéder au profil');
        closePopup();
    });

    var deconnexionButton = document.getElementById('deconnexionButton');
    deconnexionButton.addEventListener('click', function() {
        alert('Se déconnecter');
        closePopup();
    });
</Script>
