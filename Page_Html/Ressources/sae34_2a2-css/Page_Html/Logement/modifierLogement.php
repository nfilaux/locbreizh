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
        <title>Modifier un logement</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <body>
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
        <main  class="MainTablo">

        <div class="headtablo">
            <a><img src="../svg/flecheRetour.svg"></a>
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
                                                        <input disabled id='equipement' type='checkbox' name='parking_publicP' value='7' <?php if($res["parking_public"] == false){ ?> checked <?php ;}?>>Parking public
                                                    </div>
                                                    <div class="logcheckbox">
                                                        <input disabled id='equipement' type='checkbox' name='parking_priveP' value='8' <?php if($res["parking_privee"] == false){ ?> checked <?php ;}?>>Parking privé
                                                    </div>
                                                    <div class="logcheckbox"> 
                                                        <input disabled id='equipement' type='checkbox' name='televisionP' value='9' <?php if($res["television"] == false){ ?> checked <?php ;}?>>Télévision
                                                    </div>
                                                    <div class="logcheckbox"> 
                                                        <input disabled id='equipement' type='checkbox' name='wifiP' value='10' <?php if($res["wifi"] == false){ ?> checked <?php ;}?>>Wifi
                                                    </div>
                                                    <div class="logcheckbox">
                                                        <input disabled id='equipement' type='checkbox' name='lave_vaisselleP' value='11' <?php if($res["lave_vaisselle"] == false){ ?> checked <?php ;}?>>Lave vaisselle
                                                    </div>
                                                    <div class="logcheckbox">
                                                        <input disabled id='equipement' type='checkbox' name='lave_lingeP' value='12' <?php if($res["lave_linge"] == false){ ?> checked <?php ;}?>>Lave linge
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
                            <button class="btn-accueil" name='previsualiser' type='submit'>Modifier</button>
                        </div>
    
            </form>
    
            
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


<style>
    .popup {
        display: none;
        position: fixed;
        top: 15%;
        left: 91%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }
</style>
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