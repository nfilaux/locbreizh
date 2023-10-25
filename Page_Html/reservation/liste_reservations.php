<?php session_start();?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="script.js"></script>
</head>

<body>
<header class="row col-12">
        <div class="row col-3">
            <img src="../svg//logo.svg">
            <h2 style="margin-top: auto; margin-bottom: auto; margin-left: 10px;">Loc'Breizh</h2>
        </div>

        <div class="row col-3">
            <img class="col-2" src="../svg//filtre.svg">
            <input class="col-7" id="searchbar" type="text" name="search"
                style="height: 50px; margin-top: auto; margin-bottom: auto;">
            <img class="col-2" src="../svg//loupe.svg">
        </div>
        <div class="row col-3 offset-md-1">
            <img src="../svg//booklet-fill 1.svg">
            <a href="logement.php" style="margin: auto;margin-left: 10px;">
                <h4 style="color:#000;">Accèder à mes réservations</h4>
            </a>
        </div>


        <div class="col-2 row">
            <a class="offset-md-6 row"><img src="../svg/message.svg"></a>
            <a class="offset-md-2 row"><img src="../svg/compte.svg"></a>
        </div>
    </header>
    <main>

        <div class="offset-2 col-8">
            <?php
            include('../parametre_connexion.php');
                try {
                    

                    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    print "Erreur !:" . $e->getMessage() . "<br/>";
                    die();
                } 

                
                $stmt = $dbh->prepare("SELECT l.photo_principale, ville, code_postal, f.url_facture, l.id_logement, nom, prenom, c.photo
                from locbreizh._reservation r
                join locbreizh._logement l on l.id_logement = r.logement
                join locbreizh._proprietaire p on l.id_proprietaire = p.id_proprietaire
                join locbreizh._compte c on c.id_compte = p.id_proprietaire
                join locbreizh._adresse a on l.id_adresse = a.id_adresse
                join locbreizh._facture f on f.num_facture = r.facture");
                $stmt->execute();
                $reservations = $stmt->fetchAll();

                foreach ($reservations as $reservation) {
                    ?>
                    <div class="card">        
                        <img src="<?php echo $reservation['photo_principale']; ?>">
                        <h3> <?php echo $reservation['ville'] . ', ' . $reservation['code_postal'] ?> </h3>
                        <div>
                            <p>Par <?php echo $reservation['nom'] . ' ' . $reservation['prenom'];?></p>
                            <img src=<?php echo 'Ressources/Images/' . $reservation['photo']; ?> alt="photo de profil">
                            <button disabled>Contacter le proprietaire</button>
                        </div>
                        <button class="btn-accueil" disabled>CONSULTER DEVIS</button>
                        <button class="btn-accueilins">CONSULTER LOGEMENT</button>
                        <button disabled>ANNULER</button>
                        <p>DISCLAIMER - L’annulation est définitve et irréversible.</p>
                    </div>
                <?php } ?>
            
        </div>
        <div class='voir_plus'>
            <hr>
            <h4>Voir plus</h4>
            <hr>
        </div>
    </main>
    <footer class="container-fluid" >
        <div class="column">   
            <div class="text-center row">
                <p class="testfoot col-2"><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
                <p class="testfoot offset-md-2 col-2"><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
                <p class="testfoot offset-md-1 col-2"><a href="connexion.html"><img src="../svg/instagram.svg">  @LocBreizh</a></p>
                <p class="testfoot offset-md-1 col-2  "><a href="connexion.html"><img src="../svg/facebook.svg">  @LocBreizh</a></p>
            </div>
            <hr>  
            <div class="text-center row">
                <p class="offset-md-1 col-2 testfooter">©2023 Loc’Breizh</p>
                <p class="offset-md-1 col-3 testfooter" style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p class="offset-md-1 col-4 testfooter" >Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
        </div>
    </footer>
</body>

</html>