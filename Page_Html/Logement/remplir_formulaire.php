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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un logement</title>
    <link rel="stylesheet" href="../style.css">
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


    

    <main>
        <div class='banniere'>
            <img src='../svg/arrow-left-s-line 1.svg'>
            <h1>Remplir la fiche logement</h1>
        </div>
        <div class="column">
            <form method='POST' action='previsualiser_logement.php' enctype="multipart/form-data">
                <fieldset>
                    
                    <label for='nom'>Libellé logement :</label>
                    <input id='nom' type='text' name='nomP' placeholder='Nom du logement' required>

                    <label for='ville'>Ville : </label>
                    <input id='ville' type='text' name='villeP' placeholder='Ville' required>

                    <label for='code_postal'>Code postal : </label>
                    <input id='code_postal' type='text' name='code_postalP' placeholder='Code postal' required>

                    <label for='tarif_de_base'>Tarif de base (en €) : </label>
                    <input id='tarif_de_base' type='number' name='tarif_de_baseP' min='0' max='2500' step='1' value='0' required>

                    <label for='phrase_daccroche'>Phrase d'accroche : </label>
                    <input id='accroche' type='text' name='accrocheP' placeholder="Phrase d'accroche" required>

                    <label for='description'>Description : </label>
                    <textarea id='description' name='descriptionP' placeholder='Description' required></textarea>

                    <label for='nature'>Nature : </label>
                    <select id='nature' name='natureP' placeholder='Nature' required>
                        <option value='1'>Maison</option>
                        <option value='2'>Appartement</option>
                        <option value='3'>Manoir</option>
                        <option value='4'>Château</option>
                    </select>

                    <label for='type'>Type : </label>
                    <select id='type' name='typeP' aria-placeholder="Type" required>
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

                    <label for='nb_chamnbres'>Nombre de chambres : </label>
                    <input id='nb_chambres' type='number' name='nb_chambresP' min='0' max='15' step='1' value='0' required>

                    <label for='nb_lit_simple'>Nombre de lits simples : </label>
                    <input id='nb_lit_simple' type='number' name='nb_lit_simpleP' min='0' max='15' step='1' value='0' required>

                    <label for='nb_lit_double'>Nombre de lits doubles : </label>
                    <input id='nb_lit_double' type='number' name='nb_lit_doubleP' min='0' max='15' step='1' value='0' required>

                    <label for='nb_sdb'>Nombre de salles de bain : </label>
                    <input id='nb_sdb' type='number' name='nb_sdbP' min='0' max='10' step='1' value='0' required>

                    <label for='surface_maison'>Surface (en m²) : </label>
                    <input id='surface_maison' type='number' name='surface_maisonP' min='0' max='300' step='1' value='0' required>

                    <label for='nb_personne_max'>Nombre de personnes maximum : </label>
                    <input id='nb_personne_max' type='number' name='nb_personne_maxP' min='1' max='15' step='1' value='0' required>

                    <label for='surface_jardin'>Surface du jardin (en m2): : </label>
                    <input id='surface_jardin' type='number' name='surface_jardinP' min='0' max='50000' step='1' value='0' required>

                </fieldset>
                <fieldset>
                <br>
                    <h4>Equipements et services</h4>
                    <label for='equipement'>Equipements :</label><br>
                    <input id='equipement' type='checkbox' name='balconP'>Balcon
                    <input id='equipement' type='checkbox' name='terrasseP'>Terrasse
                    <input id='equipement' type='checkbox' name='piscineP'>Piscine
                    <input id='equipement' type='checkbox' name='climatisationP'>Piscine
                    <input id='equipement' type='checkbox' name='jacuzziP'>Jacuzzi
                    <input id='equipement' type='checkbox' name='saunaP'>Sauna
                    <input id='equipement' type='checkbox' name='hammamP'>Hammam
                    <input id='equipement' type='checkbox' name='parking_publicP'>Parking public
                    <input id='equipement' type='checkbox' name='parking_priveP'>Parking privé
                    <input id='equipement' type='checkbox' name='televisionP'>Télévision
                    <input id='equipement' type='checkbox' name='wifiP'>Wifi
                    <input id='equipement' type='checkbox' name='lave_vaisselleP'>Lave vaisselle
                    <input id='equipement' type='checkbox' name='lave_lingeP'>Lave linge
                    <br>


                    <h4>Prix des charges</h4>
                    <label for='taxe_sejour'>Taxe de séjour pour une personne : </label>
                    <input id='taxe_sejour' type='number' name='taxe_sejourP' min='0' max='25' step='1' required>
                    <br>
                    <label for='charges_menage'>Prix charge additionnelle "ménage" : </label>
                    <input id='charges_menage' type='number' name='charges1P' min='0' max='1000' step='1' required>
                    <br>
                    <label for='charges_animaux'>Prix charge additionnelles "animaux" : </label>
                    <input id='charges_animaux' type='number' name='charges2P' min='0' max='1000' step='1' required>
                    <br>
                    <label for='charges_pers_sup'>Prix charge additionnelle "personnes supplémentaire" : </label>
                    <input id='charges_pers_sup' type='number' name='charges3P' min='0' max='1000' step='1' required>

                </fieldset>
                <fieldset>
                    <h4>Images logement</h4>
                    <label for='image1'>Image 1</label>
                    <input id='image1' type='file' name='image1P' accept='image/png, image/jpeg' required>

                    <label for='image2'>Image 2</label>
                    <input id='image2' type='file' name='image2P' accept='image/png, image/jpeg'>

                    <label for='image3'>Image 3</label>
                    <input id='image3' type='file' name='image3P' accept='image/png, image/jpeg'>

                    <label for='image4'>Image 4</label>
                    <input id='image4' type='file' name='image4P' accept='image/png, image/jpeg'>

                    <label for='image5'>Image 5</label>
                    <input id='image5' type='file' name='image5P' accept='image/png, image/jpeg'>

                    <label for='image6'>Image 6</label>
                    <input id='image6' type='file' name='image6P' accept='image/png, image/jpeg'>
                                
                    <button name='previsualiser' type='submit'>Prévisualiser</button>
                </fieldset>
            </form>
        </div>

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