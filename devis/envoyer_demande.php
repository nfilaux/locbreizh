<?php 
    // lancement de la session
    session_start();

    require_once('../tcpdf/tcpdf.php');
    //test si date d'arrivée est avant date de depart
    if($_POST['dateArrivee'] > $_POST['dateDepart']){
        //renvoie l'utilisateur sur la page d'origine avec les infos preremplies
        header("Location: demande_devis.php?animaux={$_POST['animaux']}&menage={$_POST['menage']}&nb_pers={$_POST['nb_pers']}&nb_supp={$_POST['nb_pers_supp']}&mauvais=1");
    }
    //test si date d'arrivee et de depart sont après la date actuelle
    else if($_POST['dateArrivee'] < date('Y-m-d') or $_POST['dateDepart'] < date('Y-m-d')){
        //renvoie l'utilisateur sur la page d'origine avec les infos preremplies
        header("Location: demande_devis.php?logement={$_POST['logement']}&animaux={$_POST['animaux']}&menage={$_POST['menage']}&nb_pers={$_POST['nb_pers']}&nb_supp={$_POST['nb_pers_supp']}&mauvais=2");
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
        
        // insert la demande dans _demande_devis
        $stmt = $dbh->prepare("INSERT INTO locbreizh._demande_devis(nb_personnes, date_arrivee, date_depart, client, logement) 
        VALUES ({$_POST['nb_pers']}, '{$_POST['dateArrivee']}', '{$_POST['dateDepart']}', {$_SESSION['id']}, {$_POST['logement']});");
        $stmt->execute();
        // recupere l'id crée automatiquement (en serial)
        $id_demande = $dbh->lastInsertId();

        // recupere le libelle du logement pour pdf + message type
        $stmt = $dbh->prepare("SELECT libelle_logement from locbreizh._logement where id_logement = {$_POST['logement']};");
        $stmt->execute();
        $libelle_log = $stmt->fetch();

        // date et heure actuelle (pour message)
        $date = date('Y-m-d');
        $time = date('H:i:s');

        // recupere les convserations entre le client et le proprietaire du logement
        $stmt = $dbh->prepare("SELECT c.id_conversation
        FROM locbreizh._conversation c
        INNER JOIN locbreizh._logement ON (_logement.id_proprietaire = compte1 or _logement.id_proprietaire = compte2)
        WHERE id_logement = {$_POST['logement']} and ((compte1 = {$_SESSION['id']} and compte2 = id_proprietaire) or (compte2 = {$_SESSION['id']} and compte1 = id_proprietaire));");
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
            $stmt = $dbh->prepare("select id_proprietaire from locbreizh._logement where id_logement = {$_POST['logement']}");
            $stmt->execute();
            $proprio = $stmt->fetch();

            // on cree une conversation entre le client et le proprio
            $stmt = $dbh->prepare("INSERT INTO locbreizh._conversation(compte1, compte2) 
            VALUES ({$_SESSION['id']}, {$proprio['id_proprietaire']});");
            $stmt->execute();
            // on recupere l'id de la conv cree
            $id_conv = $dbh->lastInsertId();
        }

        // on recupere les infos necessaires sur le clinet pour le pdf de la demande de devis + message
        $stmt = $dbh->prepare("select * from locbreizh._compte where id_compte = {$_SESSION['id']};");
        $stmt->execute();
        $info_user = $stmt->fetch();

        // ajoute le message type pour une demande de devis
        $stmt = $dbh->prepare("INSERT INTO locbreizh._message(contenu_message, date_mess, heure_mess, auteur, conversation) 
        VALUES ('Voici une demande de DEVIS de {$info_user['pseudo']} pour le logement {$libelle_log['libelle_logement']}', '$date', '$time', {$_SESSION['id']}, $id_conv);");
        $stmt->execute();
        $id_mess = $dbh->lastInsertId();

        $stmt = $dbh->prepare("INSERT INTO locbreizh._message_devis(id_message_devis, lien_demande_devis)
        VALUES ($id_mess, 'devis$id_demande.pdf');");
        $stmt->execute();

        // ajout de la charge menage si demandee
        if(isset($_POST['menage'])){
            // recupere le prix de la charge
            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._possede_charges_associee_logement where id_logement = {$_POST['logement']} and nom_charges = 'menage';");
            $stmt->execute();
            $prix_menage = $stmt->fetch();

            // ajoute le lien entre la charges et la demande avec le prix en plus
            $stmt = $dbh->prepare("INSERT INTO locbreizh._comporte_charges_associee_demande_devis (prix_charges, num_demande_devis, nom_charges)
            VALUES ({$prix_menage['prix_charges']}, $id_demande, 'menage');");
            $stmt->execute();
        }
        // ajout de la charge animaux si demandee
        if(isset($_POST['animaux'])){
            // recupere le prix de la charge
            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._possede_charges_associee_logement where id_logement = {$_POST['logement']} and nom_charges = 'animaux';");
            $stmt->execute();
            $prix_animaux = $stmt->fetch();

            // ajoute le lien entre la charges et la demande avec le prix en plus
            $stmt = $dbh->prepare("INSERT INTO locbreizh._comporte_charges_associee_demande_devis (prix_charges, num_demande_devis, nom_charges)
            VALUES ({$prix_animaux['prix_charges']}, $id_demande, 'animaux');");
            $stmt->execute();
        }
        // ajout de la charge personnes supplementaires si demandee
        if($_POST['nb_pers_supp'] > 0){
            // recupere le prix de la charge
            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._possede_charges_associee_logement where id_logement = {$_POST['logement']} and nom_charges = 'personnes_supplementaires';");
            $stmt->execute();
            $prix_supp = $stmt->fetch();

            // ajoute le lien entre la charges et la demande avec le prix et le nombre en plus
            $stmt = $dbh->prepare("INSERT INTO locbreizh._comporte_charges_associee_demande_devis (prix_charges, num_demande_devis, nom_charges, nombre)
            VALUES ({$prix_supp['prix_charges']}, $id_demande, 'personnes_supplementaires', {$_POST['nb_pers_supp']});");
            $stmt->execute();
        }

        // creation du pdf
        $pdf = new TCPDF();

        // titre pdf
        $pdf->SetTitle('Demande de Devis');

        // cree une nouvelle page
        $pdf->AddPage();

        // definit la police et la taille de la police
        $pdf->SetFont('', '', 12);

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

        // tableaux avec toutes les infos du pdf
        $demandeInfo = array(
            'Nom' => $info_user['nom'] . ' ' . $info_user['prenom'] ,
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
            $pdf->Cell(0, 10, $label . ': ' . $valeur, 0, 1);
        }

        // genere le contenu PDF
        $contenu_pdf = $pdf->Output('demande_devis.pdf', 'S'); // 'S' pour obtenir le contenu du PDF

        // enregistre le PDF dans un dossier
        $chemin_dossier = 'pdf_demande/';
        $nom_fichier = "devis$id_demande.pdf";
        $chemin_complet = $chemin_dossier . $nom_fichier;
        file_put_contents($chemin_complet, $contenu_pdf);

        // renvoie l'utilisateur sur une page de reussite
        header("Location: demande_devis_envoye.php");
    }
?>