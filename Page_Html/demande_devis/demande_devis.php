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
$stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = :idCompte;");
$stmt->bindParam(':idCompte', $_SESSION['id']);
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
    $stmt = $dbh->prepare("SELECT * from locbreizh._compte join locbreizh._photo on locbreizh._compte.photo = locbreizh._photo.url_photo where locbreizh._compte.id_compte = :idCompte;");
    $stmt->bindParam(':idCompte', $_SESSION['id']);
    $stmt->execute();
    $photo_profil = $stmt->fetch();

    // recupere le nombre maximum de personnes pour le logement
    $stmt = $dbh->prepare("SELECT nb_personnes_logement as nb_pers from locbreizh._logement where id_logement = logement;");
    $stmt->bindParam(':logement', $_GET['logement']);
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
    <h1>Faire ma demande de devis</h1>
        <form name="envoie_demande_devis" method="post" action="envoyer_demande.php" enctype="multipart/form-data">
            <div>
                <label for="dateArrivee">Date d’arrivée :</label>
                <input type="date" id="dateArrivee" name="dateArrivee" required/>

                <label for="dateDepart">Date de depart :</label>
                <input type="date" id="dateDepart" name="dateDepart" required/>

                <?php
                if(isset($_GET['erreur'])){
                    if($_GET['erreur'] == 2){
                        echo '<p>La date ne peut pas être utlérieure à celle d\'aujourd\'hui !</p>';
                    }
                    if($_GET['erreur'] == 1){
                        echo '<p>La date de départ ne doit pas être utlérieure à la date d\'arrivee !</p>';
                    }
                }
                    
                ?>
                <label for="nb_pers">Nombre de persones :</label>
                <!--appel php pour set la max value de nb personne par rapport au choix du proprio-->
                <input type="number" id="nb_pers" name="nb_pers" min="1" max=<?php echo $nb_max['nb_pers']; ?> value=<?php if(isset($_GET['nb_pers']) && $_GET['nb_pers'] > 1){echo $_GET['nb_pers'];} else{echo 1;} ?> required/>
            </div>
            <h2>Suppléments</h2>
            <div>
                <!--pre-remplie les iinfos si ils sont dans get-->
                <input type="checkbox" id="animaux" name="animaux" <?php if(isset($_GET['animaux']) && $_GET['animaux'] === 'on'){echo 'checked';}; ?>/>
                <label for="animaux">Animaux :</label>

                <!--pre-remplie les iinfos si ils sont dans get-->
                <input type="checkbox" id="menage" name="menage" <?php if(isset($_GET['menage']) && $_GET['menage'] === 'on'){echo 'checked';}; ?>/>
                <label for="menage">Menage</label>

                <!--pre-remplie les iinfos si ils sont dans get-->
                <label for="nb_pers_supp">Nombre de personnes supplementaires :</label>
                <input type="number" id="nb_pers_supp" name="nb_pers_supp" min="0" max="50"  value=<?php if(isset($_GET['nb_supp']) && $_GET['nb_supp'] > 0){echo $_GET['nb_supp'];} else{echo 0;} ?> required/>
            </div>
            <input type="hidden" name="logement" value="<?php echo $_GET['logement']; ?>">
            <input type="submit" value="Soumettre ma demande" />
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
                <p>Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
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