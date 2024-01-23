<?php 
    // lancement de la session
    session_start();
    /*echo $_POST['dateArrivee'] ."\n";
    echo $_POST['dateDepart'] . "\n";
    echo($_POST['dateArrivee'] > $_POST['dateDepart']) ;*/
    require_once('../tcpdf/tcpdf.php');
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
    else{
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
        if(isset($_POST['menage'])){
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
        if(isset($_POST['animaux'])){
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

        // on recupere les infos necessaires sur le clinet pour le pdf de la demande de devis
        $stmt = $dbh->prepare("select * from locbreizh._compte where id_compte = :id;");
        $stmt->bindParam(':id', $_SESSION['id']);
        $stmt->execute();
        $info_user = $stmt->fetch();

        // creation du pdf
        $pdf = new TCPDF();

        // titre pdf
        $pdf->SetTitle('Demande de Devis');

        // cree une nouvelle page
        $pdf->AddPage();

        

        // on regarde les charges a mettre dans le pdf
        if(isset($_POST['animaux'])){
            $animaux= 'Oui';
        }
        else{
            $animaux = 'Non';
        }
        if(isset($_POST['menage'])){
            $menage = 'Oui';    
        }
        else{
            $menage = 'Non';
        }

        //titre sur le document
        $pdf->SetFont('', 'B', 30);
        $pdf->Cell(0, 25, "Demande de devis", 0, 1,'C');

        // definit la police et la taille de la police
        $pdf->SetFont('', '', 12);

        // tableaux avec toutes les infos du pdf
        $demandeInfo = array(
            'Logement demandé' => $libelle_log['libelle_logement'],
            'Nom du client' => $info_user['nom'] . ' ' . $info_user['prenom'] ,
            'Email' => $info_user['mail'],
            'Date d\'arrivée' => $_POST['dateArrivee'],
            'Date de départ' => $_POST['dateDepart'],
            'Nombre de personnes' => $_POST['nb_pers'],
            'Animaux' =>$animaux,
            'Menage' => $menage,
            'Personnes supplémentaire' => $_POST['nb_pers_supp']
        );

        
        // Boucle pour mettre les informations de la demande dans le PDF
        foreach ($demandeInfo as $label => $valeur) {
            $pdf->Cell(0, 10, $label . ' : ' . $valeur, 0, 1);
        }

        // genere le contenu PDF
        $contenu_pdf = $pdf->Output('demande_devis.pdf', 'I'); // 'S' pour obtenir le contenu du PDF

        // enregistre le PDF dans un dossier
        $chemin_dossier = 'pdf_demande/';
        $nom_fichier = "demande_devis$id_demande.pdf";
        $chemin_complet = $chemin_dossier . $nom_fichier;
        file_put_contents($chemin_complet, $contenu_pdf);

        // renvoie l'utilisateur sur une page de reussite
        header("Location: demande_devis.php?logement={$_POST['logement']}&erreur=0");
    }
?>
