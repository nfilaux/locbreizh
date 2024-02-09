<?php
    // lancement de la session
    session_start();

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
    //ajout facture
    $stmt = $dbh->prepare("INSERT into locbreizh._facture(num_devis, url_facture) values({$_GET['devis']}, 'facture{$_GET['devis']}.pdf');");
    $stmt->execute();
    $id_facture = $dbh->lastInsertId();

    // cherche le logement associé au devis
    $stmt = $dbh->prepare("SELECT id_logement, id_proprietaire, d.date_arrivee, d.date_depart from locbreizh._devis d
    join locbreizh._demande_devis dd on dd.num_demande_devis = d.num_demande_devis
    join locbreizh._logement l on l.id_logement = dd.logement
    where d.num_devis = {$_GET['devis']};");
    $stmt->execute();
    $logement = $stmt->fetch();

    // change le statut du message du devis car il a été accepte
    $stmt = $dbh->prepare("UPDATE locbreizh._devis set accepte = TRUE where num_devis = {$_GET['devis']};");
    $stmt->execute();

    // ajout reservation
    $stmt = $dbh->prepare("INSERT into locbreizh._reservation(reservation_annulee, client, logement, facture) values(False, {$_SESSION['id']}, {$logement['id_logement']}, {$id_facture});");
    $stmt->execute();

    $code = $dbh->prepare("SELECT code_planning FROM locbreizh._logement WHERE id_logement = :id_logement;");
    $code->bindParam(':id_logement', $logement['id_logement']);
    $code->execute();
    $variable = $code->fetch();
    $raison = "Réservation";

    // Converti les dates donne en paramètre
    $date_arrive = new DateTime($logement['date_arrivee']);
    $date_depart = new DateTime($logement['date_depart']);

    // parcours tous les jours de la periode de reservation
    for($date = clone $date_arrive; $date <= $date_depart; $date->modify('+1 day')) {
        $date_formate = $date->format('Y-m-d');
        $stmt = $dbh->prepare("SELECT p.id_plage_ponctuelle FROM locbreizh._plage_ponctuelle p JOIN locbreizh._plage_ponctuelle_indisponible i
        ON p.id_plage_ponctuelle = i.id_plage_ponctuelle WHERE jour_plage_ponctuelle = :jour_plage_ponctuelle AND code_planning = :code_planning;");
        $stmt->bindParam(':jour_plage_ponctuelle', $date_formate);
        $stmt->bindParam(':code_planning', $variable['code_planning']);
        $stmt->execute();
        $id_jour = $stmt->fetchColumn();

        $stmt = $dbh->prepare("UPDATE locbreizh._plage_ponctuelle_indisponible SET libelle_indisponibilite = :libelle_indisponibilite
        WHERE id_plage_ponctuelle = :id_plage_ponctuelle;");
        $stmt->bindParam(':libelle_indisponibilite', $raison);
        $stmt->bindParam(':id_plage_ponctuelle', $id_jour);
        $stmt->execute();
    }

    header("Location: liste_reservations.php");
    
    ?>