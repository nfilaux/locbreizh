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
    $stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = {$_SESSION['id']};");
    $stmt->execute();
    $photo = $stmt->fetch();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            <img src="../svg/booklet-fill 1.svg">
            <a href="../Accueil/Tableau_de_bord.php" style="margin: auto;margin-left: 10px;">
                <h4 style="color:#000;">Accèder à mon tableau de bord</h4>
            </a>
        </div>
        <div class="col-2 row">
            <a class="offset-md-6 row" href="../messagerie/messagerie.php"><img src="../svg/message.svg"></a>
            <a class="offset-md-2 row"><img id="pp" src="../Ressources/Images/<?php echo $photo['photo']; ?>"></a>
        </div>
    </header>
    <main>

        <div class="offset-1 col-5">
            <h2>Mes logements</h2>
            <?php
                
                $stmt = $dbh->prepare(
                    "SELECT photo_principale, libelle_logement, tarif_base_ht, nb_personnes_logement, id_logement
                    from locbreizh._logement where id_proprietaire = {$_SESSION['id']};"
                );

                function formatDate($start, $end)
            {
                $startDate = date('j', strtotime($start));
                $endDate = date('j', strtotime($end));
                $month = date('M', strtotime($end));

                return "$startDate-$endDate $month";
            }

            $stmt->execute();
            foreach ($stmt->fetchAll() as $card) {
                echo "<a href=\"../Logement/logement_detaille_proprio.php?logement={$card['id_logement']}\"><div class=\"card\">";
                echo '<img src="../Ressources/Images/' . $card['photo_principale'] . '">';
                echo '<h3>' . $card['libelle_logement'] . '</h3>';
                echo '<h4>' . $card['tarif_base_ht'] . '€</h4>';
                /*echo '<img src="/Ressources/Images/star.svg"> . <h4>' . $card['note_avis'] . '</h4>';*/
                /*
                echo '<h4>' . formatDate($card['debut_plage_ponctuelle'], $card['fin_plage_ponctuelle']) . '</h4>';*/
                echo '<h4>' . $card['nb_personnes_logement'] . ' personnes</h4>';
                echo "<a href=\"../Logement/modifierLogement.php?id_logement={$card['id_logement']}\"><button>Modifier ce logement</button></a></div></a>";
            }
            ?>
        <a href="../Logement/remplir_formulaire.php"><button class="btn-accueil" >AJOUTER UN LOGEMENT</button></a>
        </div>

        
        <div>
            <h2>Notifications</h2>
        </div>



        <div class="offset-1 col-5">
            <h2>Mes Réservation</h2>
            <?php
        
            $stmt = $dbh->prepare("SELECT l.photo_principale, ville, code_postal, f.url_facture, l.id_logement, nom, prenom, c.photo
            from locbreizh._reservation r
            join locbreizh._logement l on l.id_logement = r.logement
            join locbreizh._proprietaire p on l.id_proprietaire = p.id_proprietaire
            join locbreizh._compte c on c.id_compte = p.id_proprietaire
            join locbreizh._adresse a on l.id_adresse = a.id_adresse
            join locbreizh._facture f on f.num_facture = r.facture
            join locbreizh._devis d on d.num_devis = f.num_devis");
            $stmt->execute();
            $reservations = $stmt->fetchAll();

            foreach ($reservations as $reservation) {

                ?>
                <div class="card">        
                    <img src="../Ressources/Images/<?php echo $reservation['photo_principale']; ?>">
                    <h3> <?php echo $reservation['ville'] . ', ' . $reservation['code_postal'] ?> </h3>
                    <div>
                        <p>Par <?php echo $reservation['nom'] . ' ' . $reservation['prenom'];?></p>
                        <img src=<?php echo '../Ressources/Images/' . $reservation['photo']; ?> alt="photo de profil">
                        <button disabled>Contacter le proprietaire</button>
                    </div>
                    <a href="../devis/pdf_devis/"><button class="btn-accueil" disabled>CONSULTER DEVIS</button></a>
                    <a href="../Logement/logement_detaille_client.php?logement=<?php echo $reservation['id_logement'];?>"><button class="btn-accueilins">CONSULTER LOGEMENT</button></a>

                    <a><button class="btn-accueil" disabled>ANNULER</button></a>
                    <p>DISCLAIMER - L’annulation est définitve et irréversible.</p>
                </div>
            <?php } ?>
    </div>
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