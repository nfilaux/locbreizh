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
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
    <script src="../scriptPopup.js"></script>
</head>

<body class="pagecompte">
    <?php 
        include('../header-footer/choose_header.php');
    ?>
    <main class="MainTablo">
        <div class="headtablo"> 
            <h1>Mon tableau de bord</h1>
        </div>
        <section class="Tablobord">
            <article class="width">
                <h2>Mes logements</h2>
                <?php
                    
                    $stmt = $dbh->prepare(
                        "SELECT photo_principale, libelle_logement, tarif_base_ht, nb_personnes_logement, id_logement
                        from locbreizh._logement where id_proprietaire = {$pid};"
                    );

                    function formatDate($start, $end)
                {
                    $startDate = date('j', strtotime($start));
                    $endDate = date('j', strtotime($end));
                    $month = date('M', strtotime($end));

                    return "$startDate-$endDate $month";
                }
                    return "$startDate-$endDate $month";
                }

                $stmt->execute();
                foreach ($stmt->fetchAll() as $card) {
                    ?>
                        <div class="cardlogmain">
                            <img src="../Ressources/Images/<?php echo $card['photo_principale']?>">
                            <section class="logcp">
                                <div class="logrowb">
                                    <div>
                                        <h3 class="titrecard"><?php echo $card['libelle_logement'] ?></h3>
                                        <hr class="hrcard">
                                    </div>
                                    <a class="btn-modiftst" href="../Logement/modifierLogement.php?id_logement=<?php echo $card['id_logement'] ?>"><button class="btn-modif">Modifier</button></a>
                                </div>
                                
                                <div class="logrowb">
                                    <a href="../Logement/logement_detaille_proprio.php?logement=<?php echo $card['id_logement'] ?>"><button class="btn-ajoutlog">CONSULTER</button></a>
                                    <a><button class="btn-desactive">DESACTIVER</button></a>
                                    <a><button class="btn-suppr">SUPPRIMER</button></a>
                                </div>
                                
                                <p>DISCLAIMER - La suppression du compte est définitve.</p>
                                <p class="err">Condition requise : Aucune réservation prévue.</p>

                                <?php $nomPlage = 'plage' . $key; 
                                $overlayPlage = 'overlay' . $key;?>

                                <a class="calend" onclick="openPopup('<?php echo $nomPlage; ?>', '<?php echo $overlayPlage; ?>')"><img src="../svg/calendar.svg" alt="Gérer calendrier" title="Calendrier"></a>    

                                <div class="overlay_plages" id='<?php echo $overlayPlage; ?>' onclick="closePopup('<?php echo $nomPlage; ?>', '<?php echo $overlayPlage; ?>')"></div>
                                    <div id="<?php echo $nomPlage; ?>" class='plages'> 
                                        <h1>Ajouter une plage ponctuelle</h1><br>
                                        <form action="../Planning/plageBack.php" method="post">
                                            
                                            <label for="debut_plage_ponctuelle"> date de début de la plage : </label>
                                            <input type="date" id="debut_plage_ponctuelle" name="dateDeb"/>
                                            <br><br>
                                            
                                            <label for="fin_plage_ponctuelle"> date de fin de la plage : </label>
                                            <input type="date" id="fin_plage_ponctuelle" name="dateFin"/>
                                            <br><br>

                                            <label for="prix_plage_ponctuelle"> Prix : </label>
                                            <input type="text" id="prix_plage_ponctuelle" name="prix" placeholder="<?php echo $card['tarif_base_ht'] ?>"/>
                                            <br><br>

                                            <label for="disponible"> Disponible : </label>
                                            <input type="checkbox" id="disponible" name="disponible" value="true"/>
                                            <br><br>

                                            <input type="hidden" name="id_logement" value="<?php echo $card['id_logement'] ?>"/>
                                            <button type="submit">ajouter</button>
                                            
                                        </form>
                                        
                                        <hr><h1>Les plages ponctuelles</h1><br>
                                        <p> Aucune plage définie </p>
                                    </div>

                            </section>
                        </div>
                    <?php
                }
                ?>
            <a href="../Logement/remplir_formulaire.php"><button class="btn-ajoutlog" >AJOUTER UN LOGEMENT</button></a>
            </article>

            <hr class="hr">

            <article>
                <h2>Notifications</h2>

                <div class="box">
                    <p>Aucune notifications</p>
                    <?php //foreach ($notifications as $notification) {?>

                    <?php //} ?>
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
    
    <?php 
        echo file_get_contents('../header-footer/footer.html');
    ?>
</body>

</html>


<script src="../scriptPopup.js"></script>