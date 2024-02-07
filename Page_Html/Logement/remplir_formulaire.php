<?php
    // On démarre la session
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

        // On récupère l'id du compte
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
<?php 
        include('../header-footer/choose_header.php');
    ?>
    <main class="MainTablo">
    <div class="headtabloP">
            <a href="../Accueil/Tableau_de_bord.php"><img src="../svg/flecheRetour.svg"></a>
            <h1>Remplir la fiche logement</h1>
        </div>



        <div class="column">
            <form method='POST' id="creation_logement" action='previsualiser_logement.php' enctype="multipart/form-data">
                <div class="logrow">  
                    <div class="logcolumn">  
                    
                        <div class="logpc">  
                            <label for='nom'>Libellé logement :</label>
                            <input maxlength="29" class="lognom" id='nomP' type='text' name='nomP' placeholder='Nom du logement' required>
                        </div>

                        <?php if(isset($_SESSION['erreurs']['libelle'])){
                            echo "<p>" . $_SESSION['erreurs']['libelle'] . "<p>";
                        };
                        ?>

                        <div class="logrowb" id="villeEtPrix"> 
                            <div class="log3vct">
                                <label for='ville'>Ville : </label>
                                <input maxlength="49" class="logvct" id='villeP' type='text' name='villeP' placeholder='Ville' required>
                                <p id="erreurVille"></p>
                            </div>

                            <?php if(isset($_SESSION['erreurs']['ville'])){
                                echo "<p>" . $_SESSION['erreurs']['ville'] . "</p>";
                            };
                            ?>
                            
                            <script>
                                var communeInput = document.getElementById('villeP');
                                var communeValide = false;
                                var infoCommuneCorrecte;
                                var nomCommuneCorrect;
                                var communeBretonne = false;
                                var form = document.getElementById('creation_logement');
                                communeInput.addEventListener('change', verifCommune);
                                communeInput.addEventListener('input', verifCommune);
                                communeInput.addEventListener('blur', verifCommune);


                                form.addEventListener('submit', function(event) {
                                    if (!communeValide) {
                                        event.preventDefault();
                                        document.getElementById('erreurVille').innerHTML = "Veuillez entrer une ville Bretonne valide.";
                                    }
                                });

                                function verifCommune(event) {
                                    var opencageUrl = "https://api.opencagedata.com/geocode/v1/json?q=" + encodeURIComponent(communeInput.value) + "&key=90a3f846aa9e490d927a787facf78c7e";

                                    fetch(opencageUrl)
                                        .then(response => response.json())
                                        .then(data => {
                                            if (event.type == "change" || event.type == "input"){
                                                if (data.results.length > 0) {
                                                    // La commune existe
                                                    communeValide = true;
                                                    communeInput.style.backgroundColor = "#B2FF9F";
                                                    communeInput.style.borderColor = "green";
                                                    document.getElementById('erreurVille').innerHTML = "";
                                                    infoCommuneCorrecte = data.results[0];
                                                    nomCommuneCorrect = data.results[0].components.city || data.results[0].components.village || data.results[0].components.town;
                                                }else {
                                                    // La commune n'existe pas
                                                    communeValide = false;
                                                    document.getElementById('erreurVille').innerHTML = "Entrer une ville valide.";
                                                    communeInput.style.backgroundColor = "#FF9F9F";
                                                    communeInput.style.borderColor = "red";
                                                }
                                            }                                             
                                        
                                            if (event.type == "blur"){                                                                                          
                                                if (communeValide){                 
                                                    document.getElementById("villeP").value = nomCommuneCorrect;                                 
                                                    if (estEnBretagne(infoCommuneCorrecte)) {
                                                            //la commune est en Bretagne
                                                            communeBretonne = true;        
                                                            communeInput.style.backgroundColor = "#B2FF9F";
                                                            communeInput.style.borderColor = "green"; 
                                                            document.getElementById('erreurVille').innerHTML = "";                                                                                                                       
                                                            trouveCP(infoCommuneCorrecte);
                                                            communeValide = true;
                                                    } else {
                                                            //la commune n'est pas en Bretagne
                                                            communeBretonne = false;
                                                            communeInput.style.backgroundColor = "#FF9F9F";
                                                            communeInput.style.borderColor = "red";
                                                            document.getElementById('erreurVille').innerHTML = "La ville doit se situer en Bretagne.";
                                                            trouveCP(infoCommuneCorrecte);
                                                            communeValide = false;
                                                    }
                                                }
                                            }                                      
                                        })
                                        .catch(error => {
                                            console.error("Erreur lors de la requête de géocodage avec OpenCage Data:", error);
                                        });                                    
                                }
                              
                                function estEnBretagne(infoCommuneCorrecte) {
                                    // Vous pouvez ajuster ces coordonnées pour définir la zone géographique de la Bretagne
                                    var coordBretagne = {
                                        minLat: 47.08,
                                        maxLat: 48.85,
                                        minLng: -5.5,
                                        maxLng: -1.2
                                    };

                                    var villeLat = infoCommuneCorrecte.geometry.lat;
                                    var villeLng = infoCommuneCorrecte.geometry.lng;

                                    return (
                                        villeLat >= coordBretagne.minLat &&
                                        villeLat <= coordBretagne.maxLat &&
                                        villeLng >= coordBretagne.minLng &&
                                        villeLng <= coordBretagne.maxLng
                                    );
                                }

                                function trouveCP(infoCommuneCorrecte){
                                    if (("postcode" in infoCommuneCorrecte.components) && (communeBretonne)){
                                        document.getElementById('code_postal').value = infoCommuneCorrecte.components.postcode;
                                        document.getElementById("code_postal").disabled = true;
                                    }else {
                                        document.getElementById('code_postal').value = "";
                                        document.getElementById("code_postal").disabled = false;
                                    }
                                }

                            </script>

                            <div class="log3vct">
                                <label for='code_postal'>Code postal : </label>
                                <input maxlength="5" class="logvct" id='code_postal' type='text' name='code_postalP' placeholder='Code postal' title="Le code postal est incorrect" required>
                                <p id="erreurCP"></p>
                                <script>
                                    var codePostalInput = document.getElementById("code_postal");
                                    document.getElementById("erreurCP").textContent = "";
                                    codePostalInput.addEventListener('change', verifCP);
                                    codePostalInput.addEventListener('input', verifCP);
                                    function verifCP(event){
                                        var regex = /^(29|35|22|56)\d{3}$/;
                                        if (event.type == "change" || event.type == "input"){
                                            if (!regex.test(codePostalInput.value)) {
                                                document.getElementById("erreurCP").textContent = "Saisissez un code postal breton valide.";
                                            }
                                            else{
                                                document.getElementById("erreurCP").textContent = "";
                                            }
                                        }
                                    }
                                </script>
                            </div>

                            <?php if(isset($_SESSION['erreurs']['code_postal'])){
                                echo "<p>" . $_SESSION['erreurs']['code_postal'] . "</p>";
                            };
                            ?>

                            <div class="log3vct">
                                <label for='tarif_de_base'>Tarif de base (en €/jour) : </label>
                                <input class="logvct" id='tarif_de_base' type='number' name='tarif_de_baseP' min='0' max='2500' step='0.01' placeholder='0' required>
                            </div>

                            <?php if (isset($_SESSION['erreurs']['prix'])){echo '<p>Le prix est un incorrect il ne dois pas dépasser 100 000 € et doit être décimal !';};?>
                        </div>
                        <div class="logpc">
                            <label for='phrase_daccroche'>Phrase d'accroche : </label>
                            <textarea class="logPAP" id='accroche' name='accrocheP' maxlength="99" placeholder="Phrase d'accroche" required></textarea>
                        </div>
                        <div class="logrowb"> 
                                <div class="log2vct">
                                    <label for='nature'>Nature : </label>
                                    <select class="logselect" id='nature' name='natureP' placeholder='Nature' required>
                                        <option value='1'>Maison</option>
                                        <option value='2'>Appartement</option>
                                        <option value='3'>Manoir</option>
                                        <option value='4'>Château</option>
                                    </select>
                                </div>
                                <div class="log2vct">
                                    <label for='type'>Type : </label>
                                    <select class="logselect" id='type' name='typeP' aria-placeholder="Type" required>
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
                                <label for='nb_chamnbres'>Nombre de chambres : </label>
                                <input  class="logvct" id='nb_chambres' type='number' name='nb_chambresP' min='0' max='150' step='1' placeholder='0' required>
                            </div>
                            <div class="log4vct">
                                <label for='nb_lit_simple'>Nombre de lits simples : </label>
                                <input class="logvct" id='nb_lit_simple' type='number' name='nb_lit_simpleP' min='0' max='150' step='1' placeholder='0' required>
                            </div>
                            <div class="log4vct">
                                <label for='nb_lit_double'>Nombre de lits doubles : </label>
                                <input  class="logvct" id='nb_lit_double' type='number' name='nb_lit_doubleP' min='0' max='150' step='1' placeholder='0' required>
                            </div>
                            <div class="log4vct">
                                <label for='nb_sdb'>Nombre de salles de bain : </label>
                                <input  class="logvct" id='nb_sdb' type='number' name='nb_sdbP' min='0' max='150' step='1' placeholder='0' required>
                            </div>
                        </div>

                        <div class="logrowb"> 
                            <div class="log3vct">
                                <label for='surface_maison'>Surface (en m²) : </label>
                                <input class="logvct" id='surface_maison' type='number' name='surface_maisonP' min='0' max='500' step='1' placeholder='0' required>
                            </div>
                            <div class="log3vct">
                                <label for='nb_personne_max'>Nombre de personnes maximum : </label>
                                <input class="logvct" id='nb_personne_max' type='number' name='nb_personne_maxP' min='1' max='100' step='1' placeholder='0' required>
                            </div>
                            <div class="log3vct">
                                <label for='surface_jardin'>Surface du jardin (en m2): </label>
                                <input class="logvct" id='surface_jardin' type='number' name='surface_jardinP' min='0' max='5000' step='1' placeholder='0' required>
                            </div>
                        </div>
                    </div>
                    <div class="logcolumn">
                        <div class="description">
                            <label for='description'>Description : </label>
                            <textarea class="logPAP" id='description' maxlength="499"name='descriptionP' placeholder='Description' required></textarea>
                        </div>
                        <fieldset>
                            <div class="logrow">
                            <div class="logcolumn">
                                    <label for='equipement'>Equipements :</label><br>
                                    <div class="logrow">
                                        <div class="logcolumn">
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='balconP'>Balcon
                                            </div>
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='terrasseP'>Terrasse
                                            </div>
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='piscineP'>Piscine
                                            </div>
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='climatisationP'>Climatisation
                                            </div>
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='jacuzziP'>Jacuzzi
                                            </div>
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='saunaP'>Sauna
                                            </div>
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='hammamP'>Hammam
                                            </div>
                                        </div>
                                        <div class="logcolumn">
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='parking_publicP'>Parking public
                                            </div>
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='parking_priveP'>Parking privé
                                            </div>
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='televisionP'>Télévision
                                            </div>
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='wifiP'>Wifi
                                            </div>
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='lave_vaisselleP'>Lave vaisselle
                                            </div>
                                            <div class="logcheckbox">
                                                <input id='equipement' type='checkbox' name='lave_lingeP'>Lave linge
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="logcolumn">
                                <label for='service'>Services :</label><br>
                                    <div class="logcheckbox">
                                    <input id='' type='checkbox' name='menage'>Ménage
                                    </div>
                                    <div class="logcheckbox">
                                    <input id='' type='checkbox' name='navette'>Navette/taxi 
                                    </div>
                                    <div class="logcheckbox">
                                    <input id='' type='checkbox' name='linge'>Linge
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="logpc">
                    <h4 class="titreAL">Prix des charges</h4>
                    <div class="logcol">

                    <div class="logrow">
                        <div class="logpc">
                            <label for='taxe_sejour'>Taxe de séjour pour une personne : </label>
                            <input class="cardprixcharge" id='taxe_sejour' type='number' name='taxe_sejourP' min='0' max='25' step='0.01' required>
                        </div>

                        <?php 
                            if(isset($_SESSION['erreurs']['taxe'])){
                                echo "<p>" . $_SESSION['erreurs']['taxe'] . "<p>";
                            };
                        ?>

                        <div class="logpc">
                            <label for='charges_menage'>Prix charge additionnelle "ménage" : </label>
                            <input class="cardprixcharge" id='charges_menage' type='number' name='charges1P' min='0' max='1000' step='0.01' required>
                        </div>
                    
                        <?php 
                            if(isset($_SESSION['erreurs']['menage'])){
                                echo "<p>" . $_SESSION['erreurs']['menage'] . "<p>";
                            };
                        ?>
                    </div>
                    <div class="logrow">
                        <div class="logpc">
                            <label for='charges_animaux'>Prix charge additionnelles "animaux" : </label>
                            <input class="cardprixcharge" id='charges_animaux' type='number' name='charges2P' min='0' max='1000' step='0.01' required>
                        </div>

                        <?php 
                            if(isset($_SESSION['erreurs']['animaux'])){
                                echo "<p>" . $_SESSION['erreurs']['animaux'] . "<p>";
                            };
                        ?>

                        <div class="logpc">
                            <label for='charges_pers_sup'>Prix charge additionnelle "personnes supplémentaire" : </label>
                            <input class="cardprixcharge" id='charges_pers_sup' type='number' name='charges3P' min='0' max='1000' step='0.01' required>
                        </div>

                        <?php 
                            if(isset($_SESSION['erreurs']['pers_sup'])){
                                echo "<p>" . $_SESSION['erreurs']['pers_sup'] . "<p>";
                            };
                        ?>
                        </div>
                    </div>
                </div>
                <div class="logpc">
                    <h4 class="titreAL">Images logement</h4>
                    <div class="logrow">
                        <div class="logpc">
                            <label for='image1'>Image 1</label>
                            <input id='image1' type='file' name='image1P' accept='.jpg , .jpeg, .png'  required onchange="validateImage(this, 'in_image1')">
                            <img src="../Ressources/Images/image_vide_log.png" id="in_image1" title="photo" alt="photo de profil" class="modif_log_img">

                            <label for='image2'>Image 2</label>
                            <input id='image2' type='file' name='image2P' accept='.jpg , .jpeg, .png' onchange="validateImage(this, 'in_image2')">
                            <img src="../Ressources/Images/image_vide_log.png" id="in_image2" title="photo" alt="photo de profil" class="modif_log_img">

                            <label for='image3'>Image 3</label>
                            <input id='image3' type='file' name='image3P' accept='.jpg , .jpeg, .png' onchange="validateImage(this, 'in_image3')">
                            <img src="../Ressources/Images/image_vide_log.png" id="in_image3" title="photo" alt="photo de profil" class="modif_log_img">
                        </div>
                        <div class="logpc">
                            <label for='image4'>Image 4</label>
                            <input id='image4' type='file' name='image4P' accept='.jpg , .jpeg, .png' onchange="validateImage(this, 'in_image4')">
                            <img src="../Ressources/Images/image_vide_log.png" id="in_image4" title="photo" alt="photo de profil" class="modif_log_img">

                            <label for='image5'>Image 5</label>
                            <input id='image5' type='file' name='image5P' accept='.jpg , .jpeg, .png' onchange="validateImage(this, 'in_image5')">
                            <img src="../Ressources/Images/image_vide_log.png" id="in_image5" title="photo" alt="photo de profil" class="modif_log_img">

                            <label for='image6'>Image 6</label>
                            <input id='image6' type='file' name='image6P' accept='.jpg , .jpeg, .png' onchange="validateImage(this, 'in_image6')">
                            <img src="../Ressources/Images/image_vide_log.png" id="in_image6" title="photo" alt="photo de profil" class="modif_log_img">
                        </div>
                    </div>
                    <button class="btn-previsualiser" name='previsualiser' type='submit'>Prévisualiser</button>
                </div>

            </form>
        </div>

    </main>
    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>

</html>
<?php 
    unset($_SESSION['erreurs']);
?>
<script>
    function validateImage(input, imgId) {
        var file = input.files[0];
        var img = document.getElementById(imgId);

        if (file) {
            var reader = new FileReader();

            reader.onload = function (e) {
                var image = new Image();
                image.src = e.target.result;

                image.onload = function () {
                    // Vérifier si l'image n'est pas un GIF
                    if (file.type !== "image/gif" && !file.type.includes("image/webp")) {
                        // Fichier non-GIF, chargement de l'image
                        img.src = e.target.result;  
                    } else {
                        // Fichier GIF, affichage d'une alerte et réinitialisation de l'input
                        alert("Veuillez sélectionner un fichier de type png jpeg jpg.");
                        input.value = "";
                        img.src = "../Ressources/Images/image_vide_log.png";
                    }
                };
            };

            reader.readAsDataURL(file);
        }
    }
</script>
<script src="./actualiserImage.js" defer></script>