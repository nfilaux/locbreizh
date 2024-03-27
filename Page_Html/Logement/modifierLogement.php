<?php
    session_start();
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
if(isset($_SESSION["erreurs"])){
    $erreur = $_SESSION["erreurs"];
}
else{
    $erreur = [];
}
$reqinfosLogement = $dbh->prepare("SELECT libelle_logement,tarif_base_ht,accroche_logement,descriptif_logement,nature_logement,type_logement,surface_logement,en_ligne,nb_chambre,nb_personnes_logement,lit_simple,lit_double,nb_salle_bain,jardin,balcon,terrasse,parking_public,parking_privee,sauna,hammam,piscine,climatisation,jacuzzi,television,wifi,lave_linge,lave_vaisselle,code_planning,id_proprietaire,id_adresse,photo_principale,taxe_sejour FROM locbreizh._logement WHERE id_logement = $id_logement");
$reqinfosLogement->execute();
$res = $reqinfosLogement->fetch();
$principale = $res['photo_principale'];
$id_ad = $res["id_adresse"];
$taxe = $res["taxe_sejour"];
$r_adresse = $dbh->prepare("SELECT ville, code_postal FROM locbreizh._adresse WHERE id_adresse = $id_ad");
$r_adresse->execute();
$adresse = $r_adresse->fetch();
$r_taxe = $dbh->prepare("SELECT prix_journalier_adulte FROM locbreizh._taxe_sejour WHERE id_taxe = $taxe");
$r_taxe->execute();
$taxe = $r_taxe->fetchColumn();

$r_services = $dbh->prepare("SELECT nom_service FROM locbreizh._services_compris WHERE logement = $id_logement");
$r_services->execute();
$services = $r_services->fetchAll();
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    
    <head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifier logement</title>
        <link rel="stylesheet" href="../style.css">
        <script src="../scriptPopupFeedback.js"></script>
    </head>
    
    <body>
        <?php 
            include('../header-footer/choose_header.php');
        ?>
    <main class="MainTablo">
    
        <div class='headtabloP'>
            <a href="../Accueil/Tableau_de_bord.php"><img src="../svg/flecheRetour.svg" alt="Flèche de retour"></a>
            <h1>La fiche de votre logement</h1>
        </div>
    
        <form method="post" action="modifier.php?id_logement=<?php echo $id_logement ?>" enctype="multipart/form-data">
                <div class="logrow">  
                    <div class="logcolumn">     
                        <div class="logpc">  
                            <label for='nom'>Libellé logement</label>
                            <input maxlength="29" class="lognom" id='nom' type='text' name='nomP' value="<?php if ($erreur != []){if (!isset($erreur['libelle'])){echo $_SESSION['valeurs_complete']['libelle'];}} else { echo $res["libelle_logement"];} ?>" required>
                    <?php
                    if (isset($erreur['libelle'])){
                        echo '<p id="erreur">' . $erreur['libelle'] .  '</p>';
                    }
                    ?>
                    
                    </div>
                        <div class="logrowb"> 
                            <div class="log3vct">
                                <label for='ville'>Ville</label>
                                <input maxlength="49" class="logvct" id='ville' type='text' name='villeP' value="<?php if ($erreur != []){if (!isset($erreur['ville'])){echo $_SESSION['valeurs_complete']['ville'];}} else { echo $adresse['ville'];} ?>" required>
                    <?php
                    if (isset($erreur['ville'])){
                        echo '<p id="erreur">' . $erreur['ville'] .  '</p>';
                    }
                    ?>
                    </div>
                    <div class="log3vct">
                        <label for='code_postal'>Code postal</label>
                        <input maxlength="5" class="logvct" id='code_postal' type='text' name='code_postalP' placeholder='Code postal' value="<?php if ($erreur != []){if (!isset($erreur['code_postal'])){echo $_SESSION['valeurs_complete']['code_postal'];}} else { echo $adresse["code_postal"];} ?>"required>
                    <?php
                    if (isset($erreur['code_postal'])){
                        echo '<p id="erreur">' . $erreur['code_postal'] .  '</p>';
                    }
                    ?>
                    </div>
                            <div class="log3vct">
                                <label for='tarif_de_base'>Tarif de base (en €)</label>
                                <input class="logvct" id='tarif_de_base' type='number' step="0.01" name='tarif_de_baseP' min='0' max='2500' value="<?php if (!isset($erreur['tarif_base_ht'])){echo $res["tarif_base_ht"];} ?>" required>
                    <?php
                    if (isset($erreur['tarif_base_ht'])){
                        echo '<p id="erreur">' . $erreur['tarif_base_ht'] .  '</p>';
                    }
                    ?>
                    </div>
                        </div>
                        <div class="logpc">
                            <label for='phrase_daccroche' >Phrase d'accroche</label>
                            <input disabled class="logPAP" id='accroche' type='text' name='accrocheP' placeholder="Phrase d'accroche" maxlength="255" value="<?php echo $res["accroche_logement"]; ?>"required>
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
                            <textarea maxlength="499" class="logPAP" id='description' name='descriptionP' placeholder='Description' required><?php if (!isset($erreur['descriptif_logement']) && isset($_SESSION['valeurs_complete']["descriptif_logement"])){echo $_SESSION['valeurs_complete']["descriptif_logement"];}else{ echo  $res['descriptif_logement'];} ?></textarea>
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
                            <input disabled id='service' type='checkbox' name='menageP' placeholder='Service' <?php foreach($services as $key => $value){if($value["nom_service"]=="menage"){ ?> checked <?php ;}}?>>Ménage
                        </div>
                        <div class="logcheckbox">       
                            <input disabled id='service' type='checkbox' name='navetteP' placeholder='Service' <?php foreach($services as $key => $value){if($value["nom_service"]=="navette"){ ?> checked <?php ;}}?>>Navette/Taxi
                        </div>
                        <div class="logcheckbox">
                            <input disabled id='service' type='checkbox' name='lingeP' placeholder='Service' <?php foreach($services as $key => $value){if($value["nom_service"]=="linge"){ ?> checked <?php ;}}?>>Linge
                        </div> 
                            </div>   
                                </div>   
                                        </div>
                                         </fieldset>
                                                </div>
                                                    </div>
                <div class="logpc">
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
                                <label for='image1'>Image principale</label>
                                <input id='image1' type='file' name='image1P' accept='image/png, image/jpeg'>
                                <img src="../Ressources/Images/<?php echo $principale ;?>" id="in_image1"  title="photo" alt="Photo de profil" class="modif_log_img">


                    <?php 
                        $stmt = $dbh->prepare("SELECT photo
                        FROM locbreizh._photos_secondaires 
                        WHERE logement = $id_logement and numero = 2");
                        $stmt->execute();
                        $photo = $stmt->fetch();

                        if(isset($photo['photo']) && $photo['photo'] != ''){
                            $src = $photo['photo'];
                        }
                        else{
                            $src = 'image_vide_log.png';
                        }
                    
                    ?>
                    <label for='image2'>Image 2</label>


                    <input id='image2' type='file' name='image2P' accept='image/png, image/jpeg'>
                    <img src="../Ressources/Images/<?php echo $src;?>" id="in_image2" title="photo" alt="Photo de profil" class="modif_log_img">

                    <?php 
                        $stmt = $dbh->prepare("SELECT photo
                        FROM locbreizh._photos_secondaires 
                        WHERE logement = $id_logement and numero = 3");
                        $stmt->execute();
                        $photo = $stmt->fetch();

                        if(isset($photo['photo']) && $photo['photo'] != ''){
                            $src = $photo['photo'];
                        }
                        else{
                            $src = 'image_vide_log.png';
                        }
                    
                    ?>
                          
                    <label for='image3'>Image 3</label>
                    <input id='image3' type='file' name='image3P' accept='image/png, image/jpeg'>
                    <img src="../Ressources/Images/<?php echo $src?>" id="in_image3"  title="photo" alt="Photo de profil" class="modif_log_img">
                    </div>

                    <?php 
                        $stmt = $dbh->prepare("SELECT photo
                        FROM locbreizh._photos_secondaires 
                        WHERE logement = $id_logement and numero = 4");
                        $stmt->execute();
                        $photo = $stmt->fetch();

                        if(isset($photo['photo']) && $photo['photo'] != ''){
                            $src = $photo['photo'];
                        }
                        else{
                            $src = 'image_vide_log.png';
                        }
                    
                    ?>

                    <div class="logpc">      
                    <label for='image4'>Image 4</label>
                    <input id='image4' type='file' name='image4P' accept='image/png, image/jpeg'>
                    <img src="../Ressources/Images/<?php echo $src; ?>" id="in_image4"  title="photo" alt="Photo de profil" class="modif_log_img">
    
                    <?php 
                        $stmt = $dbh->prepare("SELECT photo
                        FROM locbreizh._photos_secondaires 
                        WHERE logement = $id_logement and numero = 5");
                        $stmt->execute();
                        $photo = $stmt->fetch();

                        if(isset($photo['photo']) && $photo['photo'] != ''){
                            $src = $photo['photo'];
                        }
                        else{
                            $src = 'image_vide_log.png';
                        }
                    
                    ?>

                    <label for='image5'>Image 5</label>
                    <input id='image5' type='file' name='image5P' accept='image/png, image/jpeg'>
                    <img src="../Ressources/Images/<?PHP echo $src;?>" id="in_image5" title="photo" alt="Photo de profil" class="modif_log_img">
    
                    <?php 
                        $stmt = $dbh->prepare("SELECT photo
                        FROM locbreizh._photos_secondaires 
                        WHERE logement = $id_logement and numero = 6");
                        $stmt->execute();
                        $photo = $stmt->fetch();

                        if(isset($photo['photo']) && $photo['photo'] != ''){
                            $src = $photo['photo'];
                        }
                        else{
                            $src = 'image_vide_log.png';
                        }
                    
                    ?>

                    <label for='image6'>Image 6</label>
                    <input id='image6' type='file' name='image6P' accept='image/png, image/jpeg'>
                    <img src="../Ressources/Images/<?php echo $src; ?>" id="in_image6" title="photo" alt="Photo de profil" class="modif_log_img">
    
                    </div>
                        </div>
                        </div>            
                        <button class="btn-previsualiser" name='previsualiser' type='submit'>Modifier</button>
                    </div>
            </form>
            <div id="overlayModifierLogement" onclick="closePopupFeedback('popupFeedback', 'overlayModifierLogement')"></div>
            <div id="popupFeedback" class="popupFeedback">
                <p>Votre logement a bien été modifié !</p>
                <a href="../Accueil/Tableau_de_bord.php" class="btn-accueil"></button>OK</a>
            </div>
        </main>
    
        <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
        unset($_SESSION["erreurs"]);
    ?>
</body>

</html>

<?php
    if(isset($_GET['modif']) == '1'){
        ?>
        <script>
            openPopupFeedback('popupFeedback', 'overlayModifierLogement');
        </script>
        <?php
    }
    
?>



<script src="./actualiserImage.js" defer></script>
