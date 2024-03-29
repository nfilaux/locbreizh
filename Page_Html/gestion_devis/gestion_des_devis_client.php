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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des devis</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
</head>
<body>
    <?php 
        // appel du header
        include('../header-footer/choose_header.php');

        //requete pour récuperer les devis / demandes devis
        $stmt = $dbh->prepare(
            "SELECT num_demande_devis, _demande_devis.nb_personnes, date_arrivee, date_depart, url_detail, libelle_logement, photo_principale, accepte
            from locbreizh._demande_devis
            join locbreizh._logement l on l.id_logement =  logement
            WHERE client = {$_SESSION['id']} and accepte IS NOT TRUE and visibleC IS TRUE;"
        );
        $stmt->execute();
        $list_demande = $stmt->fetchAll();

        $stmt = $dbh->prepare(
            "SELECT num_devis, _devis.nb_personnes, _devis.date_arrivee, _devis.date_depart, _devis.url_detail, libelle_logement, photo_principale, _devis.accepte, annule
            from locbreizh._devis
            join locbreizh._demande_devis on _devis.num_demande_devis = _demande_devis.num_demande_devis
            join locbreizh._logement l on l.id_logement =  logement
            WHERE _devis.client = {$_SESSION['id']} and _devis.visibleC IS TRUE;"
        );
        $stmt->execute();
        $list_devis = $stmt->fetchAll();
    ?>
    <main class="MainTablo">
        <div class="headtablo"> 
            <h1>Mes Devis</h1>
        </div>
        <div class="gestion_devis">
        <!-- demande de devis -->
        <div class="partie_demande">
            <h1>Les demandes de devis envoyées</h1>
            <div>
            <?php 
            if($list_demande == []){ ?>
                <p>Aucune demande en cours !</p>
            <?php } 
                foreach($list_demande as $demande){?>
            <div class="card_devis">
            <button onclick="openPopup('<?php echo $demande['num_demande_devis']; ?>', '<?php echo 'ov' . $demande['num_demande_devis']; ?>')">X</button>

                <div>
                    <img class="img_devis" src="<?php echo "../Ressources/Images/{$demande['photo_principale']}"; ?>" width="50" height="50" alt="">
                    <div>
                        <div>
                            <h3 class="titrecard"><?php echo $demande['libelle_logement']; ?></h3>
                            <hr class="hrcard">
                        </div>
                        <p><?php echo "Du " . str_replace('-','/',$demande['date_arrivee']) . " au " . str_replace('-','/',$demande['date_depart']) ; ?></p>
                        <p>Nombre de personne : <?php echo $demande['nb_personnes'] ;?></p>
                        <a target="_blank" href="../demande_devis/pdf_demande/<?php echo $demande['url_detail']; ?>">Voir la demande de devis</a>
                    </div>
                </div>
                <?php 
                if($demande['accepte'] === FALSE){ ?>
                    <p class="reponse_devis">Le propriétaire a refusé la demande de devis.</p>
                <?php }
                else{ ?>
                    <p class="reponse_devis">Le propriétaire n'a pas encore traité cette demande.</p>
                <?php }
                ?>
            </div>
            <div class="overlay_plages" id='<?php echo 'ov' . $demande['num_demande_devis']; ?>' onclick="closePopup('<?php echo $demande['num_demande_devis']; ?>', '<?php echo 'ov' . $demande['num_demande_devis']; ?>')"></div>
                <div id="<?php echo $demande['num_demande_devis']; ?>" class="popup_devis"> 
                    <p>Êtes-vous certains de vouloir supprimer cet echange ?</p>
                    <p>(vous ne pourrez plus y accéder par la suite)</p>
                    <div>
                        <a href="fermer_demande_devis.php?id=<?php echo $demande['num_demande_devis']; ?>"><button class="btn_ferm_oui">OUI</button></a>
                        <button onclick="closePopup('<?php echo $demande['num_demande_devis']; ?>', '<?php echo 'ov' . $demande['num_demande_devis']; ?>')" class="btn_ferm_annulerP">ANNULER</button>
                    </div> 
                </div>
                <?php } ?>
                </div>
                </div>
        <hr class="hr">
        <!-- devis -->
        <div class="partie_devis">
            <h1>Devis proposés par les propriétaires</h1>
            <div>
                <?php 
                if($list_devis == []){ ?>
                    <p>Aucun devis en cours !</p>
                <?php } 
                    foreach($list_devis as $devis){ ?>
                <div class="card_devis">
                <button onclick="openPopup('<?php echo $devis['num_devis']; ?>', '<?php echo 'ov' . $devis['num_devis']; ?>')">X</button>
                    <div>
                        <img class="img_devis" src="<?php echo "../Ressources/Images/{$devis['photo_principale']}"; ?>" width="50" height="50">
                        <div >
                            <div>
                                <h3 class="titrecard"><?php echo $devis['libelle_logement']; ?></h3>
                                <hr class="hrcard">
                            </div>
                            <p><?php echo "Du " . str_replace('-','/',$devis['date_arrivee']) . " au " .  str_replace('-','/',$devis['date_depart']) ; ?></p>
                            <p>Nombre de personne : <?php echo $devis['nb_personnes'] ;?></p>
                            <a target="_blank" href="../devis/pdf_devis/<?php echo $devis['url_detail']; ?>">Voir les details du devis</a>
                        </div>
                    </div>
                    <?php 
                        if($devis['annule']){ ?>
                            <p class="reponse_devis">Le propriétaire a annulé le devis.</p>
                        <?php } 
                        else if($devis['accepte']){ ?>
                            <p class="reponse_devis">Vous avez accepté le devis, vous pouvez voir les details dans <a href="../reservation/liste_reservations.php">"Mes reservations".</a></p>
                        <?php }
                        else if($devis['accepte'] === FALSE){ ?>
                            <p class="reponse_devis">Vous avez refusé ce devis.</p>
                        <?php }
                        else{ ?>
                        <div class="bouton_devis">
                            <form method="post" action="accepter_devis.php">
                                <input type="hidden" name="id_devis" id="id_devis" value="<?php echo $devis['num_devis']; ?>">
                                <button id="devis_accepter" type="submit">ACCEPTER</button>
                            </form>
                            <form method="post" action="refuser_devis.php">
                                <input type="hidden" name="id_devis" id="id_devis" value="<?php echo $devis['num_devis']; ?>">
                                <button type="submit">REFUSER</button>
                            </form>
                        </div>
                        <?php } ?>
                </div>
                <div class="overlay_plages" id='<?php echo 'ov' . $devis['num_devis']; ?>' onclick="closePopup('<?php echo $devis['num_devis']; ?>', '<?php echo 'ov' . $devis['num_devis']; ?>')"></div>
                <div id="<?php echo $devis['num_devis']; ?>" class="popup_devis"> 
                    <p>Êtes-vous certains de vouloir supprimer cet echange ?</p>
                    <p>(vous ne pourrez plus y accéder par la suite)</p>
                    <div>
                        <a href="fermer_devis.php?id=<?php echo $devis['num_devis']; ?>"><button class="btn_ferm_oui">OUI</button></a>
                        <button onclick="closePopup('<?php echo $devis['num_devis']; ?>', '<?php echo 'ov' . $devis['num_devis']; ?>')" class="btn_ferm_annulerP">ANNULER</button>
                    </div> 
                </div>
             <?php } ?>
            </div>
        </div>
        </div>
    </main>
    <!-- footer -->
    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>
</html>

<script src="../scriptPopup.js" defer></script>