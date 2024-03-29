<pre>
<?php
    include('../parametre_connexion.php');
    //t
    try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }

    $token = 'e9988cad2cb6a29e469fbfa03de19517ef1360e6';

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

    foreach ($plage_dispo as $jour) {
        $jour_disponible = strtotime($jour['jour_plage_ponctuelle']);

        // Si la période temporaire n'est pas vide et le jour actuel n'est pas le jour suivant
        if (!empty($periode_temporaire) && strtotime("+1 day", end($periode_temporaire)) != $jour_disponible) {
            // Ajoute la période temporaire à la liste des périodes indisponibles
            $periodes_indispo[] = $periode_temporaire;
            // Réinitialise la période temporaire
            $periode_temporaire = array();
        }

        // Ajoute le jour actuel à la période temporaire
        $periode_temporaire[] = $jour_disponible;
    }

    // Ajoute la dernière période temporaire à la liste des périodes indisponibles
    if (!empty($periode_temporaire)) {
        $periodes_indispo[] = $periode_temporaire;
    }

    // Formate bien le tableau
    foreach ($periodes_indispo as $periode) {
        $periodes_indispo_formate[] = [date('Ymd',$periode[0]), date('Ymd', end($periode))];
    }
    print_r($periodes_indispo_formate)
?>
</pre>