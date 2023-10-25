<?php
    // lancement de la session
    session_start(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande devis</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    $stmt = $dbh->prepare("SELECT nb_personnes_logement as nb_pers from locbreizh._logement where id_logement = {$_GET['logement']};");
    $stmt->execute();
    $nb_max = $stmt->fetch();
?>
<body>
    <header class="row col-12">
            <div class="row col-3">
                <img src="../svg//logo.svg">
                <h2 style="margin-top: auto; margin-bottom: auto; margin-left: 10px;">Loc'Breizh</h2>
            </div>

            <div class="row col-3">
                <img class="col-2" src="../svg//filtre.svg">
                <input class="col-7" id="searchbar" type="text" name="search" style="height: 50px; margin-top: auto; margin-bottom: auto;">
                <img class="col-2" src="../svg//loupe.svg">
            </div>
                <div class="row col-3 offset-md-1">
                    <img src="../svg//booklet-fill 1.svg">
                    <a href="../reservation/liste_reservations.php" style="margin: auto;margin-left: 10px;"><h4 style="color:#000;">Accèder à mes réservations</h4></a>
                </div>
                

            <div class="col-2 row">
                <a href="../messagerie/messagerie.php" class="offset-md-6 row"><img src="../svg/message.svg"></a>
                <a href="../Compte/compte-client.php" class="offset-md-2 row"><img src="../svg/compte.svg"></a> 
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
    <footer class="mt-4 container-fluid">
        <div class="mt-4 column">   
            <div class="col-12 text-center">
                <a class="col-2" href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a>
                <a class="offset-md-1 col-2" href="tel:+33623455689">(+33) 6 23 45 56 89</a>
                <a class="offset-md-1 col-1" href="connexion.html"><img src="../svg/instagram.svg">  @LocBreizh</a>
                <a class="offset-md-1 col-1" href="connexion.html"><img src="../svg/facebook.svg">  @LocBreizh</a>
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

