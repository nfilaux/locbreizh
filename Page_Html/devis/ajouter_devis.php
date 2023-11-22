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
    $_SESSION['valeurs_complete']['charges'] = $_POST['charges'];
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
    if (preg_match('/[});]+/', $_POST["annulation"])){
        $_SESSION['erreurs']['cond_annul'] = "Erreur, pour des mesures de sécurité vous ne pouvez pas mettre les caractères suivant dans vos conditions d'annulation.";
    } else {
        $_SESSION['valeurs_complete']['annulation'] = $_POST["annulation"];
    }
    if ($_SESSION['erreurs'] != []){
        header("Location: formulaire_devis.php?demande={$_POST['id_demande']}");
    }
    else {
        $prix_loc = $_POST['tarif_loc'];
        $prix_charges = $_POST['charges'];
        $total_HT = $prix_loc + $prix_charges;
        $total_TTC = $total_HT * 1.1;
        // gestion de la taxe de séjour à voir
        $taxe_sejour = 120;
        $total_montant_devis = $total_TTC + $taxe_sejour;
        $total_plateforme_HT = $total_montant_devis*1.01;
        $total_plateforme_TTC = $total_plateforme_HT * 1.2;
        $date_devis = date("Y-m-d");

        $stmt = $dbh->prepare("SELECT libelle_logement FROM locbreizh._demande_devis d JOIN locbreizh._logement l ON  d.logement = l.id_logement WHERE num_demande_devis = :id_demande;");
        $stmt->bindParam(':id_demande', $_POST['id_demande']);
        $stmt->execute();
        $libelle_log = $stmt->fetch();  

        $reqNomClient = $dbh->prepare("SELECT nom, prenom, id_compte, pseudo FROM locbreizh._demande_devis INNER JOIN locbreizh._compte ON _demande_devis.client = id_compte WHERE num_demande_devis = :id_demande");
        $stmt->bindParam(':id_demande', $_POST['id_demande']);
        $reqNomClient->execute();
        $infos_user = $reqNomClient->fetch();  
        
        $reg_devis = $dbh->prepare("INSERT INTO locbreizh._devis
        (client, prix_total_devis, tarif_ht_location_nuitee_devis,
        sous_total_ht_devis, sous_total_ttc_devis, frais_service_platforme_ht_devis,
        fras_service_platforme_ttc_devis, date_devis, date_validite, condition_annulation,
        num_demande_devis, taxe_sejour) 
        values (
        {:id_compte, :total_montant_devis, :prix_loc,
        :total_HT, :total_TTC, :total_plateforme_HT, 
        :total_plateforme_TTC, :date_devis, :date_val, :annulation, :id_demande, 1);");
        $stmt->bindParam(':id_compte', $infos_user['id_compte']);
        $stmt->bindParam(':total_montant_devis', $total_montant_devis);
        $stmt->bindParam(':prix_loc', $prix_loc);
        $stmt->bindParam(':total_HT', $total_HT);
        $stmt->bindParam(':total_TTC', $total_TTC);
        $stmt->bindParam(':total_plateforme_HT', $total_plateforme_HT);
        $stmt->bindParam(':total_plateforme_TTC', $total_plateforme_TTC);
        $stmt->bindParam(':date_devis', $date_devis);
        $stmt->bindParam(':date_val', $_POST['date_val']);
        $stmt->bindParam(':annulation', $_POST['annulation']);
        $stmt->bindParam(':id_demande', $_POST['id_demande']);
        $reg_devis->execute();
        $id_devis = $dbh->lastInsertId();
        
        // heure et date actuelle
        $date = date('Y-m-d');
        $time = date('H:i:s');

        // recupere les conversations entre le client et le proprietaire du logement
        $stmt = $dbh->prepare("SELECT c.id_conversation
        FROM locbreizh._conversation c
        INNER JOIN locbreizh._demande_devis d ON (d.client = compte1 or d.client = compte2)
        WHERE num_demande_devis = :id_demande and ((compte1 = :id and compte2 = client) or (compte2 = :id and compte1 = client));");
        $stmt->bindParam(':id_demande', $_POST['id_demande']);
        $stmt->bindParam(':id', $_SESSION['id']);
        $stmt->execute();

        // stock dans conv_request
        $conv_request = $stmt->fetch();

        // si conversation existe
        if(!is_bool($conv_request)){
            //stock id_conv
            $id_conv = $conv_request['id_conversation'];
        }
        else{
            // retrouve l'id du proprio
            $stmt = $dbh->prepare("select client from locbreizh._demande_devis where num_demande_devis = :id_demande;");
            $stmt->bindParam(':id_demande', $_POST['id_demande']);
            $stmt->execute();
            $client = $stmt->fetch();

            // on cree une conversation entre le client et le proprio
            $stmt = $dbh->prepare("INSERT INTO locbreizh._conversation(compte1, compte2) 
            VALUES (:id, :client);");
            $stmt->bindParam(':id', $_SESSION['id']);
            $stmt->bindParam(':client', $client['client']);
            $stmt->execute();
            // on recupere l'id de la conv cree
            $id_conv = $dbh->lastInsertId();
        }

        $reqNomClient = $dbh->prepare("SELECT pseudo FROM locbreizh._compte where id_compte = :id");
        $stmt->bindParam(':id', $_SESSION['id']);
        $reqNomClient->execute();
        $infos_proprio = $reqNomClient->fetch();
        // ajoute le message type pour un devis
        $stmt = $dbh->prepare("INSERT INTO locbreizh._message(contenu_message, date_mess, heure_mess, auteur, conversation) 
        VALUES (:contenu_message, :date, :time, :id, :id_conv);");
        $stmt->bindParam(':contenu_message', "Voici le DEVIS final de {$infos_proprio['pseudo']}");
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':id', $_SESSION['id']);
        $stmt->bindParam(':id_conv', $id_conv);
        $stmt->execute();
        $id_mess = $dbh->lastInsertId();

        $stmt = $dbh->prepare("INSERT INTO locbreizh._message_devis(id_message_devis, lien_devis, id_devis)
        VALUES (:id_mess, :lien_devis, :id_devis);");
        $stmt->bindParam(':id_mess', $id_mess);
        $stmt->bindParam(':lien_devis', "devis$id_devis.pdf");
        $stmt->bindParam(':id_devis', $id_devis);
        $stmt->execute();

        // update le statut de la demande qui passe en accepte
        $stmt = $dbh->prepare("UPDATE locbreizh._message_demande set accepte = TRUE where id_demande = :id_demande;");
        $stmt->bindParam(':id_demande', $_POST['id_demande']);
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
            'Libelle logement' => $libelle_log['libelle_logement'],
            'Tarif ht location nuitee devis' => $prix_loc . ' €',
            'Prix charges HT' => $prix_charges. ' €',
            'Total HT' => $total_HT. ' €',
            'Total TTC' => $total_TTC. ' €',
            'Taxe de séjour' => $taxe_sejour. ' €',
            'Total montant devis' =>$total_montant_devis. ' €',
            'Total plateforme HT' =>$total_plateforme_HT. ' €',
            'Total plateforme TTC' =>$total_plateforme_TTC. ' €',
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

        header("Location: devis_ajoute.html");
    }
?>
