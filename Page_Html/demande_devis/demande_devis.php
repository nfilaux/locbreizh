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

    // On recupere l'id du compte
    $stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = {$_SESSION['id']};");
    $stmt->execute();
    $photo = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande devis</title>
    <link rel="stylesheet" href="../style.css">
</head>
<?php
    // import parametre de connexion + nouvelle instance de PDO
    include('../parametre_connexion.php');

    // id fictif pour les tests
    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

    // recupere photo de profil pour le header
    $stmt = $dbh->prepare("SELECT * from locbreizh._compte join locbreizh._photo on locbreizh._compte.photo = locbreizh._photo.url_photo where locbreizh._compte.id_compte = '{$_SESSION['id']}';");
    $stmt->execute();
    $photo_profil = $stmt->fetch();

    // recupere le nombre maximum de personnes pour le logement
    $stmt = $dbh->prepare("SELECT nb_personnes_logement as nb_pers, en_ligne from locbreizh._logement where id_logement = {$_GET['logement']};");
    $stmt->execute();
    $nb_max = $stmt->fetch();

   
?>
<body>
<header>
	<a href="../Accueil/accueil_client.php">
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
            <a href="../reservation/liste_reservations.php">
                <h4>Accèder à mes reservations</h4>
            </a>
        </div>

<<<<<<< HEAD
        <div class="imghead">
            <a href="../messagerie/messagerie.php"><img src="../svg/message.svg"></a>
            <a onclick="openPopup()"><img id="pp" class="imgprofil" src="../Ressources/Images/<?php echo $photo['photo']; ?>" width="50" height="50"></a>
        </div>
        <div id="overlay" onclick="closePopup()"></div>
        <div id="popup" class="popup">
            <table id="tableProfil">
                <tr>
                    <td>
                        <a id="monprofil" href="">Accéder au profil</a>
                    </td>
                </tr>
                <tr>
                    <td> 
                        <a id="deconnexion" href="../Compte/SeDeconnecter.php">Se déconnecter</a>
                    </td>  
                </tr>
            </table>
        </div>
</header>
=======
        <img src="../svg/booklet-fill 1.svg">
        <a href="../reservation/liste_reservations.php"><h4>Accèder à mes réservations</h4></a>
>>>>>>> 12f720d3f530ccbb0ad87582d473241b48ef8ce5

        <div class="imghead">
            <a href="../messagerie/messagerie.php" ><img src="../svg/message.svg"></a>
            <a onclick="openPopup()"><img id="pp" class="imgprofil" src="../Ressources/Images/<?php echo $photo['photo']; ?>" width="50" height="50"></a> 
        </div>
        <div id="popup" class="popup">
            <a href="">Accéder au profil</a>
            <br>
            <a href="../Compte/SeDeconnecter.php">Se déconnecter</a>
            <a onclick="closePopup()">Fermer la fenêtre</a>
        </div>
    </header>

    <main class="MainTablo">
    <div class="headtablo">
        <a href="logement_detaille_client"><img src="../svg/flecheRetour.svg"/></a>
            <h1>Faire ma demande de devis</h1>
    </div>
 
        <form name="envoie_demande_devis" method="post" action="envoyer_demande.php" enctype="multipart/form-data">
            <div class="logrow">
                <div class="log5vct">  
                    <label for="dateArrivee">Date d’arrivée :</label>
                    <input class="logvct" type="date" id="dateArrivee" name="dateArrivee" required/>
                </div>
                <div class="log5vct">  
                    <label for="dateDepart">Date de depart :</label>
                    <input class="logvct" type="date" id="dateDepart" name="dateDepart" required/>
                </div>
                
                <div class="log5vct">  
                    <label for="nb_pers">Nombre de persones :</label>
                    <!--appel php pour set la max value de nb personne par rapport au choix du proprio-->
                    <input class="logvct" type="number" id="nb_pers" name="nb_pers" min="1" max=<?php echo $nb_max['nb_pers']; ?> value=<?php if(isset($_GET['nb_pers']) && $_GET['nb_pers'] > 1){echo $_GET['nb_pers'];} else{echo 1;} ?> required/>
                </div>
            
            <div class="cardSupplements">
            <h2 style="text-align:center;  font-family: 'Quicksand';">Suppléments</h2>
                <div class="logcheckbox">
                <!--pre-remplie les iinfos si ils sont dans get-->
                <input type="checkbox" id="animaux" name="animaux" <?php if(isset($_GET['animaux']) && $_GET['animaux'] === 'on'){echo 'checked';}; ?>/>
                <label for="animaux">Animaux</label>
                </div>
                <div class="logcheckbox">
                <!--pre-remplie les iinfos si ils sont dans get-->
                <input type="checkbox" id="menage" name="menage" <?php if(isset($_GET['menage']) && $_GET['menage'] === 'on'){echo 'checked';}; ?>/>
                <label for="menage">Menage</label>
                </div>
                <!--pre-remplie les iinfos si ils sont dans get-->
                <div class="logpc">
                <label style="text-align:center;" for="nb_pers_supp">Vacanciers supplémentaires</label>
                <input class="lognb" type="number" id="nb_pers_supp" name="nb_pers_supp" min="0" max="50"  value=<?php if(isset($_GET['nb_supp']) && $_GET['nb_supp'] > 0){echo $_GET['nb_supp'];} else{echo 0;} ?> required/>
                </div>
            </div>
            <input type="hidden" name="logement" value="<?php echo $_GET['logement']; ?>">
            </div>
            <div class="devis">
                <?php
                // Préviens des erreurs
                if(isset($_GET['erreur'])){
                    if($_GET['erreur'] == 2){
                        echo '<p class="err">La date ne peut pas être utlérieure à celle d\'aujourd\'hui !</p>';
                    }
                    if($_GET['erreur'] == 1){
                        echo '<p class="err">La date de départ ne doit pas être utlérieure à la date d\'arrivee !</p>';
                    }
                }
                if ($nb_max['en_ligne'] == false){
                    echo '<p class="err">Ce logement n\'est plus disponible !</p>';
                } else {
                    echo '<input class="btn-accueil" type="submit" value="Soumettre ma demande" /> ';
                }
                ?>
            
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

<<<<<<< HEAD
<script src="../scriptPopup.js"></script>
=======
<<<<<<< HEAD
<!-- Partie stylisé des popup-->
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

<!-- Partie animé du profil d'une personne connecter -->
=======
>>>>>>> 9364f17a90c80a56455381988de79fa473ad37bb
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
>>>>>>> 12f720d3f530ccbb0ad87582d473241b48ef8ce5
