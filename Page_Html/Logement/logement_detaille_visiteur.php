<?php 
session_start();
include('../parametre_connexion.php');
try {
$dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    print "Erreur !:" . $e->getMessage() . "<br/>";
    die();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page détaillé d'un logement</title>
    <link rel="stylesheet" href="../style.css">
</head>


<body>
<header>
        <div style="display:flex; flex-direction:row;">
            <img class="logot" src="../svg/logo.svg">
            <h2>Loc'Breizh</h2>
        </div>

        <div class="brecherche">
            <img src="../svg/filtre.svg">
            <input id="searchbar" type="text" name="search">
            <img src="../svg/loupe.svg">
        </div>
        

    <div>
        <a href="../messagerie/messagerie.php"><img src="../svg/message.svg"></a>
        <a onclick="openPopup()"><img id="pp" src="../Ressources/Images/<?php echo $photo['photo']; ?>"></a> 
    </div>
    <div id="popup" class="popup">
        <a href="">Accéder au profil</a>
        <br>
        <a href="../Compte/seDeconnecter.php">Se déconnecter</a>
        <a onclick="closePopup()">Fermer la fenêtre</a>
    </div>
</header>
    <main>
        <div class='infos'>
        <?php

                $stmt = $dbh->prepare(
                    "SELECT libelle_logement, nb_personnes_logement, nature_logement, tarif_base_ht, photo_principale, accroche_logement, descriptif_logement
                    from locbreizh._logement 
                    WHERE id_logement = {$_GET['logement']};");

                /*$stmt = $dbh->prepare(
                    "SELECT libelle_logement, nb_personnes_logement, nature_logement, tarif_base_ht, note_avis, photo_principale, photo, accroche_logement, descriptif_logement, debut_plage_ponctuelle, fin_plage_ponctuelle
                    from locbreizh._logement 
                        INNER JOIN locbreizh._avis ON logement = id_logement
                        INNER JOIN locbreizh._photos_secondaires p ON p.logement = id_logement
                        INNER JOIN locbreizh._planning ON _planning.code_planning = _logement.code_planning
                        INNER JOIN locbreizh._plage_ponctuelle ON _planning.code_planning = _plage_ponctuelle.code_planning
                        WHERE id_logement = {$_GET['logement']};"
                );*/


            $stmt->execute();
            $info = $stmt->fetch();
            echo '<div class="card">';
            echo '<h3>' . $info['libelle_logement'] . '</h3>';
            echo '<p>' . $info['nb_personnes_logement'] . ' personnes</p>';
            echo '<p>' . $info['nature_logement'] . '</p>';
            echo '<p>' . $info['tarif_base_ht'] . '€/nuit</p>';
            /*echo '<img src="/Ressources/Images/star-fill 1.svg">' . '<h4>' .  $info['note_avis'] . ',0</p>';
            */echo '<img src="../Ressources/Images/' . $info['photo_principale'] . '">';/*
            echo '<img src="' . $info['photo_url'] . '">';
            echo '<img src="' . $info['photo_url'] . '">';*/
            echo '<h3>' . 'Description' . '</h3>' . '<p>' . $info['accroche_logement'] . '<p>';
            echo '<p>' . $info['descriptif_logement'] . '</p>';/*
            echo '<p>' . 'Arrivée' . $info['debut_plage_ponctuelle'] . 'Départ' . $info['fin_plage_ponctuelle'] . '</p>';*/
            ?>
            <form action="../Redirection/redirection_visiteur_demande_devis.php?logement=<?php echo $_GET['logement']; ?>" method="post">
                <button type="submit">Demander un devis</button>
            </form>
            
        </div>
        <div class='voir_plus'>
            <hr>
            <h4>Voir plus</h4>
            <img src='../svg/arrow-down-s-line (1) 1.svg'>
            <hr>
        </div>

        <div class="service_equipement">
            <h3>Services et équipements du logement</h3>
            <?php


                $stmt = $dbh->prepare(
                    'SELECT nb_chambre, nb_salle_bain, lave_vaisselle, wifi, piscine, sauna, hammam, climatisation, jacuzzi, television, lave_linge, parking_public, parking_privee, balcon, terrasse, jardin FROM locbreizh._logement'
                );


            $stmt->execute();
            $info = $stmt->fetch();
            echo '<p>' . $info['nb_chambre'] . ' Chambres' . '</p>';
            echo '<p>' . $info['nb_salle_bain'] . ' Salles de bain' . '</p>';
            if ($info['lave_vaisselle'] == true) {
                echo '<p>' . 'Cuisine équipée' . '</p>';
            }

            if ($info['wifi'] == true) {
                echo '<p>' . 'Wifi inclus' . '</p>';
            }

            if ($info['piscine'] == true) {
                echo '<p>' . 'Piscine incluse' . '</p>';
            }

            if ($info['sauna'] == true) {
                echo '<p>' . 'Sauna inclus' . '</p>';
            }

            if ($info['hammam'] == true) {
                echo '<p>' . 'Hammam inclus' . '</p>';
            }

            if ($info['jacuzzi'] == true) {
                echo '<p>' . 'Jacuzzi inclus' . '</p>';
            }

            if ($info['climatisation'] == true) {
                echo '<p>' . 'Climatisation incluse' . '</p>';
            }

            if ($info['television'] == true) {
                echo '<p>' . 'Television inclus' . '</p>';
            }

            if ($info['lave_linge'] == true) {
                echo '<p>' . 'Lave-linge inclus' . '</p>';
            }

            if ($info['parking_privee'] == true) {
                echo '<p>' . 'Parking privée inclus' . '</p>';
            }

            if ($info['parking_public'] == true) {
                echo '<p>' . 'Parking public inclus' . '</p>';
            }

            if ($info['balcon'] == true) {
                echo '<p>' . 'Balcon inclus' . '</p>';
            }

            if ($info['terrasse'] == true) {
                echo '<p>' . 'Terrasse incluse' . '</p>';
            }

            echo '<p>' . 'Surface du jardin : ' . $info['jardin'] . 'm2' . '</p>';
            ?>
            <hr>
            <h3>Calendrier</h3>
            <hr>
            <!--
            <h3>Avis</h3>
            <?php
            /*try {
                include('../parametre_connexion.php');

                $dbh2 = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                $dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $dbh2->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                $stmt = $dbh2->prepare(
                    'SELECT id_avis, note_avis
                        from locbreizh._logement 
                            INNER JOIN locbreizh._avis ON logement = id_logement'
                );
            } catch (PDOException $e) {
                print "Erreur !:" . $e->getMessage() . "<br/>";
                die();
            }

            $stmt->execute();
            $info = $stmt->fetch();
            echo '<img src="/Ressources/Images/star-fill 1.svg">' . '<h4>' .  $info['note_avis'] . ',0</p>';

            $nb_avis = $info['id_avis'];
            $tab_avis[] = $nb_avis;

            foreach ($tab_avis as $boucle => $nb_avis) {
                $boucle++;
            }
            echo '<p>' . '. ' . $boucle . ' commentaires' . '</p>';*/
            ?>
        </div>

        <div class='avis_card'>
            <?php /*


                $stmt = $dbh->prepare(
                    'SELECT nom, prenom, note_avis, contenu_avis
                                        from locbreizh._avis
                                            INNER JOIN locbreizh._compte ON auteur = id_compte'
                );


            $stmt->execute();
            //$info = $stmt->fetch();
            foreach ($stmt->fetchAll() as $info) {
                echo '<img src="/Ressources/Images/compte.svg>';
                echo '<h4>' . $info['nom'] . ' ' . $info['prenom'] . '</h4>';
                echo '<img src="/Ressources/Images/star-fill 2.svg">' . '<h4>' .  $info['note_avis'] . ',0</p>';
                echo '<p>' . $info['contenu_avis'] . '</p>';
            }

            */?>Dubois
            <a href=''>Répondre au commentaire</a>
            <a href=''>Signaler</a>

            <div class='voir_plus'>
                <hr>
                <h4>Voir plus</h4>
                <img src='../svg/arrow-down-s-line (1) 1.svg'>
                <hr>
            </div>
            <hr>
        </div> 
        -->


        <div class='localisation'>
            <h3>Localisation</h3>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d83998.77845041982!2d2.2644625084947463!3d48.85893831264307!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1f06e2b70f%3A0x40b82c3688c9460!2sParis!5e0!3m2!1sfr!2sfr!4v1697885937861!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

            <?php


                $stmt = $dbh->prepare(
                    'SELECT _logement.id_adresse, ville, nom_rue, numero_rue, pays
                        from locbreizh._logement
                            INNER JOIN locbreizh._adresse ON _logement.id_adresse = _adresse.id_adresse'
                );


            $stmt->execute();
            $info = $stmt->fetch();
            echo '<p>' . $info['numero_rue'] . ' ' . $info['nom_rue'] . '</p>';
            echo '<p>' . $info['ville'] . '</p>';
            echo '<p>' . $info['pays'] . '</p>';
            
            ?>
            <hr>
        </div>
            <div class='condition'>
                <h3>Conditions du logement</h3>
                <div class='annulation_card'>
                    <h4>Conditions d'annulation</h4>
                    <p>Culpa officia magna sit duis cillum laborum. Et labore fugiat ad ullamco excepteur nisi commodo nisi cupidatat nulla. Esse eu fugiat id veniam ipsum et dolor sint ullamco incididunt quis irure nulla. Mollit exercitation officia pariatur velit ullamco. Pariatur ipsum proident proident consectetur magna proident tempor ex commodo officia.
                    </p>
                    <a href=''>Voir plus</a>
                </div>

                <div class='paiement_card'>
                    <h4>Conditions de paiement</h4>
                    <p>Culpa officia magna sit duis cillum laborum. Et labore fugiat ad ullamco excepteur nisi commodo nisi cupidatat nulla. Esse eu fugiat id veniam ipsum et dolor sint ullamco incididunt quis irure nulla. Mollit exercitation officia pariatur velit ullamco. Pariatur ipsum proident proident consectetur magna proident tempor ex commodo officia.
                    </p>
                    <a href=''>Voir plus</a>
                </div>

                <div class='arrived_card'>
                    <h4>Informations d'arrivée</h4>
                    <p>Culpa officia magna sit duis cillum laborum. Et labore fugiat ad ullamco excepteur nisi commodo nisi cupidatat nulla. Esse eu fugiat id veniam ipsum et dolor sint ullamco incididunt quis irure nulla. Mollit exercitation officia pariatur velit ullamco. Pariatur ipsum proident proident consectetur magna proident tempor ex commodo officia.
                    </p>
                    <a href=''>Voir plus</a>
                </div>

                <div class='coming_card'>
                    <h4>Informations de départ</h4>
                    <p>Culpa officia magna sit duis cillum laborum. Et labore fugiat ad ullamco excepteur nisi commodo nisi cupidatat nulla. Esse eu fugiat id veniam ipsum et dolor sint ullamco incididunt quis irure nulla. Mollit exercitation officia pariatur velit ullamco. Pariatur ipsum proident proident consectetur magna proident tempor ex commodo officia.
                    </p>
                    <a href=''>Voir plus</a>
                </div>

                <h4>Veuillez consultez</h4>
                <a href=''>Le réglemement intérieur</a>
                <hr>
            </div>

            <div class='profil'>
            <?php   
                        $stmt = $dbh->prepare(
                                'SELECT prenom, nom, photo
                                    from locbreizh._logement
                                        INNER JOIN locbreizh._compte ON _logement.id_proprietaire = _compte.id_compte'
                            );

                        $stmt->execute();
                        $info = $stmt->fetch();
                ?>
                <img src='<?php echo '../Ressources/Images/'.$info['photo'];?>'>
                <h4><?php echo "{$info['prenom']}  {$info['nom']}";?></h4>
                
                
            <form action="../Redirection/redirection_visiteur_messagerie.php" method="post">
                <button type="submit" disabled>Contacter le propriétaire</button>
            </form>

    </main>
    <?php 
        echo file_get_contents('../header-footer/footer.html');
    ?>
</body>

</html>