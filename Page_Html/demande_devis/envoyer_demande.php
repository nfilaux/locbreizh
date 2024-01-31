<?php
    // lancement de la session
    session_start();
    require_once('../tcpdf/tcpdf.php');
    // import parametre de connexion + nouvelle instance PDO
    include('../parametre_connexion.php');
    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    $stmt = $dbh->prepare("SELECT jour_plage_ponctuelle
    FROM locbreizh._plage_ponctuelle_disponible d
    JOIN locbreizh._plage_ponctuelle p ON d.id_plage_ponctuelle = p.id_plage_ponctuelle
    JOIN locbreizh._planning ON p.code_planning = _planning.code_planning
    JOIN locbreizh._logement l ON l.code_planning = _planning.code_planning
    WHERE id_logement = :id_logement");
    $stmt->bindParam(':id_logement', $_POST['logement']);
    $stmt->execute();
    $resDates = $stmt->fetchAll();
    $err = 0;
    // Converti les dates donne en paramètre
    $date_arrive = new DateTime($_POST['dateArrivee']);
    $date_depart = new DateTime($_POST['dateDepart']);
    // parcours tous les jours de la periode de reservation
    for ($date = clone $date_arrive; $date <= $date_depart; $date->modify('+1 day')) {
        $date_formate = $date->format('Y-m-d');
        $date_trouve = false;
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
    if(!isset($_POST['animaux'])){
        $_POST['animaux'] = false;
    }
    if(!isset($_POST['menage'])){
        $_POST['menage'] = false;
    }
    //test si date d'arrivée est avant date de depart
    if($_POST['dateArrivee'] > $_POST['dateDepart']){
        //renvoie l'utilisateur sur la page d'origine avec les infos preremplies
        header("Location: demande_devis.php?logement={$_POST['logement']}&animaux={$_POST['animaux']}&menage={$_POST['menage']}&nb_pers={$_POST['nb_pers']}&nb_supp={$_POST['nb_pers_supp']}&erreur=1");
    }
    //test si date d'arrivee et de depart sont après la date actuelle
    else if($_POST['dateArrivee'] < date('Y-m-d') or $_POST['dateDepart'] < date('Y-m-d')){
        //renvoie l'utilisateur sur la page d'origine avec les infos preremplies
        header("Location: demande_devis.php?logement={$_POST['logement']}&animaux={$_POST['animaux']}&menage={$_POST['menage']}&nb_pers={$_POST['nb_pers']}&nb_supp={$_POST['nb_pers_supp']}&erreur=2");
    }
    // sinon ajout de la demande dans la BDD
    else if($err == 1){
        header("Location: demande_devis.php?logement={$_POST['logement']}&animaux={$_POST['animaux']}&menage={$_POST['menage']}&nb_pers={$_POST['nb_pers']}&nb_supp={$_POST['nb_pers_supp']}&erreur=3");
    }
    //test si date d'arrivée est avant date de depart
    else if($_POST['dateArrivee'] == $_POST['dateDepart']){
        //renvoie l'utilisateur sur la page d'origine avec les infos preremplies
        if(!isset($_POST['animaux'])){
            $_POST['animaux'] = '';
        }
        if(!isset($_POST['menage'])){
            $_POST['menage'] = '';
        }
        header("Location: demande_devis.php?logement={$_POST['logement']}&animaux={$_POST['animaux']}&menage={$_POST['menage']}&nb_pers={$_POST['nb_pers']}&nb_supp={$_POST['nb_pers_supp']}&erreur=4");
    }
    else{
        // on recupère la valeur du prochain serial pour le mettre en nom de pdf
        $stmt = $dbh->prepare("SELECT NEXTVAL('locbreizh._demande_devis_num_demande_devis_seq') as prochain_serial");
        $stmt->execute();
        $serial_actuel = $stmt->fetch();
        $id_demande = $serial_actuel['prochain_serial'] + 1;
        // insert la demande dans _demande_devis
        $stmt = $dbh->prepare("INSERT INTO locbreizh._demande_devis(nb_personnes, date_arrivee, date_depart, client, logement, url_detail) 
        VALUES (:nb_personnes, :date_arrivee, :date_depart, :client, :logement, :url_d);");
        $stmt->bindParam(':nb_personnes', $_POST['nb_pers']);
        $stmt->bindParam(':date_arrivee', $_POST['dateArrivee']);
        $stmt->bindParam(':date_depart', $_POST['dateDepart']);
        $stmt->bindParam(':client', $_SESSION['id']);
        $stmt->bindParam(':logement', $_POST['logement']);
        $url = "demande_devis$id_demande.pdf";
        $stmt->bindParam(':url_d', $url);
        $stmt->execute();
        // recupere l'id crée automatiquement (en serial)
        // recupere le libelle du logement pour pdf
        $stmt = $dbh->prepare("SELECT libelle_logement from locbreizh._logement where id_logement = :logement;");
        $stmt->bindParam(':logement', $_POST['logement']);
        $stmt->execute();
        $libelle_log = $stmt->fetch();
        // ajout de la charge menage si demandee
        if(isset($_POST['menage']) && $_POST['menage'] != ''){
            // recupere le prix de la charge
            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._possede_charges_associee_logement where id_logement = :logement and nom_charges = 'menage';");
            $stmt->bindParam(':logement', $_POST['logement']);
            $stmt->execute();
            $prix_menage = $stmt->fetch();
            // ajoute le lien entre la charges et la demande avec le prix en plus
            $stmt = $dbh->prepare("INSERT INTO locbreizh._comporte_charges_associee_demande_devis (prix_charges, num_demande_devis, nom_charges)
            VALUES (:prix_charges, :id_demande, 'menage');");
            $stmt->bindParam(':prix_charges', $prix_menage['prix_charges']);
            $stmt->bindParam(':id_demande',$id_demande);
            $stmt->execute();
        }
        // ajout de la charge animaux si demandee
        if(isset($_POST['animaux']) && $_POST['animaux'] != ''){
            // recupere le prix de la charge
            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._possede_charges_associee_logement where id_logement = :logement and nom_charges = 'animaux';");
            $stmt->bindParam(':logement', $_POST['logement']);
            $stmt->execute();
            $prix_animaux = $stmt->fetch();
            // ajoute le lien entre la charges et la demande avec le prix en plus
            $stmt = $dbh->prepare("INSERT INTO locbreizh._comporte_charges_associee_demande_devis (prix_charges, num_demande_devis, nom_charges)
            VALUES (:prix_charges, :id_demande, 'animaux');");
            $stmt->bindParam(':prix_charges', $prix_animaux['prix_charges']);
            $stmt->bindParam(':id_demande', $id_demande);
            $stmt->execute();
        }
        // ajout de la charge personnes supplementaires si demandee
        if($_POST['nb_pers_supp'] > 0){
            // recupere le prix de la charge
            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._possede_charges_associee_logement where id_logement = :logement and nom_charges = 'personnes_supplementaires';");
            $stmt->bindParam(':logement', $_POST['logement']);
            $stmt->execute();
            $prix_supp = $stmt->fetch();
            // ajoute le lien entre la charges et la demande avec le prix et le nombre en plus
            $stmt = $dbh->prepare("INSERT INTO locbreizh._comporte_charges_associee_demande_devis (prix_charges, num_demande_devis, nom_charges, nombre)
            VALUES (:prix_charges, :id_demande, 'personnes_supplementaires', :nb_pers_supp);");
            $stmt->bindParam(':prix_charges', $prix_supp['prix_charges']);
            $stmt->bindParam(':id_demande', $id_demande);
            $stmt->bindParam(':nb_pers_supp', $_POST['nb_pers_supp']);
            $stmt->execute();
        }
        // on recupere les infos necessaires sur le client pour le pdf de la demande de devis
        $stmt = $dbh->prepare("select * from locbreizh._compte where id_compte = :id;");
        $stmt->bindParam(':id', $_SESSION['id']);
        $stmt->execute();
        $info_user = $stmt->fetch();
        $stmt = $dbh->prepare("SELECT nom,prenom,mail,telephone from locbreizh._compte 
        natural join locbreizh._logement 
        where id_compte = locbreizh._logement.id_proprietaire
        and id_logement = :logement;");
        $stmt->bindParam(':logement', $_POST['logement']);
        $stmt->execute();
        $proprioinfo = $stmt->fetch();
        // creation du pdf
        $pdf = new TCPDF();
        // titre pdf
        $pdf->SetTitle('Demande de Devis');
        // cree une nouvelle page
        $pdf->AddPage();
        // on regarde les charges a mettre dans le pdf
        if($_POST['animaux']){
            $animaux= 'nous aurons des animaux';
        }
        else{
            $animaux = 'nous n’aurons pas d’animaux';
        }
        if($_POST['menage']){
            $menage = 'Nous souhaitons ajouter la prestation pour le ménage';    
        }
        else{
            $menage = 'Nous ne souhaitons pas ajouter la prestation pour le ménage';
        }
        //titre sur le document
        $pdf->SetFont('', 'B', 30);
        $pdf->SetTextColor(116,80,134);
        $pdf->Cell(0, 25, "Demande de devis", 0, 1,'C');
        $pdf->SetTextColor(0,0,0);  
        // definit la police et la taille de la police
        $pdf->SetFont('', '', 12);
        $endatearrive = strtotime($_POST['dateArrivee']);
        $frdatearrive = date("d/m/Y", $endatearrive);
        $endatedepart = strtotime($_POST['dateDepart']);
        $frdatedepart = date("d/m/Y", $endatedepart);
        $pdf->Cell(0, 8, $info_user['nom'] . ' ' . $info_user['prenom'], 0, 1);
        $pdf->Cell(0, 8, $info_user['mail'], 0, 1);
        $pdf->Cell(0, 8, $info_user['telephone'], 0, 1);
        $pdf->Cell(0, 8, $proprioinfo['nom'] . ' ' . $proprioinfo['prenom'], 0, 1,'R');
        $pdf->Cell(0, 8, $proprioinfo['mail'], 0, 1,'R');
        $pdf->Cell(0, 8, $proprioinfo['telephone'], 0, 1,'R');
        $pdf->Ln(15 );
        $pdf->Cell(0, 20, 'Objet : Demande de devis pour la réservation du logement "' . $libelle_log['libelle_logement'] . "\"" , 0,'L');
        $pdf->Cell(0, 20,  'Madame, Monsieur ' , 0, 'L');
        $pdf->Cell(0, 5,  'Je me permets de vous contacter afin de solliciter un devis pour la réservation de votre logement "', 0, 'L');
        $pdf->Cell(0, 5,  $libelle_log['libelle_logement'] . '" pour la période du ' . $frdatearrive . ' au ' . $frdatedepart . '. Nous serons  ' . $_POST['nb_pers'] . ' personnes et ', 0, 'L');
        $pdf->Cell(0, 5,  $animaux . '.' . $menage, 0, 1);
        $pdf->Cell(0, 5,  'et ' . $_POST['nb_pers_supp'] . ' personne(s) supplémentaire(s).', 0, 'L');
        $pdf->Cell(0, 15, '', 0, 1);
        $pdf->MultiCell(0, 10, "Je vous serais reconnaissant de bien vouloir nous fournir un devis détaillé, incluant le coût total du séjour, ainsi que les éventuels frais supplémentaires." , 0, 'L',false);
        $pdf->Cell(0, 15, '', 0, 1);
        $pdf->MultiCell(0, 10, "Je vous remercie par avance pour l'attention que vous porterez à notre demande et reste à votre disposition pour tout complément d'information.", 0, 'L',false);
        $pdf->Cell(0, 25, "Cordialement,", 0, 1);
        $pdf->Cell(0, 15, $info_user['nom'] . ' ' . $info_user['prenom'], 0, 1,'R');
        // genere le contenu PDF
        $contenu_pdf = $pdf->Output('demande_devis.pdf', 'S'); // 'S' pour obtenir le contenu du PDF
        // enregistre le PDF dans un dossier
        $chemin_dossier = 'pdf_demande/';
        $nom_fichier = "demande_devis$id_demande.pdf";
        $chemin_complet = $chemin_dossier . $nom_fichier;
        file_put_contents($chemin_complet, $contenu_pdf);
        // renvoie l'utilisateur sur une page de reussite
        header("Location: demande_devis.php?logement={$_POST['logement']}&erreur=0");
    }
?>
