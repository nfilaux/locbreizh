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
            "SELECT num_demande_devis, nb_personnes, date_arrivee, date_depart, url_detail, libelle_logement, photo_principale
            from locbreizh._demande_devis
            join locbreizh._logement l on l.id_logement =  logement
            WHERE client = {$_SESSION['id']} and accepte IS NOT TRUE;"
        );
        $stmt->execute();
        $list_demande = $stmt->fetchAll();

        $stmt = $dbh->prepare(
            "SELECT num_devis, nb_personnes, date_arrivee, date_depart, url_detail, libelle_logement, photo_principale
            from locbreizh._devis
            natural join locbreizh._demande_devis 
            join locbreizh._logement l on l.id_logement =  logement
            WHERE (client = {$_SESSION['id']} or id_proprietaire = {$_SESSION['id']}) and accepte IS NOT TRUE;"
        );
        $stmt->execute();
        $list_devis = $stmt->fetchAll();
    ?>
    <main>
        <!-- demande de devis -->
        <div>
            <?php 
                foreach($list_demande as $demande){?>
            <div>
                <h6>Demande de devis en cours</h6>
                <p><?php echo $demande['libelle_logement']; ?></p>
                <img src="<?php echo "../Ressources/Images/{$demande['photo_principale']}"; ?>" width="50" height="50">
                <p><?php echo $demande['date_arrivee'] . " - " . $demande['date_depart'] ; ?></p>
                <p>Nombre de personne : <?php echo $demande['nb_personnes'] ;?></p>
                <button>Accepter</button>
                <button>Refuser</button>
                <button>Annuler</button>
            </div>
            <?php } ?>
        </div>
        <!-- devis -->
        <div>
            <?php 
                foreach($list_devis as $devis){ ?>
            <div>
                <h6>Devis proposé</h6>
                <p><?php echo $devis['libelle_logement']; ?></p>
                <img src="<?php echo "../Ressources/Images/{$demande['photo_principale']}"; ?>" width="50" height="50">
                <p><?php echo $devis['date_arrivee'] . " - " . $devis['date_depart'] ; ?></p>
                <p>Nombre de personne : <?php echo $devis['nb_personnes'] ;?></p>
                <button>Accepter</button>
                <button>Refuser</button>
                <button>Annuler</button>
            </div>
            <?php } ?>
        </div>
    </main>
    <!-- footer -->
    <?php 
        echo file_get_contents('../header-footer/footer.html');
    ?>
</body>
</html>