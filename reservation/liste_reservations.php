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
            <img src="svg//logo.svg">
            <h2 style="margin-top: auto; margin-bottom: auto; margin-left: 10px;">Loc'Breizh</h2>
        </div>

        <div class="row col-3">
            <img class="col-2" src="svg//filtre.svg">
            <input class="col-7" id="searchbar" type="text" name="search" style="height: 50px; margin-top: auto; margin-bottom: auto;">
            <img class="col-2" src="svg//loupe.svg">
        </div>

        <div class="row col-5 offset-md-1">
            <a class="col-4 offset-md-3 row btn-accueilins"><h5 style="margin-top: auto; margin-bottom: auto;">S'inscrire</h5></a>
            <a class="col-4 offset-md-1 row btn-accueil"><h5 style="margin-top: auto; margin-bottom: auto;">Se connecter</h5></a>
        </div>
    </header>
    <main>

        <div class="offset-2 col-8">
            <?php
                try {
                    include('connect_params.php');

                    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $password);
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                    $stmt = $dbh->prepare(
                        'SELECT photo_principale, libelle_logement, tarif_base_ht, nb_personnes_logement, note_avis, debut_plage_ponctuelle, fin_plage_ponctuelle
                        from locbreizh._logement 
                            INNER JOIN locbreizh._avis ON logement = id_logement
                            INNER JOIN locbreizh._planning ON _planning.code_planning = _logement.code_planning
                            INNER JOIN locbreizh._plage_ponctuelle ON _planning.code_planning = _plage_ponctuelle.code_planning;'
                    );
                } catch (PDOException $e) {
                    print "Erreur !:" . $e->getMessage() . "<br/>";
                    die();
                }

                function formatDate($start, $end)
                {
                    $startDate = date('j', strtotime($start));
                    $endDate = date('j', strtotime($end));
                    $month = date('M', strtotime($end));

                    return "$startDate-$endDate $month";
                }
                $stmt->execute();
                $lst_card = $stmt->fetchAll();

                
                foreach ($lst_card as $card) {
                    $stmt = $dbh->prepare("select nom, prenom, photo from locbreizh._reservation r 
                    join locbreizh._logement l on l.id_logement = r.logement
                    join locbreizh._compte c on c.id_proprietaire = l.id_proprietaire;");
                    $stmt->execute();
                    $info_proprio = $stmt->fetch();
                    ?>
                    <div class="card">        
                        <img src="<?php $card['../photos/photo_principale'] ?>">
                        <h3> <?php $card['???'] ?> </h3>
                        <div>
                            <p>Par <?php echo $info_proprio['prenom'] . ' ' . $info_proprio['nom'];?></p>
                            <img src=<?php echo 'photos/' . $info_proprio['photo']; ?> alt="photo de profil">
                            <button>Contacter le proprietaire</button>
                        </div>
                        <button class="btn-accueil">CONSULTER DEVIS</button>
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
                <p class="testfoot offset-md-1 col-2"><a href="connexion.html"><img src="svg/instagram.svg">  @LocBreizh</a></p>
                <p class="testfoot offset-md-1 col-2  "><a href="connexion.html"><img src="svg/facebook.svg">  @LocBreizh</a></p>
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