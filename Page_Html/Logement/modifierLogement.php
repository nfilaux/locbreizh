<?php
    session_start();
<<<<<<< HEAD
    ?>
    <style> #erreur {color: red; font-size : bold}</style>
    <?php
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
    $id_logement = $_GET['id_logement'];
    $erreur = $_SESSION["erreurs"];
    $reqinfosLogement = $dbh->prepare("SELECT libelle_logement,tarif_base_ht,accroche_logement,descriptif_logement,nature_logement,type_logement,surface_logement,en_ligne,nb_chambre,nb_personnes_logement,lit_simple,lit_double,nb_salle_bain,jardin,balcon,terrasse,parking_public,parking_privee,sauna,hammam,piscine,climatisation,jacuzzi,television,wifi,lave_linge,lave_vaisselle,code_planning,id_proprietaire,id_adresse,photo_principale,taxe_sejour FROM locbreizh._logement WHERE id_logement = $id_logement");
    $reqinfosLogement->execute();
    $res = $reqinfosLogement->fetch();

    $id_ad = $res["id_adresse"];
    $taxe = $res["taxe_sejour"];

    $r_adresse = $dbh->prepare("SELECT ville, code_postal FROM locbreizh._adresse WHERE id_adresse = $id_ad");
    $r_adresse->execute();
    $adresse = $r_adresse->fetch();

    $r_taxe = $dbh->prepare("SELECT prix_journalier_adulte FROM locbreizh._taxe_sejour WHERE id_taxe = $taxe");
    $r_taxe->execute();
    $taxe = $r_taxe->fetchColumn();
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifier logement</title>
        <script src="../scriptPopup.js"></script>
        <title>Modifier un logement</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <body>
        <?php 
            include('../header-footer/choose_header.php');
        ?>

    
        <div class="headconn">
            <a href="../Accueil/Tableau_de_bord.php"><img src='../svg/flecheRetour.svg'></a>
            <h1>La fiche de votre logement</h1>
        </div>

            <form method='POST' action='modifier.php?id_logement=<?php echo $id_logement ?>' enctype="multipart/form-data">
                <div class="logrow">  
                    <div class="logcolumn">     
                        <div class="logpc">  
                            <label for='nom'>Libellé logement</label>
                            <input class="lognom" id='nom' type='text' name='nomP' value="<?php if ($erreur != []){if (!isset($erreur['libelle'])){echo $_SESSION['valeurs_complete']['libelle'];}} else { echo $res["libelle_logement"];} ?>" required>
                            <?php
                            if (isset($erreur['libelle'])){
                                echo '<p id="erreur">' . $erreur['libelle'] .  '</p>';
                            }
                            ?>
                        </div>
                        <div class="logrowb"> 
                            <div class="log3vct">
                                <label for='ville'>Ville</label>
                                <input class="logvct" id='ville' type='text' name='villeP' value="<?php if ($erreur != []){if (!isset($erreur['ville'])){echo $_SESSION['valeurs_complete']['ville'];}} else { echo $adresse['ville'];} ?>" required>
                                <?php
                                if (isset($erreur['ville'])){
                                    echo '<p id="erreur">' . $erreur['ville'] .  '</p>';
                                }
                                ?>
                            </div>
                            <div class="log3vct">
                                <label for='code_postal'>Code postal</label>
                                <input class="logvct" id='code_postal' type='text' name='code_postalP' placeholder='Code postal' value="<?php if ($erreur != []){if (!isset($erreur['code_postal'])){echo $_SESSION['valeurs_complete']['code_postal'];}} else { echo $adresse["code_postal"];} ?>"required>
                                <?php
                                if (isset($erreur['code_postal'])){
                                    echo '<p id="erreur">' . $erreur['code_postal'] .  '</p>';
                                }
                                ?>
                            </div>
                            <div class="log3vct">
                                <label for='tarif_de_base'>Tarif de base (en €)</label>
                                <input class="logvct" id='tarif_de_base' type='number' name='tarif_de_baseP' min='0' max='2500' value="<?php if (!isset($erreur['tarif_base_ht'])){echo $res["tarif_base_ht"];} ?>" required>
                                <?php
                                if (isset($erreur['tarif_base_ht'])){
                                    echo '<p id="erreur">' . $erreur['tarif_base_ht'] .  '</p>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="logpc">
                            <label for='phrase_daccroche' >Phrase d'accroche</label>
                            <input disabled class="logPA" id='accroche' type='text' name='accrocheP' placeholder="Phrase d'accroche" value="<?php echo $res["accroche_logement"]; ?>"required>
                        </div>
                        <div class="logrowb"> 
                                <div class="log2vct">
                                    <label for='nature'>Nature</label>
                                    <select class="logselect" id='nature' name='natureP' placeholder='Nature' disabled required>
                                        <option value='1'>Maison</option>
                                        <option value='2'>Appartement</option>
                                        <option value='3'>Manoir</option>
                                        <option value='4'>Château</option>
                                    </select>
                                </div>
                                <div class="log2vct">
                                    <label for='type'>Type</label>
                                    <select class="logselect" id='type' name='typeP' aria-placeholder="Type" disabled required>
                                        <option value='1'>T1</option>
                                        <option value='2'>T2</option>
                                        <option value='3'>T3</option>
                                        <option value='4'>T4</option>
                                        <option value='5'>T5</option>
                                        <option value='6'>T6</option>
                                        <option value='7'>T7</option>
                                        <option value='8'>T8</option>
                                        <option value='9'>T9</option>
                                        <option value='10'>T10</option>
                                    </select>
                                </div>
                        </div>
        
                        <div class="logrowb"> 
                            <div class="log4vct">
                                <label for='nb_chamnbres' >Nombre de chambres</label>
                                <input class="logvct" id='nb_chambres' disabled type='number' name='nb_chambresP' min='0' max='15' step='1' value=<?php echo $res["nb_chambre"] ?> required>
                            </div>
                            <div class="log4vct">
                                <label for='nb_lit_simple'>Nombre de lits simples</label>
                                <input class="logvct" id='nb_lit_simple' disabled type='number' name='nb_lit_simpleP' min='0' max='15' step='1' value=<?php echo $res["lit_simple"] ?> required>
                            </div>
                            <div class="log4vct">
                                <label for='nb_lit_double'>Nombre de lits doubles</label>
                                <input class="logvct" id='nb_lit_double' disabled type='number' name='nb_lit_doubleP' min='0' max='15' step='1' value=<?php echo $res["lit_double"] ?> required>
                            </div>
                            <div class="log4vct">
                                <label for='nb_sdb'>Nombre de salles de bain</label>
                                <input class="logvct" id='nb_sdb' disabled type='number' name='nb_sdbP' min='0' max='10' step='1' value=<?php echo $res["nb_salle_bain"] ?> required>
                            </div>
                        </div>

                        <div class="logrowb"> 
                            <div class="log3vct">
                                <label for='surface_maison'>Surface (en m²)</label>
                                <input class="logvct" id='surface_maison' disabled type='number' name='surface_maisonP' min='0' max='300' step='1' value=<?php echo $res["surface_logement"] ?> required>
                            </div>
                            <div class="log3vct">
                                <label for='nb_personne_max'>Nombre de personnes maximum</label>
                                <input class="logvct" id='nb_personne_max' disabled type='number' name='nb_personne_maxP' min='1' max='15' step='1' value=<?php echo $res["nb_personnes_logement"] ?> required>
                            </div>
                            <div class="log3vct">
                                <label for='surface_jardin'>Surface du jardin (en m2)</label>
                                <input class="logvct" id='surface_jardin' disabled type='number' name='surface_jardinP' min='0' max='50000' step='1' value=<?php echo $res["jardin"] ?> required>
                            </div>
                        </div>
                    </div>
                    <div class="logcolumn">
                        <div class="description">
                            <label for='description'>Description</label>
                            <textarea class="logPA" id='description' name='descriptionP' placeholder='Description' required><?php if (!isset($erreur['descriptif_logement'])){echo $res["descriptif_logement"];} ?></textarea>
                            <?php
                            if (isset($erreur['descriptif_logement'])){
                                echo '<p id="erreur">' . $erreur['descriptif_logement'] .  '</p>';
                            }
                            ?>
                        </div>
                        <fieldset>
                            <div class="logrow">
                                <div class="logcolumn">
                                    <label for='equipement'>Equipements</label>
                                        <div class="logrow">
                                                <div class="logcolumn">
                                                    <div class="logcheckbox">
                                                        <input disabled id='equipement' type='checkbox' name='balcon' value='1' <?php if($res["balcon"] == true){ ?> checked <?php ;}?>>Balcon
                                                    </div>
                                                    <div class="logcheckbox">
                                                        <input  disabled id='equipement' type='checkbox' name='terrasseP' value='2' <?php if($res["terrasse"] == true){ ?> checked <?php ;}?>>Terrasse
                                                    </div>
                                                    <div class="logcheckbox">
                                                        <input disabled id='equipement' type='checkbox' name='piscineP' value='3' <?php if($res["piscine"] == true){ ?> checked <?php ;}?>>Piscine
                                                    </div>
                                                    <div class="logcheckbox">
                                                        <input disabled id='equipement' type='checkbox' name='jacuzziP' value='4' <?php if($res["jacuzzi"] == true){ ?> checked <?php ;}?>>Jacuzzi
                                                    </div>
                                                    <div class="logcheckbox">
                                                        <input disabled id='equipement' type='checkbox' name='saunaP' value='5' <?php if($res["sauna"] == true){ ?> checked <?php ;}?>>Sauna
                                                    </div>
                                                    <div class="logcheckbox">
                                                        <input disabled id='equipement' type='checkbox' name='hammamP' value='6' <?php if($res["hammam"] == true){ ?> checked <?php ;}?>>Hammam
                                                    </div>
                                                </div>
                                                <div class="logcolumn">
                                                    <div class="logcheckbox">
                                                        <input disabled id='equipement' type='checkbox' name='parking_publicP' value='7' <?php if($res["parking_public"] == true){ ?> checked <?php ;}?>>Parking public
                                                    </div>
                                                    <div class="logcheckbox">
                                                        <input disabled id='equipement' type='checkbox' name='parking_priveP' value='8' <?php if($res["parking_privee"] == true){ ?> checked <?php ;}?>>Parking privé
                                                    </div>
                                                    <div class="logcheckbox"> 
                                                        <input disabled id='equipement' type='checkbox' name='televisionP' value='9' <?php if($res["television"] == true){ ?> checked <?php ;}?>>Télévision
                                                    </div>
                                                    <div class="logcheckbox"> 
                                                        <input disabled id='equipement' type='checkbox' name='wifiP' value='10' <?php if($res["wifi"] == true){ ?> checked <?php ;}?>>Wifi
                                                    </div>
                                                    <div class="logcheckbox">
                                                        <input disabled id='equipement' type='checkbox' name='lave_vaisselleP' value='11' <?php if($res["lave_vaisselle"] == true){ ?> checked <?php ;}?>>Lave vaisselle
                                                    </div>
                                                    <div class="logcheckbox">
                                                        <input disabled id='equipement' type='checkbox' name='lave_lingeP' value='12' <?php if($res["lave_linge"] == true){ ?> checked <?php ;}?>>Lave linge
                                                    </div>
                                                </div>
                                            </div>
                                </div>
                                            <div class="logcolumn">
                                                <label for='service'>Service</label>
                                                <div class="logcheckbox">
                                                    <input disabled id='service' type='checkbox' name='menageP' placeholder='Service'>Ménage
                                                </div>
                                                <div class="logcheckbox">
                                                    <input disabled id='service' type='checkbox' name='navetteP' placeholder='Service'>Navette/Taxi
                                                </div>
                                                <div class="logcheckbox">    
                                                    <input disabled id='service' type='checkbox' name='lingeP' placeholder='Service'>Linge
                                                </div> 
                                            </div>   
                                        </div>   
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div class="logpc">
                    <h4 class="titreAL">Prix des charges</h4>
                    <div class="logrow">
                        <div class="logpc">
                            <label for='taxe_sejour'>Taxe de séjour</label>
                            <input class="logvct" disabled id='taxe_sejour' type='number' name='taxe_sejourP' min='0' max='25' step='1' value=<?php echo $taxe; ?> required>
                        </div>
                    </div>
                </div>  
                        <div class="logpc">
                            <h4 class="titreAL">Images logement</h4>
                            <div class="logrow">
                                <div class="logpc">
                                    <label for='image1'>Image 1</label>
                                    <input id='image1' type='file' name='image1P' accept='image/png, image/jpeg'>

                                    <label for='image2'>Image 2</label>
                                    <input id='image2' type='file' name='image2P' accept='image/png, image/jpeg'>

                                    <label for='image3'>Image 3</label>
                                    <input id='image3' type='file' name='image3P' accept='image/png, image/jpeg'>
                                </div>
                                <div class="logpc">
                                    <label for='image4'>Image 4</label>
                                    <input id='image4' type='file' name='image4P' accept='image/png, image/jpeg'>

                                    <label for='image5'>Image 5</label>
                                    <input id='image5' type='file' name='image5P' accept='image/png, image/jpeg'>

                                    <label for='image6'>Image 6</label>
                                    <input id='image6' type='file' name='image6P' accept='image/png, image/jpeg'>
                                </div>
                            </div>
                            <div class="btn-modif-logement">
                            <button class="btn-accueil" name='previsualiser' type='submit'>Modifier</button></div>
                        </div>
    
            </form>
=======

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
    $id_proprietaire = $_SESSION['id'];
    $nom_image_principale = $_SESSION['post_logement']['image1P'];
    /*$nom_image2 = $_SESSION['post_logement']['image2P'];
    $nom_image3 = $_SESSION['post_logement']['image3P'];
    $nom_image4 = $_SESSION['post_logement']['image4P'];
    $nom_image5 = $_SESSION['post_logement']['image5P'];
    $nom_image6 = $_SESSION['post_logement']['image6P'];*/
    $charges1 = $_SESSION['post_logement']['charges1P'];
    $charges2 = $_SESSION['post_logement']['charges2P'];
    $charges3 = $_SESSION['post_logement']['charges3P'];

    if (isset($_SESSION['post_logement']['balconP'])) {
        $balcon = $_SESSION['post_logement']['balconP'];
    } else {
        $balcon = 0;
    }

    if (isset($_SESSION['post_logement']['terrasseP'])) {
        $terrasse = $_SESSION['post_logement']['terrasseP'];
    } else {
        $terrasse = 0;
    }

    if (isset($_SESSION['post_logement']['parking_publicP'])) {
        $parking_public = $_SESSION['post_logement']['parking_publicP'];
    } else {
        $parking_public = 0;
    }

    if (isset($_SESSION['post_logement']['parking_priveP'])) {
        $parking_privee = $_SESSION['post_logement']['parking_priveP'];
    } else {
        $parking_privee = 0;
    }

    if (isset($_SESSION['post_logement']['saunaP'])) {
        $sauna = $_SESSION['post_logement']['saunaP'];
    } else {
        $sauna = 0;
    }

    if (isset($_SESSION['post_logement']['hammamP'])) {
        $hammam = $_SESSION['post_logement']['hammamP'];
    } else {
        $hammam = 0;
    }

    if (isset($_SESSION['post_logement']['piscineP'])) {
        $piscine = $_SESSION['post_logement']['piscineP'];
    } else {
        $piscine = 0;
    }

    if (isset($_SESSION['post_logement']['climatisationP'])) {
        $climatisation = $_SESSION['post_logement']['climatisationP'];
    } else {
        $climatisation = 0;
    }

    if (isset($_SESSION['post_logement']['jacuzziP'])) {
        $jacuzzi = $_SESSION['post_logement']['jacuzziP'];
    } else {
        $jacuzzi = 0;
    }

    if (isset($_SESSION['post_logement']['televisionP'])) {
        $television = $_SESSION['post_logement']['televisionP'];
    } else {
        $television = 0;
    }

    if (isset($_SESSION['post_logement']['wifiP'])) {
        $wifi = $_SESSION['post_logement']['wifiP'];
    } else {
        $wifi = 0;
    }

    if (isset($_SESSION['post_logement']['lave_vaiselleP'])) {
        $lave_vaiselle = $_SESSION['post_logement']['lave_vaiselleP'];
    } else {
        $lave_vaiselle = 0;
    }

    if (isset($_SESSION['post_logement']['lave_lingeP'])) {
        $lave_linge = $_SESSION['post_logement']['lave_lingeP'];
    } else {
        $lave_linge = 0;
    }

    if (isset($_SESSION['post_logement']['menageP'])) {
        $menage = $_SESSION['post_logement']['menageP'];
    } else {
        $menage = 0;
    }

    if (isset($_SESSION['post_logement']['lingeP'])) {
        $linge = $_SESSION['post_logement']['lingeP'];
    } else {
        $linge = 0;
    }

    if (isset($_SESSION['post_logement']['lave_vaisselleP'])) {
        $lave_vaiselle = $_SESSION['post_logement']['lave_vaisselleP'];
    } else {
        $lave_vaiselle = 0;
    }

    if (isset($_SESSION['post_logement']['menageP'])) {
        $menage_service = "menage";
    } else {
        $menage_service = "";
    }

    if (isset($_SESSION['post_logement']['navetteP'])) {
        $navette = "navette";
    } else {
        $navette = "";
    }

    if (isset($_SESSION['post_logement']['lingeP'])) {
        $linge = "linge";
    } else {
        $linge = "";
    }

    try {
        include('../parametre_connexion.php');

        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }

    //echo $dbh->lastInsertId() . "+" . $linge;

    $stmt = $dbh->prepare(
        "INSERT INTO locbreizh._taxe_sejour (prix_journalier_adulte)
            VALUES (:prix_journalier_adulte)"
    );

    $stmt->bindParam(':prix_journalier_adulte', $taxe_sejour);
    $stmt->execute();

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



    $delai = 1;
    $planning->bindParam(':tarif_journee', $tarif_de_base);
    $planning->bindParam(':delai_depart_arrivee', $delai);
    $planning->bindParam(':disponible', $en_ligne);

    $planning->execute();

    $code_planning = $dbh->lastInsertId();

    $stmt = $dbh->prepare(
        "INSERT INTO locbreizh._logement (libelle_logement, tarif_base_HT, accroche_logement, descriptif_logement, nature_logement, type_logement, nb_chambre, lit_simple, lit_double, nb_salle_bain, surface_logement, nb_personnes_logement, jardin, balcon, terrasse, parking_public, parking_privee, sauna, hammam, piscine, climatisation, jacuzzi, television, wifi, lave_vaisselle, code_planning,lave_linge, photo_principale, taxe_sejour, en_ligne, id_proprietaire, id_adresse)
            VALUES (:libelle_logement, :tarif_de_base, :accroche, :description, :nature, :type, :nb_chambres, :nb_lit_simple, :nb_lit_double, :nb_sdb, :surface_maison, :nb_personne_max, :surface_jardin, :balcon, :terrasse, :parking_public, :parking_privee, :sauna, :hammam, :piscine, :climatisation, :jacuzzi, :television, :wifi, :lave_vaisselle, :code_planning, :lave_linge, :image1, :id_taxe_sejour, :en_ligne, :id_proprietaire, :id_adresse)"
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
    $stmt->bindParam(':lave_vaisselle', $lave_vaiselle);
    $stmt->bindParam(':code_planning', $code_planning);
    $stmt->bindParam(':lave_linge', $lave_linge);
    $stmt->bindParam(':image1', $nom_image_principale);
    $stmt->bindParam(':en_ligne', $en_ligne);
    $stmt->bindParam(':id_proprietaire', $id_proprietaire);
    $stmt->bindParam(':id_adresse', $id_adresse);
    $stmt->execute();
    $id_logement = $dbh->lastInsertId();

    if ($linge != ""){
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._services_compris (logement,nom_service)
                VALUES (:logement,:nom_service)"
        );
    
        $stmt->bindParam(':logement',$id_logement );
        $stmt->bindParam(':nom_service', $linge);
        $stmt->execute();
    }

    if ($navette != ""){
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._services_compris (logement,nom_service)
                VALUES (:logement,:nom_service)"
        );
    
        $stmt->bindParam(':logement',$id_logement );
        $stmt->bindParam(':nom_service', $navette);
        $stmt->execute();
    }

    if ($menage != ""){
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._services_compris (logement,nom_service)
                VALUES (:logement,:nom_service)"
        );
>>>>>>> origin/youen
    
        $stmt->bindParam(':logement',$id_logement );
        $stmt->bindParam(':nom_service', $menage_service);
        $stmt->execute();
    }

    $stmt = $dbh->prepare("INSERT INTO locbreizh._possede_charges_associee_logement
    values($charges1, $id_logement, 'menage');");
    $stmt->execute();

    $stmt = $dbh->prepare("INSERT INTO locbreizh._possede_charges_associee_logement
    values($charges2, $id_logement, 'animaux');");
    $stmt->execute();

    $stmt = $dbh->prepare("INSERT INTO locbreizh._possede_charges_associee_logement
    values($charges3, $id_logement, 'personnes_supplementaires');");
    $stmt->execute();

    if (isset($_SESSION['post_logement']['image2P'])) {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image2)'
        );
        $stmt->bindParam(':image2', $nom_image2);
        $stmt->execute();
    }

    if (isset($_SESSION['post_logement']['image3P'])) {
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image3)"
        );
        $stmt->bindParam(':image3', $nom_image3);
        $stmt->execute();
    }

    if (isset($_SESSION['post_logement']['image4P'])) {
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image4)"
        );
        $stmt->bindParam(':image4', $nom_image4);
        $stmt->execute();
    }

    if (isset($_SESSION['post_logement']['image5P'])) {
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image5)"
        );
        $stmt->bindParam(':image5', $nom_image5);
        $stmt->execute();
    }

    if (isset($_SESSION['post_logement']['image6P'])) {
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image6)"
        );
        $stmt->bindParam(':image6', $nom_image6);
        $stmt->execute();
    }

    if (isset($_SESSION['post_logement']['image2P'])) {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo)
                VALUES (:logement, :image2)'
        );
        $stmt->bindParam(':image2', $nom_image2);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->execute();
    }

    if (isset($_SESSION['post_logement']['image3P'])) {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo)
                VALUES (:logement, :image3)'
        );
        $stmt->bindParam(':image3', $nom_image3);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->execute();
    }


    if (isset($_SESSION['post_logement']['image4P'])) {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo)
                VALUES (:logement, :image4)'
        );
        $stmt->bindParam(':image4', $nom_image4);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->execute();
    }

    if (isset($_SESSION['post_logement']['image5P'])) {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo)
                VALUES (:logement, :image5)'
        );
        $stmt->bindParam(':image5', $nom_image5);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->execute();
    }


    if (isset($_SESSION['post_logement']['image6P'])) {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo)
                VALUES (:logement, :image6)'
            
<<<<<<< HEAD
        </main>
    
        <?php 
        echo file_get_contents('../header-footer/footer.html');
    ?>
</body>

</html>
<script src="../scriptPopup.js"></script>
=======
        );
        $stmt->bindParam(':image6', $nom_image6);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->execute();
    }
    unset($_SESSION['post_logement']);

    header("Location: ../Accueil/Tableau_de_bord.php");
?>
>>>>>>> origin/youen
