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
</head>
<?php

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

    <?php 
        include('../header-footer/choose_header.php');
    ?>

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
    <?php
        echo file_get_contents('../header-footer/footer.html');
    ?>
</body>

</html>

