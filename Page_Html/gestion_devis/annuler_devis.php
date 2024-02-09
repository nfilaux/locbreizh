<?php
    // Début de la session
    session_start();

    // Inclusion d'une instance PDO
    include('../parametre_connexion.php');

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

    // On change le statut du devis
    $stmt = $dbh->prepare("UPDATE locbreizh._devis 
    set annule = TRUE 
    where num_devis = {$_POST['id_devis']};");
    $stmt->execute();

    // cherche le logement associé au devis
    $stmt = $dbh->prepare("SELECT id_logement, d.date_arrivee, d.date_depart from locbreizh._devis d
    join locbreizh._demande_devis dd on dd.num_demande_devis = d.num_demande_devis
    join locbreizh._logement l on l.id_logement = dd.logement
    where d.num_devis = {$_POST['id_devis']};");
    $stmt->execute();
    $logement = $stmt->fetch();

    $code = $dbh->prepare("SELECT code_planning FROM locbreizh._logement WHERE id_logement = :id_logement;");
    $code->bindParam(':id_logement', $logement['id_logement']);
    $code->execute();
    $variable = $code->fetch();

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

        $stmt = $dbh->prepare("SELECT d.prix_plage_ponctuelle, d.id_plage_ponctuelle FROM locbreizh._plage_ponctuelle_indisponible d JOIN locbreizh._plage_ponctuelle p
        ON d.id_plage_ponctuelle = p.id_plage_ponctuelle WHERE jour_plage_ponctuelle =  :jour_plage_ponctuelle AND code_planning = :code_planning;");
        $stmt->bindParam(':jour_plage_ponctuelle', $date_formate);
        $stmt->bindParam(':code_planning', $variable['code_planning']);
        $stmt->execute();
        $prix_jour = $stmt->fetch();

        $stmt = $dbh->prepare("DELETE FROM locbreizh._plage_ponctuelle WHERE id_plage_ponctuelle = :id_plage_ponctuelle;");
        $stmt->bindParam(':id_plage_ponctuelle', $id_jour);
        $stmt->execute();

        $stmt = $dbh->prepare("INSERT INTO locbreizh._plage_ponctuelle(jour_plage_ponctuelle, code_planning)
        VALUES (:jour_plage_ponctuelle, :code_planning);");
        $stmt->bindParam(':jour_plage_ponctuelle', $date_formate);
        $stmt->bindParam(':code_planning', $variable['code_planning']);
        $stmt->execute();
        $stmt = $dbh->prepare("SELECT currval('locbreizh._plage_ponctuelle_id_plage_ponctuelle_seq');");
        $stmt->execute();
        $id_plage = $stmt->fetchColumn();
 
        $stmt = $dbh->prepare("INSERT INTO locbreizh._plage_ponctuelle_disponible(id_plage_ponctuelle, prix_plage_ponctuelle)
        VALUES (:id_plage_ponctuelle, :prix_plage_ponctuelle);");
        $stmt->bindParam(':id_plage_ponctuelle', $id_plage);
        $stmt->bindParam(':prix_plage_ponctuelle', $prix_jour['prix_plage_ponctuelle']);
        $stmt->execute();

        $stmt = $dbh->prepare("DELETE FROM locbreizh._plage_ponctuelle WHERE id_plage_ponctuelle = :id_plage_ponctuelle;");
        $stmt->bindParam(':id_plage_ponctuelle', $prix_jour['id_plage_ponctuelle']);
        $stmt->execute();
    }

    header("Location: gestion_des_devis_proprio.php")
?>