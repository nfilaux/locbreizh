<script src="../scriptPopup.js"></script>
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
</head>

<body class="pagecompte">
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
            <a onclick="openPopup('popup', 'overlay_profil-deconnexion')"><img id="pp" class="imgprofil" src="../Ressources/Images/<?php echo $photo['photo']; ?>" width="50" height="50"></a>
        </div>
        <div id="overlay_profil-deconnexion" onclick="closePopup('popup', 'overlay_profil-deconnexion')"></div>
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
    <main class="MainTablo">
        <div class="headtablo"> 
            <h1>Mon tableau de bord</h1>
        </div>
        <section class="Tablobord">
            <article>
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
                foreach ($stmt->fetchAll() as $card) { ?>
                    <div class="tdblog"> 
                        <?php
                        echo "<a href=\"../Logement/logement_detaille_proprio.php?logement={$card['id_logement']}\"><div class=\"card\">";
                        echo '<img src="../Ressources/Images/' . $card['photo_principale'] . '">';
                        echo '<h3>' . $card['libelle_logement'] . '</h3>';
                        echo '<h4>' . $card['tarif_base_ht'] . '€</h4>';
                        /*echo '<img src="/Ressources/Images/star.svg"> . <h4>' . $card['note_avis'] . '</h4>';*/
                        /*
                        echo '<h4>' . formatDate($card['debut_plage_ponctuelle'], $card['fin_plage_ponctuelle']) . '</h4>';*/
                        echo '<h4>' . $card['nb_personnes_logement'] . ' personnes</h4>';
                        echo "<a href=\"../Logement/modifierLogement.php?id_logement={$card['id_logement']}\"><button>Modifier ce logement</button></a></div></a>";?>
                    
                        <a onclick="openPopup('plages', 'overlay_plages')"><img src="../svg/calendar.svg" alt="Gérer calendrier" title="Calendrier"></a>
                        
                        <div id="overlay_plages" onclick="closePopup('plages', 'overlay_plages')"></div>
                        <div id='plages' class='plages'> 
                            <h1>Plages ponctuelles<h1><br>
                            <form action="plagesBack.php" method="post">
                                
                                <label for="dateDeb"> date de début de la plage : </label>
                                <input type="date" id="dateDeb" name="dateDeb"/>
                                <br><br>
                                
                                <label for="dateFin"> date de fin de la plage : </label>
                                <input type="date" id="dateFin" name="dateFin"/>
                                <br><br>

                                <label for="prix"> Prix : </label>
                                <input type="text" id="prix" name="prix" placeholder="<?php echo $card['tarif_base_ht'] ?>"/>
                                <br><br>
            
                                <button type="submit">Sauvegarder</button>
                            </form>    
                        </div>

                    </div>
                    
                <?php
                }
                ?>
            <a href="../Logement/remplir_formulaire.php"><button class="btn-ajoutlog" >AJOUTER UN LOGEMENT</button></a>
            </article>

            <hr>

            <article>
                <h2>Notifications</h2>

                <div class="box">
                    <?php foreach ($notifications as $notification) {?>

                    <?php } ?>
                </div>


                <h2>Mes Réservation</h2>
                <p>Aucune réservation en cours </p>
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
            </article>
        </section>    
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

