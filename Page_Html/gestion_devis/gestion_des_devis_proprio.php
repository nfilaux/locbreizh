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
            "SELECT num_demande_devis, nb_personnes, date_arrivee, date_depart, url_detail, libelle_logement, photo_principale, accepte
            from locbreizh._demande_devis
            join locbreizh._logement l on l.id_logement =  logement
            WHERE (client = {$_SESSION['id']} or id_proprietaire = {$_SESSION['id']}) and accepte IS NOT TRUE;"
        );
        $stmt->execute();
        $list_demande = $stmt->fetchAll();

        $stmt = $dbh->prepare(
            "SELECT num_devis, nb_personnes, date_arrivee, date_depart, _devis.url_detail, libelle_logement, photo_principale, _devis.accepte, annule
            from locbreizh._devis
            join locbreizh._demande_devis on _devis.num_demande_devis = _demande_devis.num_demande_devis
            join locbreizh._logement l on l.id_logement =  logement
            WHERE _devis.client = {$_SESSION['id']} or id_proprietaire = {$_SESSION['id']};"
        );
        $stmt->execute();
        $list_devis = $stmt->fetchAll();
    ?>
    <main>
        <!-- demande de devis -->
        <div>
            <h6>Demande de devis des clients</h6>
            <?php 
                foreach($list_demande as $demande){?>
            <div>
                <p><?php echo $demande['libelle_logement']; ?></p>
                <img src="<?php echo "../Ressources/Images/{$demande['photo_principale']}"; ?>" width="50" height="50">
                <p><?php echo $demande['date_arrivee'] . " - " . $demande['date_depart'] ; ?></p>
                <p>Nombre de personne : <?php echo $demande['nb_personnes'] ;?></p>

                <?php 
                    if($demande['accepte'] === null){ ?>
                <form method="post" action="accepter_demande.php">
                    <input type="hidden" name="id_demande" id="id_demande" value="<?php echo $demande['num_demande_devis']; ?>">
                    <button type="submit">Accepter</button>
                </form>
                <form method="post" action="refuser_demande.php">
                    <input type="hidden" name="id_demande" id="id_demande" value="<?php echo $demande['num_demande_devis']; ?>">
                    <button type="submit">Refuser</button>
                </form>
                <?php } 
                    else if($demande['accepte'] === false){ ?>
                        <p>Vous avez refusé cette demande de devis.</p>
                    <?php }
                ?>
            </div>
            <?php } ?>
        </div>
        <hr class="hrP">
        <!-- devis -->
        <div>
            <h6>Vos proposition de devis</h6>
            <?php 
                foreach($list_devis as $devis){ ?>
            <div>
                <p><?php echo $devis['libelle_logement']; ?></p>
                <img src="<?php echo "../Ressources/Images/{$devis['photo_principale']}"; ?>" width="50" height="50">
                <p><?php echo $devis['date_arrivee'] . " - " . $devis['date_depart'] ; ?></p>
                <p>Nombre de personne : <?php echo $devis['nb_personnes'] ;?></p>
                <?php 
                    if($devis['annule']){ ?>
                        <p>Vous avez annulé ce devis.</p>
                    <?php }
                    else if($devis['accepte']){ ?>
                        <p>Le client à accepter le devis vous pouvez consulter la reservation dans <a href="../reservation/liste_reservations_proprio.php">"Mon tableau de bord".</a></p>
                    <?php }
                    else if($devis['accepte'] === false){ ?>
                        <p>Le client à refuser le devis.</p>
                    <?php }
                    else{ ?>
                        <p>La demande n'a pas encore été traité par le client.</p>
                        <form method="post" action="annuler_devis.php">
                            <input type="hidden" name="id_devis" id="id_devis" value="<?php echo $devis['num_devis']; ?>">
                            <button type="submit">Annuler</button>
                        </form>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </main>
    <!-- footer -->
    <?php
    // appel du footer
    include('../header-footer/choose_footer.php'); ?>
</body>
</html>