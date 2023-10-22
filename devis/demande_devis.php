<?php
    // lancement de la session
    session_start(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<?php
    // import parametre de connexion + nouvelle instance de PDO
    include('../parametre_connexion.php');
    // id fictif pour les tests
    $_SESSION['id'] = 4;
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
    $stmt = $dbh->prepare("SELECT nb_personnes_logement as nb_pers from locbreizh._logement where id_logement = {$_GET['logement']};");
    $stmt->execute();
    $nb_max = $stmt->fetch();
?>
<body>
    <header>
        <nav>
            <div id="logo">
                <img src="../image/logo.svg">
                <p>Loc’Breizh</p>
            </div>
            <img src="../image/filtre.svg">
            <form name="formulaire" method="post" action="recherche.php" enctype="multipart/form-data">
                <input type="search" id="recherche" name="recherche" placeholder="Rechercher"><br>
                <input type="image" id="loupe" alt="loupe" src="../image/loupe.svg" />
            </form>
            <div>
                <img src="../image/reserv.svg">
                <a href="liste_reservations.html">Accéder à mes réservations</a>
            </div>
            <div id="parametre">
                <a href="messagerie.php"><img src="../image/messagerie.svg"></a>
                <a href="compte.php"><img src=<?php echo "../" . $photo_profil['url_photo']; ?>></a>
            <div>
        </nav>
    </header>
    <main>
        <h1>Faire ma demande de devis</h1>
        <!--formulaire avec methode post-->
        <form name="envoie_demande_devis" method="post" action="envoyer_demande.php" enctype="multipart/form-data">
            <div>
                <label for="dateArrivee">Date d’arrivée :</label>
                <input type="date" id="dateArrivee" name="dateArrivee" required/>

                <label for="dateDepart">Date de depart :</label>
                <input type="date" id="dateDepart" name="dateDepart" required/>
                <?php 
                    if (isset($_GET['erreur']) && $_GET['erreur'] == 1) {
                        echo '<p class="error-message">La date de départ est ultérieure à la date d\'arrivée !</p>';
                    } elseif (isset($_GET['erreur']) && $_GET['erreur'] == 2) {
                        echo '<p class="error-message">La date d\'arrivée ou la date de départ est ultérieure à la date d\'aujourd\'hui !</p>';
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
    <footer class="mt-4 container-fluid">
        <div class="mt-4 column">
            <div class="col-12 text-center">
                <a class="col-2" href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a>
                <a class="offset-md-1 col-2" href="tel:+33623455689">(+33) 6 23 45 56 89</a>
                <a class="offset-md-1 col-1" href="connexion.html"><img src="../image/instagram.svg">  @LocBreizh</a>
                <a class="offset-md-1 col-1" href="connexion.html"><img src="../image/facebook.svg">  @LocBreizh</a>
            </div>
            <hr>
            <div class="offset-md-1 col-10 mt-4 text-center row">
                <p class="offset-md-1 col-2">©2023 Loc’Breizh</p>
                <p class="offset-md-1 col-3" style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p class="offset-md-1 col-4" >Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
        </div>
    </footer>

</body>
</html>

