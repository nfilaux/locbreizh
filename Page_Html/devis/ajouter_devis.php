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
        // taxe de sejour
        $stmt = $dbh->prepare("SELECT taxe_sejour, nb_personnes FROM locbreizh._demande_devis d 
        JOIN locbreizh._logement l ON  d.logement = l.id_logement 
        WHERE num_demande_devis = {$_POST['id_demande']};");
        $stmt->execute();
        $taxe = $stmt->fetch();

        // recherche prix charge menage
        $stmt = $dbh->prepare("SELECT prix_charges
        FROM locbreizh._comporte_charges_associee_devis
        WHERE num_devis = {$_POST['id_demande']} and nom_charges = 'menage';");
        $stmt->execute();
        $menage = $stmt->fetch();

        // recherche prix charge animaux
        $stmt = $dbh->prepare("SELECT prix_charges
        FROM locbreizh._comporte_charges_associee_devis
        WHERE num_devis = {$_POST['id_demande']} and nom_charges = 'animaux';");
        $stmt->execute();
        $animaux = $stmt->fetch();

        // recherche prix charge pers supp
        $stmt = $dbh->prepare("SELECT prix_charges, nombre
        FROM locbreizh._comporte_charges_associee_devis
        WHERE num_devis = {$_POST['id_demande']} and nom_charges = 'personnes_supplementaires';");
        $stmt->execute();
        $pers_supp = $stmt->fetch();

        // ajout des charges
        $totalCharges_HT = 0;
        if(isset($menage['prix_charges'])){
            $totalCharges_HT += $menage['prix_charges'];
        }
        if(isset($animaux['prix_charges'])){
            $totalCharges_HT += $animaux['prix_charges'];
        }
        if(isset($pers_supp['prix_charges'])){
            $totalCharges_HT += $pers_supp['prix_charges'] * $pers_supp['nombre'];
        }

        /*
        • Tarif de location des nuitées HT
        • Charges additionnelles HT
        • Sous-total (location et charges) HT et TTC (application d’une TVA de 10%)
        • Frais de service de la plateforme HT et TTC (application d’une TVA de 20%)
        • Taxe de séjour (pas de TVA applicable)
        • Prix total du devis
        */



        // doit calculer en fonction nb jours !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        // calcul
        $nuitees_HT = $_POST['tarif_loc'];
        $sousTotal_HT = $nuitees_HT + $totalCharges_HT;
        $sousTotal_TTC = $sousTotal_HT * 1.1;
        $fraisService_HT = 0.1* $sousTotal_HT;
        $fraisService_TTC = $fraisService_HT * 1.2;
        $taxe_sejour = $taxe["taxe_sejour"] * ($taxe["nb_personnes"] + $pers_supp['nombre']);
        $prixTotal = $sousTotal_TTC + $fraisService_TTC + $taxe_sejour;
    


        $date_devis = date("Y-m-d");



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
        nb_personnes,
        tarif_ht_location_nuitee_devis,
        sous_total_HT_devis,
        sous_total_TTC_devis,
        frais_service_platforme_HT_devis,
        frais_service_platforme_TTC_devis,
        date_devis, date_validite, condition_annulation,
        num_demande_devis, taxe_sejour, url_detail)
        VALUES (
        :client,
        :prixTotal,
        :nb_personnes,
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
        :url_detail)");

        $url = 'devis' . $id_devis . '.pdf';
        $reg_devis->bindParam(':client', $infos_user['id_compte']);
        $reg_devis->bindParam(':nb_personnes', $taxe['nb_personnes']);
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
        $reg_devis->bindParam(':taxe_sejour', $taxe["taxe_sejour"]);
        $reg_devis->bindParam(':url_detail', $url);

        $reg_devis->execute();

        $id_devis = $dbh->lastInsertId();

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

        // cherche le libelle
        $stmt = $dbh->prepare("SELECT libelle_logement FROM locbreizh._demande_devis d 
        JOIN locbreizh._logement l ON  d.logement = l.id_logement 
        WHERE num_demande_devis = {$_POST['id_demande']};");
        $stmt->execute();
        $libelle_log = $stmt->fetch();

        // tableaux avec toutes les infos du pdf
        $devisInfo = array(
            'Nom' => $infos_user['nom'],
            'Prenom' => $infos_user['prenom'],
            'Libelle logement' => $libelle_log['libelle_logement'],
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
