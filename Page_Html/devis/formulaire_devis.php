<?php
    session_start();
    if(isset($_SESSION['erreurs'])){
        $erreurs = $_SESSION['erreurs'];
    }
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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire devis</title>
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

    <main>
        <?php
            // id fictif
            include('../parametre_connexion.php');
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // obtenu par l'url ($_GET)
            $d_a ="2015-02-25-";
            $d_d ="2010-02-23";
            $date_val = "13.2";
            $delai_accept = "3";

            $reqNomClient = $dbh->prepare("SELECT nom, prenom FROM locbreizh._demande_devis INNER JOIN locbreizh._compte ON _demande_devis.client = id_compte WHERE num_demande_devis = {$_GET['demande']}");
            $reqNomClient->execute();
            $infos_user = $reqNomClient->fetch(); 

            // recupere le nombre maximum de personnes pour le logement
            $stmt = $dbh->prepare("SELECT nb_personnes_logement as nb_pers from locbreizh._logement l
            join locbreizh._demande_devis d on d.logement = l.id_logement
            where d.num_demande_devis = {$_GET['demande']};");
            $stmt->execute();
            $nb_max = $stmt->fetch();

            $num_demande = $_GET["demande"];
            
            //on récupère les informatiosn pour préremplir le devis en fonction de la demande de devis qui lui est associé
            
            $stmt = $dbh->prepare("SELECT date_arrivee,date_depart,nb_personnes from locbreizh._demande_devis where num_demande_devis = $num_demande;");
            $stmt->execute();
            $infos_demande = $stmt->fetch();

            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._comporte_charges_associee_demande_devis where num_demande_devis = $num_demande and nom_charges = 'menage';");
            $stmt->execute();
            $menage = $stmt->fetchColumn();

            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._comporte_charges_associee_demande_devis where num_demande_devis = $num_demande and nom_charges = 'animaux';");
            $stmt->execute();
            $animaux = $stmt->fetchColumn();

            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._comporte_charges_associee_demande_devis where num_demande_devis = $num_demande and nom_charges = 'personnes_supplementaires';");
            $stmt->execute();
            $vac_sup = $stmt->fetchColumn();

            
        ?>
        <style>#erreur {color : red;}</style>
    <fieldset>
        <h1 class="policetitre colorviolet">La demande de devis de <?php echo $infos_user['prenom'] . ' '. $infos_user['nom']; ?> !</h1>
        <form name="formulaire" action="ajouter_devis.php" method="post">
    
        <div class="logrow">
            <div class="devispc">
                <div class="logrow">
                    <div class="log3vct">  
                        <label for="date_arrivee">date d'arrivée:</label>
                        <input class="logvct" type="date" id="date_arrivee" name="date_arrivee" value="<?php if(isset($_SESSION['valeurs_complete']['date_arrivee'])){ echo $_SESSION['valeurs_complete']['date_arrivee'];}else{if(!isset($erreurs['valide_dates'])){echo $infos_demande['date_arrivee']; }}; ?>" required /> 
                    </div>
                    <div class="log3vct">   
                        <label for="date_depart">date de départ:</label>
                        <input class="logvct" type="date" id="date_depart" name="date_depart" value="<?php if(isset($_SESSION['valeurs_complete']['date_depart'])){ echo $_SESSION['valeurs_complete']['date_depart'];}else{if(!isset($erreurs['valide_dates'])){echo $infos_demande['date_depart']; }}; ?>" required /> 
                    </div>
                    
                    <div class="log3vct">  
                    <label for="nb_pers">nombre de personnes:</label>
                    <!--appel php pour set la max value de nb personne par rapport au choix du proprio-->
                    <input class="logvct" type="number" id="nb_pers" name="nb_pers" placeholder="nombre de personnes" min="1" max=<?php echo $nb_max['nb_pers']; ?> value="<?php if(isset($_SESSION['valeurs_complete']['nb_pers'])){echo $_SESSION['valeurs_complete']['nb_pers'];}else{if(!isset($erreurs['valide_dates'])){echo $infos_demande['nb_personnes'];}}; ?>" required />
                    </div>
                </div>
                <div class="logrow">
                    <div class="log2vct">  
                        <label for="delais_accept">délais d'acceptation ( de 1 à 4 jours ) :</label>
                        <input class="logvct" type="number" min="1" max="4" id="delais_accept" name="delais_accept" value="<?php if(isset($_SESSION['valeurs_complete']['delais_accept'])){echo $_SESSION['valeurs_complete']['delais_accept'];} ?>" required />
                    </div>
                    <div class="log2vct"> 
                        <label for="date_val">date validité du devis ( en mois) :</label>
                        <input class="logvct" type="number" id="date_val" name="date_val" value="<?php if(isset($_SESSION['valeurs_complete']['date_val'])){echo $_SESSION['valeurs_complete']['date_val'];} ?>" required /> 
                    </div>
                </div>
            </div>

            <div class="cardSupplements">
            <h2 style="text-align:center;  font-family: 'Quicksand';">Charges aditionnelles</h2>
                <div class="logcheckbox">
                <!--pre-remplie les iinfos si ils sont dans get-->
                <input type="checkbox" id="animaux" name="animaux" <?php if(isset($_SESSION['valeurs_complete']['animaux'])){echo 'checked';}else if ($menage !=''){echo 'checked';}; ?>>
                 <label for="animaux"> Animaux </label>
                </div>
                <div class="logcheckbox">
                <!--pre-remplie les iinfos si ils sont dans get-->
                <input type="checkbox" id="menage" name="menage" <?php if(isset($_SESSION['valeurs_complete']['menage'])){echo 'checked';}else{if ($animaux !=''){echo 'checked';}}; ?>>
                <label for="menage"> Menage </label>
                </div>
                <!--pre-remplie les iinfos si ils sont dans get-->
                <div class="logpc">
                <label style="text-align:center;" for="nb_pers_supp">Vacanciers supplémentaires</label>
                <input class="lognb" type="text" id="vacanciers_sup" name="vacanciers_sup" min="0" max="100" placeholder="0" value="<?php if(isset($_SESSION['valeurs_complete']['vacanciers_sup'])){echo $_SESSION['valeurs_complete']['vacanciers_sup'];}else{if ($vac_sup!=''){echo $vac_sup;}}; ?>"/>
                </div>
            </div>
            <input type="hidden" name="logement" value="<?php echo $_GET['logement']; ?>">
            </div>



            <?php
            if (isset($erreurs['valide_dates'])){
                echo '<p id="erreur">' . $erreurs['valide_dates'] . '</p>';
            }
            ?>
            
            <?php
            if (isset($erreurs['cond_annul'])){
                echo '<p id="erreur">' . $erreurs['cond_annul'] .  '</p>';
            }
            ?>

            <input type="hidden" id="id_demande" name="id_demande" value=<?PHP echo $_GET['demande']; ?>>

            <label for="annulation">Condition annulation</label>
            <input class="logvct" type="text" id="annulation" name="annulation" value="<?php if(isset($_SESSION['valeurs_complete']['annulation'])){echo $_SESSION['valeurs_complete']['annulation'];} ?>" required/>
        </fieldset>

        <fieldset>
            <h1 class="policetitre colorviolet">Details pour le paiement</h1>
            <div class="devisrow">
                <p class="ren">A RENSEIGNER</p>
                <div class="deviscol">
                <label for="tarif_loc">Tarif HT de la location du logement (en €) :</label>
                <input class="logvct" type="number" id="tarif_loc" name="tarif_loc" value="<?php if(isset($_SESSION['valeurs_complete']['tarif_loc'])){echo $_SESSION['valeurs_complete']['tarif_loc'];} ?>" required /> 
                </div>
                <div class="deviscol">
                <label for="charges additionnelles">Charges additionnelles HT (en €) :</label>
                <input class="logvct" type="number" id="charges" name="charges" value="<?php if(isset($_SESSION['valeurs_complete']['charges'])){echo $_SESSION['valeurs_complete']['charges'];} ?>" required />
                </div>
                <input class="btn-ajoutlog" type="button" value="Calculer" onclick="calcul()"/>
            </div>
            
            <hr class="hr">

            
            <div id="resultat" class="deviscol">
                    <div class="devisrow">
                    <p class="ren">Calculer automatiquement</p>
                        <div class="deviscolinput">
                            <p> Total HT (en € ) </p>
                            <input class="logvct" id="totalht" name="totalht" value="" disabled>
                        </div>
                        <div class="deviscolinput">
                            <p> Total TTC (en € ) </p>
                            <input class="logvct" id="totalht" name="totalht" value="" disabled>
                        </div>
                        <div class="deviscolinput">
                            <p> Taxe de séjour (en € ) </p>
                            <input class="logvct" id="totalht" name="totalht" value="" disabled>
                        </div>
                    </div>
                    <div class="devisrow">
                    <div class="devisvct">
                            <p> Montant total du devis (en € ) </p>
                            <input class="logvct" id="totalht" name="totalht" value=""  disabled>
                        </div>
                    <div class="devisvct">
                            <p> Frais de plateforme HT (en € ) </p>
                            <input class="logvct" id="totalht" name="totalht" value="" disabled>
                        </div>
                    <div class="devisvct">
                        <p> Frais de plateforme TTC (en € ) </p>
                            <input class="logvct" id="totalht" name="totalht" value="" disabled>
                        </div>
                    </div>
            </div>
            <script>
                function roundDecimal(nombre, precision){
                    var precision = precision || 2;
                    var tmp = Math.pow(10, precision);
                    return Math.round( nombre*tmp )/tmp;
                }
                function calcul() {
                    let baliseprixloc = document.getElementById("tarif_loc")
                    let prix_loc = baliseprixloc.value
                    let baliseprixcharges = document.getElementById("charges")
                    let prix_charges = baliseprixcharges.value
                    let html = "";
                    total_HT = parseInt(prix_loc) + parseInt(prix_charges);
                    total_TTC = roundDecimal(total_HT * 1.1,2)
                    taxe_sejour = 120;
                    total_montant_devis = roundDecimal(total_TTC + taxe_sejour,2)
                    total_plateforme_HT = roundDecimal(total_montant_devis*1.01,2)
                    total_plateforme_TTC = roundDecimal(total_plateforme_HT * 1.2,2)
                    html += `<div class="deviscol">`;
                    html += `<div class="devisrow">`;
                    html += `<p class="ren">Calculer automatiquement</p>`;
                    html += `<div class="deviscolinput"><p> Total HT (en € ) </p><input class="logvct" id="totalht" name="totalht" value="${total_HT}€" disabled></div>`;
                    html += `<div class="deviscolinput"><p> Total TTC (en € ) </p><input class="logvct" id="totalht" name="totalht" value="${total_TTC}€" disabled></div>`;
                    html += `<div class="deviscolinput"><p> Taxe de séjour (en € ) </p><input class="logvct" id="totalht" name="totalht" value="${taxe_sejour}€" disabled></div>`;
                    html += '</div>';
                    html += `<div class="devisrow">`;
                    html += `<div class="devisvct"><p> Montant total du devis</p><input class="logvct" id="totalht" name="totalht" value="${total_montant_devis}€"  disabled></div>`;
                    html += `<div class="devisvct"><p> Frais de plateforme HT</p><input class="logvct" id="totalht" name="totalht" value="${total_plateforme_HT}€" disabled></div>`;
                    html += `<div class="devisvct"><p> Frais de plateforme TT</p><input class="logvct" id="totalht" name="totalht" value="${total_plateforme_TTC} €" disabled></div>`;
                    html += '</div>';
                    html += '</div>';
                    document.getElementById("resultat").innerHTML = html;
                    document.getElementById("envoyerDevisBtn").removeAttribute("disabled");
                }
            </script>
            <br/>
            <input type="hidden" id="id_demande" name ="id_demande" value=<?php echo $_GET['demande'];?>>
            </fieldset>
            <input class="btn-envoidevis" type="submit" id="envoyerDevisBtn" value="Envoyer le devis" disabled />
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
