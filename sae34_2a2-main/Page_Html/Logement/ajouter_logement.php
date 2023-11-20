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
    $en_ligne = true;
    $id_proprietaire = $_SESSION['id'];
    $nom_image_principale = $_SESSION['post_logement']['image1P'];
    $nom_image2 = $_SESSION['post_logement']['image2P'];
    $nom_image3 = $_SESSION['post_logement']['image3P'];
    $nom_image4 = $_SESSION['post_logement']['image4P'];
    $nom_image5 = $_SESSION['post_logement']['image5P'];
    $nom_image6 = $_SESSION['post_logement']['image6P'];
    $charges1 = $_SESSION['post_logement']['charges1P'];
    $charges2 = $_SESSION['post_logement']['charges2P'];
    $charges3 = $_SESSION['post_logement']['charges3P'];

    $id_photo = 0;

    function id_photo($id_photo)
    {
        return time() . $id_photo;
    }

    $nouveau_nom_image1 = id_photo($id_photo);
    $id_photo++;
    move_uploaded_file($nom_image_principale, "../Ressources/Images/" . $nouveau_nom_image1);


    $nouveau_nom_image2 = id_photo($id_photo);
    $id_photo++;
    if (isset($_SESSION['post_logement']['image2P'])) {
        move_uploaded_file($nom_image2, "../Ressources/Images/" . $nouveau_nom_image2);
    } else {
        $nom_image2 = null;
    }

    $nouveau_nom_image3 = id_photo($id_photo);
    $id_photo++;
    if ($_SESSION['post_logement']['image3P'] != "") {
        move_uploaded_file($nom_image3, "../Ressources/Images/" . $nouveau_nom_image3);
    } else {
        $nom_image3 = null;
    }

    $nouveau_nom_image4 = id_photo($id_photo);
    $id_photo++;
    if (isset($_SESSION['post_logement']['image4P'])) {
        move_uploaded_file($nom_image4, "../Ressources/Images/" . $nouveau_nom_image4);
    } else {
        $nom_image4 = null;
    }

    $nouveau_nom_image5 = id_photo($id_photo);
    $id_photo++;
    if (isset($_SESSION['post_logement']['image5P'])) {
        move_uploaded_file($nom_image5, "../Ressources/Images/" . $nouveau_nom_image5);
    } else {
        $nom_image5 = null;
    }

    $nouveau_nom_image6 = id_photo($id_photo);
    $id_photo++;
    if (isset($_SESSION['post_logement']['image6P'])) {
        move_uploaded_file($nom_image6, "../Ressources/Images/" . $nouveau_nom_image6);
    } else {
        $nom_image6 = null;
    }



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

    try {
        include('../parametre_connexion.php');

        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }

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

    $stmt->bindParam(':image1', $nouveau_nom_image1);
    $stmt->execute();



    $stmt = $dbh->prepare(
        "INSERT INTO locbreizh._logement (libelle_logement, tarif_base_HT, accroche_logement, descriptif_logement, nature_logement, type_logement, nb_chambre, lit_simple, lit_double, nb_salle_bain, surface_logement, nb_personnes_logement, jardin, balcon, terrasse, parking_public, parking_privee, sauna, hammam, piscine, climatisation, jacuzzi, television, wifi, lave_vaisselle, lave_linge, photo_principale, taxe_sejour, en_ligne, id_proprietaire, id_adresse)
            VALUES (:libelle_logement, :tarif_de_base, :accroche, :description, :nature, :type, :nb_chambres, :nb_lit_simple, :nb_lit_double, :nb_sdb, :surface_maison, :nb_personne_max, :surface_jardin, :balcon, :terrasse, :parking_public, :parking_privee, :sauna, :hammam, :piscine, :climatisation, :jacuzzi, :television, :wifi, :lave_vaisselle, :lave_linge, :image1, :id_taxe_sejour, :en_ligne, :id_proprietaire, :id_adresse)"
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
    $stmt->bindParam(':lave_linge', $lave_linge);
    $stmt->bindParam(':image1', $nouveau_nom_image1);
    $stmt->bindParam(':en_ligne', $en_ligne);
    $stmt->bindParam(':id_proprietaire', $id_proprietaire);
    $stmt->bindParam(':id_adresse', $id_adresse);

    $stmt->execute();
    $id_logement = $dbh->lastInsertId();

    $stmt = $dbh->prepare("INSERT INTO locbreizh._possede_charges_associee_logement
    values($charges1, $id_logement, 'menage');");
    $stmt->execute();

    $stmt = $dbh->prepare("INSERT INTO locbreizh._possede_charges_associee_logement
    values($charges2, $id_logement, 'animaux');");
    $stmt->execute();

    $stmt = $dbh->prepare("INSERT INTO locbreizh._possede_charges_associee_logement
    values($charges3, $id_logement, 'personnes_supplementaires');");
    $stmt->execute();

    if ($_SESSION['post_logement']['image2P'] != "") {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image2)'
        );
        $stmt->bindParam(':image2', $nouveau_nom_image2);
        $stmt->execute();
    }

    if ($_SESSION['post_logement']['image3P'] != "") {
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image3)"
        );
        $stmt->bindParam(':image3', $nouveau_nom_image3);
        $stmt->execute();
    }

    if ($_SESSION['post_logement']['image4P'] != '') {
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image4)"
        );
        $stmt->bindParam(':image4', $nouveau_nom_image4);
        $stmt->execute();
    }

    if (($_SESSION['post_logement']['image5P']) != '') {
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image5)"
        );
        $stmt->bindParam(':image5', $nouveau_nom_image5);
        $stmt->execute();
    }

    if (($_SESSION['post_logement']['image6P']) != '') {
        $stmt = $dbh->prepare(
            "INSERT INTO locbreizh._photo (url_photo)
                VALUES (:image6)"
        );
        $stmt->bindParam(':image6', $nouveau_nom_image6);
        $stmt->execute();
    }

    if (($_SESSION['post_logement']['image2P']) != '') {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo)
                VALUES (:logement, :image2)'
        );
        $stmt->bindParam(':image2', $nouveau_nom_image2);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->execute();
    }

    if (($_SESSION['post_logement']['image3P']) != '') {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo)
                VALUES (:logement, :image3)'
        );
        $stmt->bindParam(':image3', $nouveau_nom_image3);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->execute();
    }


    if (($_SESSION['post_logement']['image4P']) != '') {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo)
                VALUES (:logement, :image4)'
        );
        $stmt->bindParam(':image4', $nouveau_nom_image4);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->execute();
    }

    if (($_SESSION['post_logement']['image5P']) != '') {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo)
                VALUES (:logement, :image5)'
        );
        $stmt->bindParam(':image5', $nouveau_nom_image5);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->execute();
    }


    if (($_SESSION['post_logement']['image6P']) != '') {
        $stmt = $dbh->prepare(
            'INSERT INTO locbreizh._photos_secondaires (logement, photo)
                VALUES (:logement, :image6)'
            
        );
        $stmt->bindParam(':image6', $nouveau_nom_image6);
        $stmt->bindParam(':logement', $id_logement);
        $stmt->execute();
    }

    header("Location: ../Accueil/Tableau_de_bord.php");
?>