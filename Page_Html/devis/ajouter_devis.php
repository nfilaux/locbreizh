<?php
    session_start();
    $_SESSION['erreurs'] = [];
    $_SESSION['valeurs_complete'] = [];
    include('../parametre_connexion.php');
    require_once('../tcpdf/tcpdf.php');
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $_SESSION['valeurs_complete']['nb_pers'] = $_POST['nb_pers'];
    $_SESSION['valeurs_complete']['delais_accept'] = $_POST['delais_accept'];
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

    if ($_POST["date_depart"] < $_POST["date_arrivee"]){
        //echo "y a une erreur de date";
        $_SESSION['erreurs']['valide_dates'] = "La date d'arrivee se trouve après la date de départ !";
    }
    else if($_POST["date_depart"] < date('Y-m-d') or $_POST["date_arrivee"] < date('Y-m-d')) {
        //echo "y a une erreur de date";
        $_SESSION['erreurs']['valide_dates'] = "Les dates données sont ulterieures à aujourd'hui !";
    }
    else {
        $_SESSION['valeurs_complete']['date_depart'] = $_POST["date_depart"];
        $_SESSION['valeurs_complete']['date_arrivee'] = $_POST["date_arrivee"];
    }

    if ($_SESSION['erreurs'] != []){
        header("Location: formulaire_devis.php?demande={$_POST['id_demande']}");
    }
    else {
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

        $reqNomClient = $dbh->prepare("SELECT nom, prenom, id_compte, pseudo 
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
        num_demande_devis, taxe_sejour, url_detail,nb_personnes )
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
        :nb_personnes)");

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
        // accepte la demande pour informer le client
        $stmt = $dbh->prepare("UPDATE locbreizh._demande_devis set accepte = True where num_demande_devis = :num;");
        $stmt->bindParam(':num', $_POST['id_demande']);
        $stmt->execute();


        // creation du pdf
        $pdf = new TCPDF();

        // titre pdf
        $pdf->SetTitle('Devis');

        // cree une nouvelle page
        $pdf->AddPage();

        // definit la police et la taille de la police
        $pdf->SetFont('', '', 12);


        // tableaux avec toutes les infos du pdf
        $devisInfo = array(
            'Nom' => $infos_user['nom'],
            'Prenom' => $infos_user['prenom'],
            'Libelle logement' => $logement['libelle_logement'],
            'Tarif ht location nuitee devis' => $nuitees_HT . ' €',
            'Prix charges HT' => $totalCharges_HT. ' €',
            'Sous total HT' => $sousTotal_HT. ' €',
            'Sous total TTC' => $sousTotal_TTC. ' €',
            'Taxe de séjour' => $taxe_sejour. ' €',
            'Total plateforme HT' =>$fraisService_HT. ' €',
            'Total plateforme TTC' =>$fraisService_TTC. ' €',
            'Total montant devis' =>$prixTotal. ' €',
            'Date devis' => $date_devis
        );

        // Boucle pour mettre les informations de la demande dans le PDF
        foreach ($devisInfo as $label => $valeur) {
            $pdf->Cell(0, 10, $label . ': ' . $valeur, 0, 1);
        }

        // genere le contenu PDF
        $contenu_pdf = $pdf->Output('devis.pdf', 'S'); // 'S' pour obtenir le contenu du PDF

        // enregistre le PDF dans un dossier
        $chemin_dossier = 'pdf_devis/';
        $nom_fichier = "devis$id_devis.pdf";
        $chemin_complet = $chemin_dossier . $nom_fichier;
        file_put_contents($chemin_complet, $contenu_pdf);

        header("Location: ./formulaire_devis.php?demande={$_POST['id_demande']}&erreur=0");
    }
?>
