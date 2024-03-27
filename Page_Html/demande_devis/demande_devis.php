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
    <script src="../scriptPopup.js"></script>
    <script src="../scriptPopupFeedback.js"></script>
</head>
<?php

    // recupere photo de profil pour le header
    $stmt = $dbh->prepare("SELECT * from locbreizh._compte join locbreizh._photo on locbreizh._compte.photo = locbreizh._photo.url_photo where locbreizh._compte.id_compte = '{$_SESSION['id']}';");
    $stmt->execute();
    $photo_profil = $stmt->fetch();

    // Initialisation avec une valeur par défaut
    $nb_max = array('nb_pers' => 10, 'en_ligne' => 0);

    // recupere le nombre maximum de personnes pour le logement
    if(isset($_GET['logement']) && !empty($_GET['logement'])) {
        $logementId = $_GET['logement'];
        $stmt = $dbh->prepare("SELECT nb_personnes_logement as nb_pers, en_ligne from locbreizh._logement where id_logement = :logementId");
        $stmt->bindParam(':logementId', $logementId, PDO::PARAM_INT);
        $stmt->execute();
        $nb_max = $stmt->fetch();
    }
    //$stmt = $dbh->prepare("SELECT nb_personnes_logement as nb_pers, en_ligne from locbreizh._logement where id_logement = {$_GET['logement']};");
    //$stmt->execute();
    //$nb_max = $stmt->fetch();

   
?>
<body>

    <?php 
        include('../header-footer/choose_header.php');
    ?>

    <main class="MainTablo">
    <div class="headtablo">
        <a href="../Logement/logement_detaille_client.php?logement=<?php echo $_GET['logement']; ?>"><img src="../svg/flecheRetour.svg" alt="fleche de retour"/></a>
            <h1>Faire ma demande de devis</h1>
    </div>
 
        <form name="envoie_demande_devis" method="post" action="envoyer_demande.php" enctype="multipart/form-data">
            <div class="logrow">
                <div class="datedevis">
                <div class="demdevis">  
                    <label for="dateArrivee">Date d’arrivée :</label>
                    <input class="logvctC" type="date" id="dateArrivee" name="dateArrivee" value="<?php if(isset($_POST['arrive'])) { echo htmlentities($_POST['arrive']);}?>" required/>
                </div>
                <div class="demdevis">  
                    <label for="dateDepart">Date de depart :</label>
                    <input class="logvctC" type="date" id="dateDepart" name="dateDepart" value="<?php if(isset($_POST['depart'])) { echo htmlentities($_POST['depart']);}?>" required/>
                </div>
                </div>
                <div class="log5vct">  
                    <label for="nb_pers">Nombre de persones :</label>
                    <!--appel php pour set la max value de nb personne par rapport au choix du proprio-->
                    <input class="logvctC" type="number" id="nb_pers" name="nb_pers" min="1" max=<?php echo $nb_max['nb_pers']; ?> value=<?php if(isset($_GET['nb_pers']) && $_GET['nb_pers'] > 1){echo $_GET['nb_pers'];} else{echo 1;} ?> required/>
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
                <input class="lognbC" type="number" id="nb_pers_supp" name="nb_pers_supp" min="0" max="50"  value=<?php if(isset($_GET['nb_supp']) && $_GET['nb_supp'] > 0){echo $_GET['nb_supp'];} else{echo 0;} ?> required/>
                </div>
            </div>
            <input type="hidden" name="logement" value="<?php echo $_GET['logement']; ?>">
            </div>
            <div class="devis">
                <?php
                if(isset($_GET['erreur'])){
                    if($_GET['erreur'] == 2){
                        echo '<p class="err">La date ne peut pas être utlérieure à celle d\'aujourd\'hui !</p>';
                    }
                    if($_GET['erreur'] == 1){
                        echo '<p class="err">La date de départ ne doit pas être utlérieure à la date d\'arrivée !</p>';
                    }
                    if($_GET['erreur'] == 3){
                        echo '<p class="err">Certains jours du planning ne sont pas disponibles !</p>';
                    }
                    if($_GET['erreur'] == 4){
                        echo '<p class="err">Il faut au minimum une nuit pour réserver un logement !</p>';
                    }
                }
                    
                ?>
                <input class="btn-accueil" type="submit" value="Demander un devis"/>
            </div>
            
        </form>
        <div id="overlayDemandeDeDevis" onclick="closePopupFeedback('popupFeedback', 'overlayDemandeDeDevis')"></div>
        <div id="popupFeedback" class="popupFeedback">
            <p>Votre demande de devis a bien été envoyée !</p>
            <a href="../gestion_devis/gestion_des_devis_client.php" class="btn-accueil"></button>OK</a>
        </div>
    </main>
    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>
</html>

<?php
    if(isset($_GET['erreur']) && $_GET['erreur'] === '0'){
        ?>
        <script>
            openPopupFeedback('popupFeedback', 'overlayDemandeDeDevis');
        </script>
        <?php
    }
    
?>