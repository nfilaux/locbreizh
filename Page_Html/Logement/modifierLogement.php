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
        <title>Modifier logement</title>
    </head>
    
    <body>
    <header>
<a href="../Accueil/Tableau_de_bord.php">
    <div>
        <img src="../svg//logo.svg">
        <h2>Loc'Breizh</h2>
    </div></a>

    <div>
        <img src="../svg//filtre.svg">
        <input id="searchbar" type="text" name="search">
        <img src="../svg//loupe.svg">
    </div>
        <div>
            <img src="../svg//booklet-fill 1.svg">
            <a href="../Accueil/Tableau_de_bord.php">
                <h4>Accèder à mon tableau de bord</h4>
            </a>
        </div>
        

    <div>
        <a href="../messagerie/messagerie.php"><img src="../svg/message.svg"></a>
        <a onclick="openPopup()"><img id="pp" src="../Ressources/Images/<?php echo $photo['photo']; ?>"></a> 
    </div>
    <div id="popup" class="popup">
        <a href="">Accéder au profil</a>
        <br>
        <a href="../Compte/seDeconnecter.php">Se déconnecter</a>
        <a onclick="closePopup()">Fermer la fenêtre</a>
    </div>
</header>
    
        <div class='banniere'>
            <img src='/Ressources/Images/arrow-left-s-line 1.svg'>
            <h1>La fiche de votre logement</h1>
        </div>
    
        <main>
            <form method='POST' action='modifier.php?id_logement=<?php echo $id_logement ?>' enctype="multipart/form-data">
                <fieldset>
                    <label for='nom'>Libellé logement</label>
                    <input id='nom' type='text' name='nomP' value="<?php if ($erreur != []){if (!isset($erreur['libelle'])){echo $_SESSION['valeurs_complete']['libelle'];}} else { echo $res["libelle_logement"];} ?>" required>
                    <?php
                    if (isset($erreur['libelle'])){
                        echo '<p id="erreur">' . $erreur['libelle'] .  '</p>';
                    }
                    ?>
                    <br>
                    <label for='ville'>Ville</label>
                    <input id='ville' type='text' name='villeP' value="<?php if ($erreur != []){if (!isset($erreur['ville'])){echo $_SESSION['valeurs_complete']['ville'];}} else { echo $adresse['ville'];} ?>" required>
                    <?php
                    if (isset($erreur['ville'])){
                        echo '<p id="erreur">' . $erreur['ville'] .  '</p>';
                    }
                    ?>
                    <br>
                    <label for='code_postal'>Code postal</label>
                    <input id='code_postal' type='text' name='code_postalP' placeholder='Code postal' value="<?php if ($erreur != []){if (!isset($erreur['code_postal'])){echo $_SESSION['valeurs_complete']['code_postal'];}} else { echo $adresse["code_postal"];} ?>"required>
                    <?php
                    if (isset($erreur['code_postal'])){
                        echo '<p id="erreur">' . $erreur['code_postal'] .  '</p>';
                    }
                    ?>
                    <br>
                    <label for='tarif_de_base'>Tarif de base (en €)</label>
                    <input id='tarif_de_base' type='number' name='tarif_de_baseP' min='0' max='2500' value="<?php if (!isset($erreur['tarif_base_ht'])){echo $res["tarif_base_ht"];} ?>" required>
                    <?php
                    if (isset($erreur['tarif_base_ht'])){
                        echo '<p id="erreur">' . $erreur['tarif_base_ht'] .  '</p>';
                    }
                    ?>
                    <br>
                    <label for='phrase_daccroche' >Phrase d'accroche</label>
                    <input disabled id='accroche' type='text' name='accrocheP' placeholder="Phrase d'accroche" value="<?php echo $res["accroche_logement"]; ?>"required>
                    <br>
                    <label for='description'>Description</label>
                    <textarea id='description' name='descriptionP' placeholder='Description' required><?php if (!isset($erreur['descriptif_logement'])){echo $res["descriptif_logement"];} ?></textarea>
                    <?php
                    if (isset($erreur['descriptif_logement'])){
                        echo '<p id="erreur">' . $erreur['descriptif_logement'] .  '</p>';
                    }
                    ?>
                    <label for='nature'>Nature</label>
                    <select id='nature' name='natureP' placeholder='Nature' disabled required>
                        <option value='1'>Maison</option>
                        <option value='2'>Appartement</option>
                        <option value='3'>Manoir</option>
                        <option value='4'>Château</option>
                    </select>
    
                    <label for='type'>Type</label>
                    <select id='type' name='typeP' aria-placeholder="Type" disabled required>
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
    
                    <label for='nb_chamnbres' >Nombre de chambres</label>
                    <input id='nb_chambres' disabled type='number' name='nb_chambresP' min='0' max='15' step='1' value=<?php echo $res["nb_chambre"] ?> required>
    
                    <label for='nb_lit_simple'>Nombre de lits simples</label>
                    <input id='nb_lit_simple' disabled type='number' name='nb_lit_simpleP' min='0' max='15' step='1' value=<?php echo $res["lit_simple"] ?> required>
    
                    <label for='nb_lit_double'>Nombre de lits doubles</label>
                    <input id='nb_lit_double' disabled type='number' name='nb_lit_doubleP' min='0' max='15' step='1' value=<?php echo $res["lit_double"] ?> required>
    
                    <label for='nb_sdb'>Nombre de salles de bain</label>
                    <input id='nb_sdb' disabled type='number' name='nb_sdbP' min='0' max='10' step='1' value=<?php echo $res["nb_salle_bain"] ?> required>
    
                    <label for='surface_maison'>Surface (en m²)</label>
                    <input id='surface_maison' disabled type='number' name='surface_maisonP' min='0' max='300' step='1' value=<?php echo $res["surface_logement"] ?> required>
    
                    <label for='nb_personne_max'>Nombre de personnes maximum</label>
                    <input id='nb_personne_max' disabled type='number' name='nb_personne_maxP' min='1' max='15' step='1' value=<?php echo $res["nb_personnes_logement"] ?> required>
    
                    <label for='surface_jardin'>Surface du jardin (en m2)</label>
                    <input id='surface_jardin' disabled type='number' name='surface_jardinP' min='0' max='50000' step='1' value=<?php echo $res["jardin"] ?> required>
    
                    <fieldset>
                        <label for='equipement'>Equipements</label>
                        <input disabled id='equipement' type='checkbox' name='balcon' value='1' <?php if($res["balcon"] == true){ ?> checked <?php ;}?>>Balcon
                        <input  disabled id='equipement' type='checkbox' name='terrasseP' value='2' <?php if($res["terrasse"] == true){ ?> checked <?php ;}?>>Terrasse
                        <input disabled id='equipement' type='checkbox' name='piscineP' value='3' <?php if($res["piscine"] == true){ ?> checked <?php ;}?>>Piscine
                        <input disabled id='equipement' type='checkbox' name='jacuzziP' value='4' <?php if($res["jacuzzi"] == true){ ?> checked <?php ;}?>>Jacuzzi
                        <input disabled id='equipement' type='checkbox' name='saunaP' value='5' <?php if($res["sauna"] == true){ ?> checked <?php ;}?>>Sauna
                        <input disabled id='equipement' type='checkbox' name='hammamP' value='6' <?php if($res["hammam"] == true){ ?> checked <?php ;}?>>Hammam
                        <input disabled id='equipement' type='checkbox' name='parking_publicP' value='7' <?php if($res["parking_public"] == false){ ?> checked <?php ;}?>>Parking public
                        <input disabled id='equipement' type='checkbox' name='parking_priveP' value='8' <?php if($res["parking_privee"] == false){ ?> checked <?php ;}?>>Parking privé
                        <input disabled id='equipement' type='checkbox' name='televisionP' value='9' <?php if($res["television"] == false){ ?> checked <?php ;}?>>Télévision
                        <input disabled id='equipement' type='checkbox' name='wifiP' value='10' <?php if($res["wifi"] == false){ ?> checked <?php ;}?>>Wifi
                        <input disabled id='equipement' type='checkbox' name='lave_vaisselleP' value='11' <?php if($res["lave_vaisselle"] == false){ ?> checked <?php ;}?>>Lave vaisselle
                        <input disabled id='equipement' type='checkbox' name='lave_lingeP' value='12' <?php if($res["lave_linge"] == false){ ?> checked <?php ;}?>>Lave linge
    
                        <label for='service'>Service</label>
                        <input disabled id='service' type='checkbox' name='menageP' placeholder='Service'>Ménage
                        <input disabled id='service' type='checkbox' name='navetteP' placeholder='Service'>Navette/Taxi
                        <input disabled id='service' type='checkbox' name='lingeP' placeholder='Service'>Linge
                    </fieldset>
    
                    <h1>Images logement</h1>
                    <label for='image1'>Image 1</label>
                    <input id='image1' type='file' name='image1P' accept='image/png, image/jpeg' required>
    
                    <label for='image2'>Image 2</label>
                    <input id='image2' type='file' name='image2' accept='image/png, image/jpeg'>
    
                    <label for='image3'>Image 3</label>
                    <input id='image3' type='file' name='image3' accept='image/png, image/jpeg'>
    
                    <label for='image4'>Image 4</label>
                    <input id='image4' type='file' name='image4' accept='image/png, image/jpeg'>
    
                    <label for='image5'>Image 5</label>
                    <input id='image5' type='file' name='image5' accept='image/png, image/jpeg'>
    
                    <label for='image6'>Image 6</label>
                    <input id='image6' type='file' name='image6' accept='image/png, image/jpeg'>
    
                    <label for='taxe_sejour'>Taxe de séjour</label>
                    <input disabled id='taxe_sejour' type='number' name='taxe_sejourP' min='0' max='25' step='1' value=<?php echo $taxe; ?> required>
    
                    <button name='previsualiser' type='submit'>Modifier</button>
                </fieldset>
            </form>
    
            
        </main>
    
        <footer>
        <div>   
            <div>
                <p><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
                <p><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
                <p><a href="connexion.html"><img src="../svg/instagram.svg">  @LocBreizh</a></p>
                <p><a href="connexion.html"><img src="../svg/facebook.svg">  @LocBreizh</a></p>
            </div>
            <hr>  
            <div>
                <p>©2023 Loc’Breizh</p>
                <p><a href="connexion.html">Conditions générales</a></p>
                <p>Développé par <a href="connexion.html">7ème sens</a></p>
            </div>
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