<?php
    session_start();

    $nom = $_SESSION['post_logement']['nomP'];
    $ville = $_SESSION['post_logement']['villeP'];
    $code_postal = $_SESSION['post_logement']['code_postalP'];
    $tarif_de_base = $_SESSION['post_logement']['tarif_de_baseP'];
    $accroche = $_SESSION['post_logement']['accrocheP'];
    $description = $_SESSION['post_logement']['descriptionP'];
    $nature = $_SESSION['post_logement']['natureP'];
    $type = $_SESSION['post_logement']['typeP'];
    $nb_chambres = $_SESSION['post_logement']['nb_chambresP'];
    $nb_lit_simple = $_SESSION['post_logement']['nb_lit_simpleP'];
    $nb_lit_double = $_SESSION['post_logement']['nb_lit_doubleP'];
    $nb_sdb = $_SESSION['post_logement']['nb_sdbP'];
    $surface_maison = $_SESSION['post_logement']['surface_maisonP'];
    $nb_personne_max = $_SESSION['post_logement']['nb_personne_maxP'];
    $surface_jardin = $_SESSION['post_logement']['surface_jardinP'];
    $taxe_sejour = $_SESSION['post_logement']['taxe_sejourP'];
    $en_ligne = false;
    $id_proprietaire = $_SESSION['id'];
    $nom_image_principale = $_SESSION['post_logement']['image1P'];
    if (isset($_SESSION['post_logement']["image2P"])){
        $nom_image2 = $_SESSION['post_logement']['image2P'];
    }
    if (isset($_SESSION['post_logement']["image3P"])){
        $nom_image3 = $_SESSION['post_logement']['image3P'];
    }
    if (isset($_SESSION['post_logement']["image4P"])){
        $nom_image4 = $_SESSION['post_logement']['image4P'];
    }
    if (isset($_SESSION['post_logement']["image5P"])){
        $nom_image5 = $_SESSION['post_logement']['image5P']; 
    }
    if (isset($_SESSION['post_logement']["image6P"])){
        $nom_image6 = $_SESSION['post_logement']['image6P'];
    }

    $charges1 = $_SESSION['post_logement']['charges1P'];
    $charges2 = $_SESSION['post_logement']['charges2P'];
    $charges3 = $_SESSION['post_logement']['charges3P'];

    if (isset($_SESSION['post_logement']['balconP'])) {
        $balcon = $_SESSION['post_logement']['balconP'];
    } else {
        $balcon = 0;
    }

    if (isset($_SESSION['post_logement']['terrasseP'])) {
        $terrasse = $_SESSION['post_logement']['terrasseP'];
    } else {
        $terrasse = 0;
    }

    if (isset($_SESSION['post_logement']['parking_publicP'])) {
        $parking_public = $_SESSION['post_logement']['parking_publicP'];
    } else {
        $parking_public = 0;
    }

    if (isset($_SESSION['post_logement']['parking_priveP'])) {
        $parking_privee = $_SESSION['post_logement']['parking_priveP'];
    } else {
        $parking_privee = 0;
    }

    if (isset($_SESSION['post_logement']['saunaP'])) {
        $sauna = $_SESSION['post_logement']['saunaP'];
    } else {
        $sauna = 0;
    }

    if (isset($_SESSION['post_logement']['hammamP'])) {
        $hammam = $_SESSION['post_logement']['hammamP'];
    } else {
        $hammam = 0;
    }

    if (isset($_SESSION['post_logement']['piscineP'])) {
        $piscine = $_SESSION['post_logement']['piscineP'];
    } else {
        $piscine = 0;
    }

    if (isset($_SESSION['post_logement']['climatisationP'])) {
        $climatisation = $_SESSION['post_logement']['climatisationP'];
    } else {
        $climatisation = 0;
    }

    if (isset($_SESSION['post_logement']['jacuzziP'])) {
        $jacuzzi = $_SESSION['post_logement']['jacuzziP'];
    } else {
        $jacuzzi = 0;
    }

    if (isset($_SESSION['post_logement']['televisionP'])) {
        $television = $_SESSION['post_logement']['televisionP'];
    } else {
        $television = 0;
    }

    if (isset($_SESSION['post_logement']['wifiP'])) {
        $wifi = $_SESSION['post_logement']['wifiP'];
    } else {
        $wifi = 0;
    }

    if (isset($_SESSION['post_logement']['lave_vaiselleP'])) {
        $lave_vaiselle = $_SESSION['post_logement']['lave_vaiselleP'];
    } else {
        $lave_vaiselle = 0;
    }

    if (isset($_SESSION['post_logement']['lave_lingeP'])) {
        $lave_linge = $_SESSION['post_logement']['lave_lingeP'];
    } else {
        $lave_linge = 0;
    }

    if (isset($_SESSION['post_logement']['menageP'])) {
        $menage = $_SESSION['post_logement']['menageP'];
    } else {
        $menage = 0;
    }

    if (isset($_SESSION['post_logement']['lingeP'])) {
        $linge = $_SESSION['post_logement']['lingeP'];
    } else {
        $linge = 0;
    }

    if (isset($_SESSION['post_logement']['lave_vaisselleP'])) {
        $lave_vaiselle = $_SESSION['post_logement']['lave_vaisselleP'];
    } else {
        $lave_vaiselle = 0;
    }

    if (isset($_SESSION['post_logement']['menage'])) {
        $menage_service = "menage";
    } else {
        $menage_service = "";
    }

    if (isset($_SESSION['post_logement']['navette'])) {
        $navette = "navette";
    } else {
        $navette = "";
    }

    if (isset($_SESSION['post_logement']['linge'])) {
        $linge = "linge";
    } else {
        $linge = "";
    }

    try {
        include('../parametre_connexion.php');

        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }

    //echo $dbh->lastInsertId() . "+" . $linge;

    $stmt = $dbh->prepare(
        "INSERT INTO locbreizh._taxe_sejour (prix_journalier_adulte)
            VALUES (:prix_journalier_adulte)"
    );

    $stmt->bindParam(':prix_journalier_adulte', $taxe_sejour);
    $stmt->execute();

    $stmt = $dbh->prepare(
        "INSERT INTO locbreizh._taxe_sejour (prix_journalier_adulte)
            VALUES (:prix_journalier_adulte)"
    );

    $stmt->bindParam(':prix_journalier_adulte', $taxe_sejour);
    $stmt->execute();

    $id_taxe_sejour = $dbh->lastInsertId();

    $stmt = $dbh->prepare(
        "INSERT INTO locbreizh._adresse ( ville, code_postal)
            VALUES (:ville, :code_postal)"
    );

    $stmt->bindParam(':ville', $ville);
    $stmt->bindParam(':code_postal', $code_postal);
    $stmt->execute();

    $id_adresse = $dbh->lastInsertId();

    $stmt = $dbh->prepare(
        "INSERT INTO locbreizh._photo (url_photo)
            VALUES (:image1)"
    );

    $stmt->bindParam(':image1', $nom_image_principale);
    $stmt->execute();

    $planning = $dbh->prepare(
        'INSERT INTO locbreizh._planning(delai_depart_arrivee) 
            VALUES(:delai_depart_arrivee);'
    ); 


    $delai = 1;
    $planning->bindParam(':delai_depart_arrivee', $delai);

    $planning->execute();

    $code_planning = $dbh->lastInsertId();

    $stmt = $dbh->prepare(
        "INSERT INTO locbreizh._logement (libelle_logement, tarif_base_HT, accroche_logement, descriptif_logement, nature_logement, type_logement, nb_chambre, lit_simple, lit_double, nb_salle_bain, surface_logement, nb_personnes_logement, jardin, balcon, terrasse, parking_public, parking_privee, sauna, hammam, piscine, climatisation, jacuzzi, television, wifi, lave_vaisselle, code_planning,lave_linge, photo_principale, taxe_sejour, en_ligne, id_proprietaire, id_adresse)
            VALUES (:libelle_logement, :tarif_de_base, :accroche, :description, :nature, :type, :nb_chambres, :nb_lit_simple, :nb_lit_double, :nb_sdb, :surface_maison, :nb_personne_max, :surface_jardin, :balcon, :terrasse, :parking_public, :parking_privee, :sauna, :hammam, :piscine, :climatisation, :jacuzzi, :television, :wifi, :lave_vaisselle, :code_planning, :lave_linge, :image1, :id_taxe_sejour, :en_ligne, :id_proprietaire, :id_adresse)"
    );
    

    $stmt->bindParam(':id_taxe_sejour', $id_taxe_sejour);
    $stmt->bindParam(':libelle_logement', $nom);
    $stmt->bindParam(':tarif_de_base', $tarif_de_base);
    $stmt->bindParam(':accroche', $accroche);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':nature', $nature);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':nb_chambres', $nb_chambres);
    $stmt->bindParam(':nb_lit_simple', $nb_lit_simple);
    $stmt->bindParam(':nb_lit_double', $nb_lit_double);
    $stmt->bindParam(':nb_sdb', $nb_sdb);
    $stmt->bindParam(':surface_maison', $surface_maison);
    $stmt->bindParam(':nb_personne_max', $nb_personne_max);
    $stmt->bindParam(':surface_jardin', $surface_jardin);
    $stmt->bindParam(':balcon', $balcon);
    $stmt->bindParam(':terrasse', $terrasse);
    $stmt->bindParam(':parking_public', $parking_public);
    $stmt->bindParam(':parking_privee', $parking_privee);
    $stmt->bindParam(':sauna', $sauna);
    $stmt->bindParam(':hammam', $hammam);
    $stmt->bindParam(':piscine', $piscine);
    $stmt->bindParam(':climatisation', $climatisation);
    $stmt->bindParam(':jacuzzi', $jacuzzi);
    $stmt->bindParam(':television', $television);
    $stmt->bindParam(':wifi', $wifi);
    $stmt->bindParam(':lave_vaisselle', $lave_vaiselle);
    $stmt->bindParam(':code_planning', $code_planning);
    $stmt->bindParam(':lave_linge', $lave_linge);
    $stmt->bindParam(':image1', $nom_image_principale);
    $stmt->bindParam(':en_ligne', $en_ligne, PDO::PARAM_BOOL);
    $stmt->bindParam(':id_proprietaire', $id_proprietaire);
    $stmt->bindParam(':id_adresse', $id_adresse);

    $stmt->execute();
    $id_logement = $dbh->lastInsertId();

    if ($linge != ""){
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._services_compris (logement,nom_service)
                VALUES (:logement,:nom_service)"
        );
    
        $stmt->bindParam(':logement',$id_logement );
        $stmt->bindParam(':nom_service', $linge);
        $stmt->execute();
    }

    if ($navette != ""){
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._services_compris (logement,nom_service)
                VALUES (:logement,:nom_service)"
        );
    
        $stmt->bindParam(':logement',$id_logement );
        $stmt->bindParam(':nom_service', $navette);
        $stmt->execute();
    }

    if ($menage != ""){
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._services_compris (logement,nom_service)
                VALUES (:logement,:nom_service)"
        );
    
        $stmt->bindParam(':logement',$id_logement );
        $stmt->bindParam(':nom_service', $menage_service);
        $stmt->execute();
    }

    $stmt = $dbh->prepare("INSERT INTO locbreizh._possede_charges_associee_logement
    values($charges1, $id_logement, 'menage');");
    $stmt->execute();

    $stmt = $dbh->prepare("INSERT INTO locbreizh._possede_charges_associee_logement
    values($charges2, $id_logement, 'animaux');");
    $stmt->execute();

    $stmt = $dbh->prepare("INSERT INTO locbreizh._possede_charges_associee_logement
    values($charges3, $id_logement, 'personnes_supplementaires');");
    $stmt->execute();

    if (isset($nom_image2)) {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image2)'
        );
        $stmt->bindParam(':image2', $nom_image2);
        $stmt->execute();

        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo, numero)
                VALUES (:logement, :image2, :numero)'
        );
        $n = 2;
        $stmt->bindParam(':image2', $nom_image2);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->bindParam(':numero', $n);
        $stmt->execute();
    }

    if (isset($nom_image3)) {
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image3)"
        );
        $stmt->bindParam(':image3', $nom_image3);
        $stmt->execute();

        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo, numero)
                VALUES (:logement, :image3, :numero)'
        );
        $n = 3;
        $stmt->bindParam(':image3', $nom_image3);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->bindParam(':numero', $n);
        $stmt->execute();
    }

    if (isset($nom_image4)) {
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image4)"
        );
        $stmt->bindParam(':image4', $nom_image4);
        $stmt->execute();

        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo,numero)
                VALUES (:logement, :image4, :numero)'
        );
        $n = 4;
        $stmt->bindParam(':image4', $nom_image4);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->bindParam(':numero', $n);
        $stmt->execute();
    }

    if (isset($nom_image5)) {
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image5)"
        );
        $stmt->bindParam(':image5', $nom_image5);
        $stmt->execute();


        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo,numero)
                VALUES (:logement, :image5, :numero)'
        );
        $n = 5;
        $stmt->bindParam(':image5', $nom_image5);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->bindParam(':numero', $n);
        $stmt->execute();
    }

    if (isset($nom_image6)) {
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image6)"
        );
        $stmt->bindParam(':image6', $nom_image6);
        $stmt->execute();

        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo,numero)
                VALUES (:logement, :image6, :numero)'
            
        );
        $n = 6;
        $stmt->bindParam(':image6', $nom_image6);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->bindParam(':image6', $nom_image6);
        $stmt->bindParam(':numero', $n);
        $stmt->execute();
    }


    unset($_SESSION['post_logement']);

    header("Location: ../Accueil/Tableau_de_bord.php");
?>
