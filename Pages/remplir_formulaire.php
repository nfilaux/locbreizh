<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un logement</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <header class="row col-12">
        <div class="row col-3">
            <img src="/Ressources/Images/logo.svg">
            <h2 style="margin-top: auto; margin-bottom: auto; margin-left: 10px;">Loc'Breizh</h2>
        </div>

        <div class="row col-3">
            <img class="col-2" src="/Ressources/Images/filtre.svg">
            <input class="col-7" id="searchbar" type="text" name="search" style="height: 50px; margin-top: auto; margin-bottom: auto;">
            <img class="col-2" src="/Ressources/Images/loupe.svg">
        </div>
        <div class="row col-4">
            <img src="/Ressources/Images/booklet-fill 1.svg">
            <a href="logement.php" style="margin: auto;margin-left: 10px;">
                <h4 style="color:#000;">Accèder à mes réservations</h4>
            </a>
        </div>


        <div class="col-2 row">
            <a class="offset-md-6 row"><img src="/Ressources/Images/message.svg"></a>
            <a class="offset-md-2 row"><img src="/Ressources/Images/compte.svg"></a>
        </div>
    </header>

    <div class='banniere'>
        <img src='/Ressources/Images/arrow-left-s-line 1.svg'>
        <h1>Remplir la fiche logement</h1>
    </div>

    <main>
        <form method='POST' action='previsualiser_logement.php' enctype="multipart/form-data">
            <fieldset>

                <label for='nom'>Libellé logement</label>
                <input id='nom' type='text' name='nomP' placeholder='Nom du logement' required>

                <label for='ville'>Ville</label>
                <input id='ville' type='text' name='villeP' placeholder='Ville' required>

                <label for='code_postal'>Code postal</label>
                <input id='code_postal' type='text' name='code_postalP' placeholder='Code postal' required>

                <label for='tarif_de_base'>Tarif de base (en €)</label>
                <input id='tarif_de_base' type='number' name='tarif_de_baseP' min='0' max='2500' step='100' value='0' required>

                <label for='phrase_daccroche'>Phrase d'accroche</label>
                <input id='accroche' type='text' name='accrocheP' placeholder="Phrase d'accroche" required>

                <label for='description'>Description</label>
                <textarea id='description' name='descriptionP' placeholder='Description' required></textarea>

                <label for='nature'>Nature</label>
                <select id='nature' name='natureP' placeholder='Nature' required>
                    <option value='1'>Maison</option>
                    <option value='2'>Appartement</option>
                    <option value='3'>Manoir</option>
                    <option value='4'>Château</option>
                </select>

                <label for='type'>Type</label>
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

                <label for='nb_chamnbres'>Nombre de chambres</label>
                <input id='nb_chambres' type='number' name='nb_chambresP' min='0' max='15' step='1' value='0' required>

                <label for='nb_lit_simple'>Nombre de lits simples</label>
                <input id='nb_lit_simple' type='number' name='nb_lit_simpleP' min='0' max='15' step='1' value='0' required>

                <label for='nb_lit_double'>Nombre de lits doubles</label>
                <input id='nb_lit_double' type='number' name='nb_lit_doubleP' min='0' max='15' step='1' value='0' required>

                <label for='nb_sdb'>Nombre de salles de bain</label>
                <input id='nb_sdb' type='number' name='nb_sdbP' min='0' max='10' step='1' value='0' required>

                <label for='surface_maison'>Surface (en m²)</label>
                <input id='surface_maison' type='number' name='surface_maisonP' min='0' max='300' step='10' value='0' required>

                <label for='nb_personne_max'>Nombre de personnes maximum</label>
                <input id='nb_personne_max' type='number' name='nb_personne_maxP' min='1' max='15' step='1' value='0' required>

                <label for='surface_jardin'>Surface du jardin (en m2)</label>
                <input id='surface_jardin' type='number' name='surface_jardinP' min='0' max='50000' step='100' value='0' required>

                <fieldset>
                    <label for='equipement'>Equipements</label>
                    <input id='equipement' type='checkbox' name='balcon' value='1'>Balcon
                    <input id='equipement' type='checkbox' name='terrasseP' value='2'>Terrasse
                    <input id='equipement' type='checkbox' name='piscineP' value='3'>Piscine
                    <input id='equipement' type='checkbox' name='jacuzziP' value='4'>Jacuzzi
                    <input id='equipement' type='checkbox' name='saunaP' value='5'>Sauna
                    <input id='equipement' type='checkbox' name='hammamP' value='6'>Hammam
                    <input id='equipement' type='checkbox' name='parking_publicP' value='7'>Parking public
                    <input id='equipement' type='checkbox' name='parking_priveP' value='8'>Parking privé
                    <input id='equipement' type='checkbox' name='televisionP' value='9'>Télévision
                    <input id='equipement' type='checkbox' name='wifiP' value='10'>Wifi
                    <input id='equipement' type='checkbox' name='lave_vaisselleP' value='11'>Lave vaisselle
                    <input id='equipement' type='checkbox' name='lave_lingeP' value='12'>Lave linge

                    <label for='service'>Service</label>
                    <input id='service' type='checkbox' name='menageP' placeholder='Service'>Ménage
                    <input id='service' type='checkbox' name='navetteP' placeholder='Service'>Navette/Taxi
                    <input id='service' type='checkbox' name='lingeP' placeholder='Service'>Linge
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
                <input id='taxe_sejour' type='number' name='taxe_sejourP' min='0' max='25' step='1' value='0' required>

                <button name='previsualiser' type='submit'>Prévisualiser</button>
            </fieldset>
        </form>

        
    </main>

    <footer class="container-fluid">
        <div class="column">
            <div class="text-center row">
                <p class="testfoot col-2"><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
                <p class="testfoot offset-md-2 col-2"><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
                <p class="testfoot offset-md-1 col-2"><a href="connexion.html"><img src="/Ressources/Images/instagram-fill 1.svg"> @LocBreizh</a></p>
                <p class="testfoot offset-md-1 col-2  "><a href="connexion.html"><img src="/Ressources/Images/facebook-circle-fill 1.svg"> @LocBreizh</a></p>
            </div>
            <hr>
            <div class="text-center row">
                <p class="offset-md-1 col-2 testfooter">©2023 Loc’Breizh</p>
                <p class="offset-md-1 col-3 testfooter" style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p class="offset-md-1 col-4 testfooter">Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
        </div>
    </footer>
</body>

</html>