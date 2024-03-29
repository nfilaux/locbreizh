<?php
    include('../../parametre_connexion.php');
    try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }

    // recupère le token à partir du nom du fichier
    $path = explode('/', __FILE__);
    $file_name = $path[array_key_last($path)];
    $token = explode('.', $file_name)[0];

    // recupère le proprio
    $stmt = $dbh->prepare("SELECT proprio from locbreizh._proprio_possede_token WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $proprio = $stmt->fetch();

    // recupère les paramètre du calendrier
    $stmt = $dbh->prepare("SELECT * from locbreizh._icalendar WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $params = $stmt->fetch();

    // recupère les infos de toutes les reservation sur la periode donnée si voulus
    if($params['reservations']){
        // TO DO -> choisir que infos necessaires
        $stmt = $dbh->prepare("SELECT date_arrivee, date_depart from locbreizh._reservation
        JOIN locbreizh._facture ON _facture.num_facture = _reservation.facture
        JOIN locbreizh._devis ON _devis.num_devis = _facture.num_devis
        JOIN locbreizh._logement ON _reservation.logement = _logement.id_logement
        JOIN locbreizh._client ON _client.id_client = _reservation.client
        WHERE _logement.id_proprietaire = :proprio 
        AND reservation_annulee = false
        AND _devis.date_depart > :date_debut
        AND _devis.date_arrivee < :date_fin");
        $stmt->bindParam(':proprio', $proprio['proprio']);
        $stmt->bindParam(':date_debut', $params['debut']);
        $stmt->bindParam(':date_fin', $params['fin']);
        $stmt->execute();
        $reservations = $stmt->fetchAll();
    } else{
        $reservations = [];
    }
    if($params['demandes']){
        $stmt = $dbh->prepare("SELECT _devis.date_arrivee, _devis.date_depart from locbreizh._devis
        JOIN locbreizh._demande_devis ON _demande_devis.num_demande_devis = _devis.num_demande_devis
        JOIN locbreizh._logement ON _demande_devis.logement = _logement.id_logement
        JOIN locbreizh._client ON _client.id_client = _devis.client
        WHERE _logement.id_proprietaire = :proprio 
        AND _devis.accepte IS NULL
        AND _devis.date_depart > :date_debut
        AND _devis.date_arrivee < :date_fin");
        $stmt->bindParam(':proprio', $proprio['proprio']);
        $stmt->bindParam(':date_debut', $params['debut']);
        $stmt->bindParam(':date_fin', $params['fin']);
        $stmt->execute();
        $demandes = $stmt->fetchAll();
    } else{
        $demandes = [];
    }
    if($params['indisponibilites']){
        $stmt = $dbh->prepare("SELECT _plage_ponctuelle.jour_plage_ponctuelle from locbreizh._logement
        JOIN locbreizh._planning ON _planning.code_planning = _logement.code_planning
        JOIN locbreizh._plage_ponctuelle ON _plage_ponctuelle.code_planning = _planning.code_planning
        WHERE _logement.id_proprietaire = :proprio 
        AND _plage_ponctuelle.jour_plage_ponctuelle >= :date_debut
        AND _plage_ponctuelle.jour_plage_ponctuelle <= :date_fin");
        $stmt->bindParam(':proprio', $proprio['proprio']);
        $stmt->bindParam(':date_debut', $params['debut']);
        $stmt->bindParam(':date_fin', $params['fin']);
        $stmt->execute();
        $plage_dispo = $stmt->fetchAll();

        $simple_dates = [];

        // on met les jours dans un tableau plus simple à parcourir
        foreach ($plage_dispo as $date) {
            $simple_dates[] = $date['jour_plage_ponctuelle'];
        }

        
        // Détecte les plages indisponibles consécutives
        $plage_indispo = [];
        $debut_plage = null;
        foreach ($simple_dates as $date) {
            if ($debut_plage === null) {
                $debut_plage = $date;
            } 
            elseif ($date != $debut_plage) {
                $plage_indispo[] = [$debut_plage, $date];
                $debut_plage = $date;
            }
        }
        // Ajouter la dernière plage si elle n'est pas terminée
        if ($debut_plage !== null) {
            $plage_indispo[] = [$debut_plage, $params['fin']];
        }

    } else{
        $plage_indispo = [];
    }


    
    //Evenèment au format ICS


    $ics  = "BEGIN:VCALENDAR\n";
    $ics .= "VERSION:2.0\n";
    $ics .= "PRODID:-//hacksw/handcal//NONSGML v1.0//EN\n";

    foreach($reservations as $resa){
        $objet = "Une resa";
        $lieu = "lieu";
        $details = "Test boucle resa";

        $ics .= "BEGIN:VEVENT\n";
        $ics .= "X-WR-TIMEZONE:Europe/Paris\n";
        $ics .= "DTSTART:".str_replace('-', '',$resa['date_arrivee'])."T180000\n";
        $ics .= "DTEND:".str_replace('-', '',$resa['date_depart'])."T120000\n";
        $ics .= "SUMMARY:".$objet."\n";
        $ics .= "LOCATION:".$lieu."\n";
        $ics .= "DESCRIPTION:".$details."\n";
        $ics .= "END:VEVENT\n";
    }
    foreach($demandes as $dema){
        $objet = "Une demande";
        $lieu = "lieu";
        $details = "Test boucle demande";

        $ics .= "BEGIN:VEVENT\n";
        $ics .= "X-WR-TIMEZONE:Europe/Paris\n";
        $ics .= "DTSTART:".str_replace('-', '',$dema['date_arrivee'])."T170000\n";
        $ics .= "DTEND:".str_replace('-', '',$dema['date_depart'])."T120000\n";
        $ics .= "SUMMARY:".$objet."\n";
        $ics .= "LOCATION:".$lieu."\n";
        $ics .= "DESCRIPTION:".$details."\n";
        $ics .= "END:VEVENT\n";
    }

    $ics .= "END:VCALENDAR\n";
    
    $file = "../calendar_ics/$token.ics";
    file_put_contents($file, $ics);
    header("Location: $file");
?>