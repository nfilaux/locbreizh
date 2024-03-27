<?php
    session_start();
    $_SESSION['erreurs'] = [];
    $_SESSION['valeurs_complete'] = [];
    include('../parametre_connexion.php');
    require_once('../tcpdf/tcpdf.php');
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $_SESSION['valeurs_complete']['nb_pers'] = $_POST['nb_pers'];
    $_SESSION['valeurs_complete']['date_val'] = $_POST['date_val'];
    $_SESSION['valeurs_complete']['tarif_loc'] = $_POST['tarif_loc'];
    $_SESSION['valeurs_complete']['annulation'] = $_POST['annulation'];
    if(isset($_POST['menage'])){
        $_SESSION['valeurs_complete']['menage'] = $_POST['menage'];
    }
    if(isset($_POST['animaux'])){
        $_SESSION['valeurs_complete']['animaux'] = $_POST['animaux'];
    }
    $_SESSION['valeurs_complete']['vacanciers_sup'] = $_POST['vacanciers_sup'];
    // test pour vérifier que les données soit bien entrée au format attendu
    $stmt = $dbh->prepare("SELECT jour_plage_ponctuelle
    FROM locbreizh._plage_ponctuelle_disponible d
    JOIN locbreizh._plage_ponctuelle p ON d.id_plage_ponctuelle = p.id_plage_ponctuelle
    JOIN locbreizh._planning ON p.code_planning = _planning.code_planning
    JOIN locbreizh._logement l ON l.code_planning = _planning.code_planning
    join locbreizh._demande_devis on _demande_devis.logement = l.id_logement
    WHERE num_demande_devis = :num_demande");
    $stmt->bindParam(':num_demande', $_POST['id_demande']);
    $stmt->execute();
    $resDates = $stmt->fetchAll();

    $err = 0;
    // Converti les dates donne en paramètre
    $date_arrive = new DateTime($_POST["date_arrivee"]);
    $date_depart = new DateTime($_POST["date_depart"]);

    $stmt = $dbh->prepare("SELECT prix_plage_ponctuelle
    FROM locbreizh._demande_devis d
    JOIN locbreizh._logement l ON d.logement = l.id_logement
    JOIN locbreizh._planning p ON p.code_planning = l.code_planning
    JOIN locbreizh._plage_ponctuelle p1 ON p1.code_planning = p.code_planning
    JOIN locbreizh._plage_ponctuelle_disponible p2 ON p2.id_plage_ponctuelle = p1.id_plage_ponctuelle
    WHERE num_demande_devis = :num_demande
    AND jour_plage_ponctuelle >= '{$_POST["date_arrivee"]}'
    AND jour_plage_ponctuelle < '{$_POST["date_depart"]}'");
    $stmt->bindParam(':num_demande', $_POST['id_demande']);
    $stmt->execute();
    $prixnuitunite = $stmt->fetchAll();
    $tabDates = [];
    // parcours tous les jours de la periode de reservation
    for($date = clone $date_arrive; $date <= $date_depart; $date->modify('+1 day')) {
        $date_formate = $date->format('Y-m-d');
        $date_trouve = false;
        $tabDates[] = $date_formate;
        foreach ($resDates as $resDate) {
            if ($date_formate == $resDate['jour_plage_ponctuelle']) {
                $date_trouve = true;
                break;
            }
        }
        if (!$date_trouve) {
            $err = 1;
        }
    }
    if ($_POST["date_depart"] < $_POST["date_arrivee"]){
        //echo "y a une erreur de date";
        $_SESSION['erreurs']['valide_dates'] = "La date d'arrivée se trouve après la date de départ !";
    }
    else if($_POST["date_depart"] < date('Y-m-d') or $_POST["date_arrivee"] < date('Y-m-d')) {
        //echo "y a une erreur de date";
        $_SESSION['erreurs']['valide_dates'] = "Les dates données sont ulterieures à aujourd'hui !";
    }
    else if($err == 1){
        $_SESSION['erreurs']['valide_dates'] = "Les dates données ne sont pas présentes dans le planning !";
    }
    else if($_POST['date_arrivee'] == $_POST['date_depart']){
        $_SESSION['erreurs']['valide_dates'] = "Il faut au minimum une nuit pour réserver un logement";
    }
    else {
        $_SESSION['valeurs_complete']['date_depart'] = $_POST["date_depart"];
        $_SESSION['valeurs_complete']['date_arrivee'] = $_POST["date_arrivee"];
    }
    if ($_SESSION['erreurs'] != []){
        header("Location: formulaire_devis.php?demande={$_POST['id_demande']}");
    }
    else {
        $nb_personnes = $_POST['nb_pers'];
        $vac_supp = $_POST['vacanciers_sup'];
        $prix_par_nuit = $_POST['tarif_loc'] ;
        $nuitees_HT = $_POST['nuitees'];
        $totalCharges_HT = $_POST['prixCharges'];
        $sousTotal_HT = $_POST['sousTotal_HT'];
        $sousTotal_TTC =  $_POST['sousTotal_TTC'];
        $fraisService_HT = $_POST['fraisService_HT'];
        $fraisService_TTC = $_POST['fraisService_TTC'];
        $taxe_sejour = $_POST['taxe_sejour'];
        $prixTotal = $_POST['prixTotal'];
        $date_devis = date("Y-m-d");
        // cherche le libelle(pour pdf) + id(pour charges) + id_taxe
        $stmt = $dbh->prepare("SELECT libelle_logement, id_logement,taxe_sejour FROM locbreizh._demande_devis d 
        JOIN locbreizh._logement l ON  d.logement = l.id_logement 
        WHERE num_demande_devis = {$_POST['id_demande']};");
        $stmt->execute();
        $logement = $stmt->fetch();
        $reqNomClient = $dbh->prepare("SELECT nom, prenom, id_compte, pseudo, mail 
        FROM locbreizh._demande_devis INNER JOIN locbreizh._compte ON _demande_devis.client = id_compte 
        WHERE num_demande_devis = {$_POST['id_demande']}");
        $reqNomClient->execute();
        $infos_user = $reqNomClient->fetch();
        $stmt = $dbh->prepare("SELECT NEXTVAL('locbreizh._devis_num_devis_seq') as prochain_serial");
        $stmt->execute();
        $serial_actuel = $stmt->fetch();
        $id_devis = $serial_actuel['prochain_serial'] + 1;
        $reg_devis = $dbh->prepare("INSERT INTO locbreizh._devis
        (client, 
        prix_total_devis, 
        tarif_ht_location_nuitee_devis,
        sous_total_HT_devis,
        sous_total_TTC_devis,
        frais_service_platforme_HT_devis,
        frais_service_platforme_TTC_devis,
        date_devis, date_validite, condition_annulation,
        num_demande_devis, taxe_sejour, url_detail,nb_personnes,date_arrivee,date_depart )
        VALUES (
        :client,
        :prixTotal,
        :nuitees_HT,
        :sousTotal_HT,
        :sousTotal_TTC,
        :fraisService_HT,
        :fraisService_TTC,
        :date_devis,
        :date_val,
        :annulation,
        :id_demande,
        :taxe_sejour,
        :url_detail,
        :nb_personnes,
        :date_arrivee,
        :date_depart)");
        $url = 'devis' . $id_devis . '.pdf';
        $reg_devis->bindParam(':client', $infos_user['id_compte']);
        $reg_devis->bindParam(':prixTotal', $prixTotal);
        $reg_devis->bindParam(':nuitees_HT', $nuitees_HT);
        $reg_devis->bindParam(':sousTotal_HT', $sousTotal_HT);
        $reg_devis->bindParam(':sousTotal_TTC', $sousTotal_TTC);
        $reg_devis->bindParam(':fraisService_HT', $fraisService_HT);
        $reg_devis->bindParam(':fraisService_TTC', $fraisService_TTC);
        $reg_devis->bindParam(':date_devis', $date_devis);
        $reg_devis->bindParam(':date_val', $_POST['date_val']);
        $reg_devis->bindParam(':annulation', $_POST['annulation']);
        $reg_devis->bindParam(':id_demande', $_POST['id_demande']);
        $reg_devis->bindParam(':taxe_sejour', $logement['taxe_sejour']);
        $reg_devis->bindParam(':url_detail', $url);
        $reg_devis->bindParam(':nb_personnes', $_POST['nb_pers']);
        $reg_devis->bindParam(':date_arrivee', $_POST['date_arrivee']);
        $reg_devis->bindParam(':date_depart', $_POST['date_depart']);
        $reg_devis->execute();
        $id_devis = $dbh->lastInsertId();
        //insertion charges additionnelles
        // menage
        if(isset($_POST['menage'])){
            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._possede_charges_associee_logement 
            where id_logement = {$logement['id_logement']} and nom_charges = 'menage';");
            $stmt->execute();
            $menage = $stmt->fetch();
            $stmt = $dbh->prepare("INSERT into locbreizh._comporte_charges_associee_devis(
            prix_charges,
            num_devis,
            nom_charges) 
            values(
                {$menage['prix_charges']},
                $id_devis,
                'menage')");
            $stmt->execute();
        }
        // animaux
        if(isset($_POST['animaux'])){
            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._possede_charges_associee_logement 
            where id_logement = {$logement['id_logement']} and nom_charges = 'animaux';");
            $stmt->execute();
            $animaux = $stmt->fetch();
            $stmt = $dbh->prepare("INSERT into locbreizh._comporte_charges_associee_devis(
            prix_charges,
            num_devis,
            nom_charges) 
            values(
                {$animaux['prix_charges']},
                $id_devis,
                'animaux')");
            $stmt->execute();
        }
        // personnes supp
        if($_POST['vacanciers_sup'] > 0){
            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._possede_charges_associee_logement 
            where id_logement = {$logement['id_logement']} and nom_charges = 'personnes_supplementaires';");
            $stmt->execute();
            $personnes_supplementaires = $stmt->fetch();
            $stmt = $dbh->prepare("INSERT into locbreizh._comporte_charges_associee_devis
            values(
                {$personnes_supplementaires['prix_charges']},
                $id_devis,
                'personnes_supplementaires',
                {$_POST['vacanciers_sup']})");
            $stmt->execute();
        }
        $code = $dbh->prepare("SELECT code_planning FROM locbreizh._logement WHERE id_logement = :id_logement;");
        $code->bindParam(':id_logement', $logement['id_logement']);
        $code->execute();
        $variable = $code->fetch();
        $raison = "Demande devis";
        foreach ($tabDates as $key => $elem){
            $stmt = $dbh->prepare("SELECT d.id_plage_ponctuelle, d.prix_plage_ponctuelle FROM locbreizh._plage_ponctuelle p JOIN locbreizh._plage_ponctuelle_disponible d ON p.id_plage_ponctuelle = d.id_plage_ponctuelle
            WHERE jour_plage_ponctuelle =  :jour_plage_ponctuelle AND code_planning = :code_planning;");
            $stmt->bindParam(':jour_plage_ponctuelle', $elem);
            $stmt->bindParam(':code_planning', $variable['code_planning']);
            $stmt->execute();
            $jour_existant = $stmt->fetch();

            $code = $dbh->prepare("DELETE FROM locbreizh._plage_ponctuelle WHERE id_plage_ponctuelle = :id_plage_ponctuelle;");
            $code->bindParam(':id_plage_ponctuelle', $jour_existant['id_plage_ponctuelle']);
            $code->execute();

            $stmt = $dbh->prepare("INSERT INTO locbreizh._plage_ponctuelle(jour_plage_ponctuelle, code_planning)
            VALUES (:jour_plage_ponctuelle, :code_planning);");
            $stmt->bindParam(':jour_plage_ponctuelle', $elem);
            $stmt->bindParam(':code_planning', $variable['code_planning']);
            $stmt->execute();
            $stmt = $dbh->prepare("SELECT currval('locbreizh._plage_ponctuelle_id_plage_ponctuelle_seq');");
            $stmt->execute();
            $id_plage = $stmt->fetchColumn();

            $stmt = $dbh->prepare("INSERT INTO locbreizh._plage_ponctuelle_indisponible(id_plage_ponctuelle, libelle_indisponibilite, prix_plage_ponctuelle)
            VALUES (:id_plage_ponctuelle, :libelle_indisponibilite, :prix_plage_ponctuelle);");
            $stmt->bindParam(':id_plage_ponctuelle', $id_plage);
            $stmt->bindParam(':libelle_indisponibilite', $raison);
            $stmt->bindParam(':prix_plage_ponctuelle', $jour_existant['prix_plage_ponctuelle']);
            $stmt->execute();
        }
        // accepte la demande pour informer le client
        $stmt = $dbh->prepare("UPDATE locbreizh._demande_devis set accepte = True where num_demande_devis = :num;");
        $stmt->bindParam(':num', $_POST['id_demande']);
        $stmt->execute();
        // cherche le libelle
        $stmt = $dbh->prepare("SELECT libelle_logement FROM locbreizh._demande_devis d 
        JOIN locbreizh._logement l ON  d.logement = l.id_logement 
        WHERE num_demande_devis = {$_POST['id_demande']};");
        $stmt->execute();
        $libelle_log = $stmt->fetch();
        //cherche info proprio
        $stmt = $dbh->prepare("SELECT nom,prenom,mail,telephone from locbreizh._compte 
        natural join locbreizh._logement
        JOIN locbreizh._demande_devis d ON  d.logement = locbreizh._logement.id_logement  
        where id_compte = locbreizh._logement.id_proprietaire
        AND num_demande_devis = {$_POST['id_demande']};");
        $stmt->execute();
        $proprioinfo = $stmt->fetch();
        // creation du pdf
        $pdf = new TCPDF();
        // cree une nouvelle page
        $pdf->AddPage();
        //titre sur le document
        $pdf->SetFont('', 'B', 30);
        $pdf->SetTextColor(116,80,134);
        $pdf->Cell(0, 25, "Devis", 0, 1,'C');
        $pdf->SetTextColor(0,0,0);
        // definit la police et la taille de la police
        $pdf->SetFont('', '', 12);
        $endatearrive = strtotime($_POST["date_arrivee"]);
        $frdatearrive = date("d/m/Y", $endatearrive);
        $endatedepart = strtotime($_POST["date_depart"]);
        $frdatedepart = date("d/m/Y", $endatedepart);
        if(isset($_POST['menage'])){
            $menage = 'Ménage : Oui';    
        }
        else{
            $menage = 'Ménage : Non';
        }
        if(isset($_POST['animaux'])){
            $animaux= 'Animaux : Oui';
        }
        else{
            $animaux = 'Animaux : Non';
        }
        if($vac_supp == ''){
            $vac_supp= 0;
        }

        $pdf->Cell(0, 8, $proprioinfo['nom'] . ' ' . $proprioinfo['prenom'], 0, 1);
        $pdf->Cell(0, 8, $proprioinfo['mail'], 0, 1);
        $pdf->Cell(0, 8, $proprioinfo['telephone'], 0, 1);
        $pdf->Cell(0, 8, $infos_user['nom'] . ' ' . $infos_user['prenom'], 0, 1, 'R');
        $pdf->Cell(0, 8, $infos_user['mail'], 0, 1,'R');
        $pdf->Ln();
        $pdf->Cell(0, 8, 'Le ' . $date_devis, 0, 1, 'R');
        $pdf->Cell(0, 10, "Séjour du ".$frdatearrive . ' au ' . $frdatedepart . '.', 0, 1);
        $pdf->Cell(0, 8, 'Logement demandé : ' . $libelle_log['libelle_logement'], 0, 1);
        $pdf->Cell(0, 8, 'Prix moyen à la nuité : ' . number_format($prix_par_nuit , 2, ',', ' ') . '€', 0, 1);
        $pdf->Cell(0, 8, 'Nombre de personnes : ' . $nb_personnes, 0, 1);
        $pdf->Cell(0, 8, 'Durée de validité du devis en jours : ' . $_POST['date_val'], 0, 1);
        $pdf->Cell(0, 8, 'Nombre de personnes supplémentaires : ' . $vac_supp, 0, 1);
        $pdf->Cell(0, 8, "Condition d'annulation : " . $_POST["annulation"], 0, 1);
        $pdf->Cell(0, 8, $menage, 0, 1);
        $pdf->Cell(0, 8, $animaux, 0, 1);

        $pdf->Ln();
        $pdf->Ln();
        // Informations pour le devis
        $informationsDevis1 = array(
            array('Désignation', 'Prix HT', 'Prix TTC')
        );
        $informationsDevis2 = array();
        $loop = 1;
        foreach ($prixnuitunite as $valeur => $key) {
            array_push($informationsDevis2,array("Nuit $loop", number_format($key["prix_plage_ponctuelle"] , 2, ',', ' '). " €", number_format($key["prix_plage_ponctuelle"]  * 1.1 , 2, ',', ' '). " €"));
            $loop++;
        } 
        $informationsDevis3 = array(
            array('Logement total', number_format($nuitees_HT, 2, '.', '') . " €", number_format($nuitees_HT*1.10 , 2 , '.', ''). ' €'),
        );
        $informationsDevis4 = array(
            array('Charges', number_format($totalCharges_HT, 2, '.', ''). " €",  number_format($totalCharges_HT*1.10, 2, '.', ''). " €"),
            array('Taxe de séjour', number_format($logement['taxe_sejour'], 2 , '.', '') . ' €', number_format($logement['taxe_sejour'], 2, '.', '') . ' €'),
            array('Frais de plateforme', number_format($fraisService_HT, 2, '.', '') . ' €', number_format($fraisService_TTC, 2, '.', '') . ' €')    
        );

        $informationsDevis_nuit = array_merge($informationsDevis1,$informationsDevis2,$informationsDevis3);
        $informationsDevis_frais = array_merge($informationsDevis1,$informationsDevis4);
        // Définir les largeurs des colonnes
        $colonneLargeurs = array(60, 40, 40, 40);
        // Boucle pour créer le tableau
        $premier = 1;
        foreach ($informationsDevis_nuit as $ligne) {
            for ($i = 0; $i < 3; $i++) {
                if ($i == 0) {
                    $pdf->SetFont('', 'B', 12);
                    $align = 'C';
                } elseif($premier){
                    $pdf->SetFont('', 'B', 12);
                    $align = 'C';
                }
                else{
                    $pdf->SetFont('', '', 12);
                    $align = 'R';
                }
                $pdf->Cell($colonneLargeurs[$i], 10, $ligne[$i], 1, 0, $align);
            }
            $pdf->Ln();
            $premier = 0;
        }
        $pdf->Ln();

        $premier = 1;
        foreach ($informationsDevis_frais as $ligne) {
            for ($i = 0; $i < 3; $i++) {
                if ($i == 0) {
                    $pdf->SetFont('', 'B', 12);
                    $align = 'C';
                } elseif($premier){
                    $pdf->SetFont('', 'B', 12);
                    $align = 'C';
                }
                else{
                    $pdf->SetFont('', '', 12);
                    $align = 'R';
                }
                $pdf->Cell($colonneLargeurs[$i], 10, $ligne[$i], 1, 0, $align);
            }
            $pdf->Ln();
            $premier = 0;
        }
        $pdf->Ln();

        $informationsPrix = array(
            array('Prix Total', number_format($nuitees_HT + $totalCharges_HT + $logement['taxe_sejour']+ $fraisService_HT, 2, '.', '') . ' €', number_format($prixTotal, 2, '.', '') . ' €')
        );
        foreach ($informationsPrix as $ligne) {
            for ($i = 0; $i < count($ligne); $i++) {
                if ($i == 0) {
                    $pdf->SetFont('', 'B', 12);
                    $align = 'C';
                }
                else{
                    $pdf->SetFont('', '', 12);
                    $align = 'R';
                }
                $pdf->Cell($colonneLargeurs[$i], 10, $ligne[$i], 1, 0, $align);
            }
            $pdf->Ln();
        }

          
        // genere le contenu PDF
        $contenu_pdf = $pdf->Output('devis.pdf', 'S'); // 'S' pour obtenir le contenu du PDF
        // enregistre le PDF dans un dossier
        $chemin_dossier = 'pdf_devis/';
        $nom_fichier = "devis$id_devis.pdf";
        $chemin_complet = $chemin_dossier . $nom_fichier;
        file_put_contents($chemin_complet, $contenu_pdf);

        // met hors ligne le logement si il n'as plus de plages disponibles
        $plageDispo = $dbh->prepare("SELECT COUNT(*) FROM locbreizh._plage_ponctuelle INNER JOIN locbreizh._plage_ponctuelle_disponible
        ON _plage_ponctuelle.id_plage_ponctuelle = _plage_ponctuelle_disponible.id_plage_ponctuelle WHERE code_planning = :code_planning ;");
        $plageDispo->bindParam(':code_planning', $variable['code_planning']);
        $plageDispo->execute();
        $plageDispo = $plageDispo->fetchColumn();
        print_r($plageDispo);
        if ($plageDispo == 0){
            $enLigne = false;
            $stmt = $dbh->prepare("UPDATE locbreizh._logement SET en_ligne = :enLigne WHERE id_logement = :id_logement;");
            $stmt->bindParam(':enLigne', $enLigne, PDO::PARAM_BOOL);
            $stmt->bindParam(':id_logement', $logement['id_logement']);
            $stmt->execute();
        }

        header("Location: ./formulaire_devis.php?demande={$_POST['id_demande']}&erreur=0");
    }
?>