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
$stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = {$_SESSION['id']};");
$stmt->execute();
$photo = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page détaillé d'un logement</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>




<body>
<header class="row col-12">
	<a href="../Accueil/accueil_client.php">
    <div class="row col-3">
        <img src="../svg//logo.svg">
        <h2 style="margin-top: auto; margin-bottom: auto; margin-left: 10px;">Loc'Breizh</h2>
    </div></a>

    <div class="row col-3">
        <img class="col-2" src="../svg//filtre.svg">
        <input class="col-7" id="searchbar" type="text" name="search" style="height: 50px; margin-top: auto; margin-bottom: auto;">
        <img class="col-2" src="../svg//loupe.svg">
    </div>
        <div class="row col-3 offset-md-1">
            <img src="../svg//booklet-fill 1.svg">
            <a href="../reservation/liste_reservations.php" style="margin: auto;margin-left: 10px;">
                <h4 style="color:#000;">Accèder à mes reservations</h4>
            </a>
        </div>
        

    <div class="col-2 row">
        <a href="../messagerie/messagerie.php" class="offset-md-6 row"><img src="../svg/message.svg"></a>
        <a onclick="openPopup()" class="offset-md-2 row"><img id="pp" src="../Ressources/Images/<?php echo $photo['photo']; ?>"></a> 
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


                $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                $stmt = $dbh->prepare(
                    "SELECT libelle_logement, nb_personnes_logement, nature_logement, tarif_base_ht, photo_principale, accroche_logement, descriptif_logement
                    from locbreizh._logement 
                    WHERE id_logement = {$_GET['logement']};"
                );
                $stmt->execute();
                $info = $stmt->fetch();

                $stmt = $dbh->prepare(
                    "SELECT photo
                    from locbreizh._photos_secondaires 
                    WHERE logement = {$_GET['logement']};"
                );
                $stmt->execute();
                $photos_secondaires = $stmt->fetchAll();

                /*$stmt = $dbh->prepare(
                    "SELECT libelle_logement, nb_personnes_logement, nature_logement, tarif_base_ht, note_avis, photo_principale, photo, accroche_logement, descriptif_logement, debut_plage_ponctuelle, fin_plage_ponctuelle
                    from locbreizh._logement 
                        INNER JOIN locbreizh._avis ON logement = id_logement
                        INNER JOIN locbreizh._photos_secondaires p ON p.logement = id_logement
                        INNER JOIN locbreizh._planning ON _planning.code_planning = _logement.code_planning
                        INNER JOIN locbreizh._plage_ponctuelle ON _planning.code_planning = _plage_ponctuelle.code_planning
                        WHERE id_logement = {$_GET['logement']};"
                );*/
            echo '<div class="card">';
            echo '<h3>' . $info['libelle_logement'] . '</h3>';
            echo '<p>' . $info['nb_personnes_logement'] . ' personnes</p>';
            echo '<p>' . $info['nature_logement'] . '</p>';
            echo '<p>' . $info['tarif_base_ht'] . '€/nuit</p>';
            /*echo '<img src="/Ressources/Images/star-fill 1.svg">' . '<h4>' .  $info['note_avis'] . ',0</p>';
            */echo '<img src="../Ressources/Images/' . $info['photo_principale'] . '">';
            //print_r($photos_secondaires);
            for ($i = 0 ; $i < 6 ; $i++){
                if (isset($photos_secondaires[$i]['photo'])){
                    echo '<img src="../Ressources/Images/' . $photos_secondaires[$i]['photo'] . '">';
                }
            }
            //echo '<img src="../Ressources/Images/' . $info['photo_principale'] . '">';
            /*echo '<img src="' . $info['photo_url'] . '">';
            echo '<img src="' . $info['photo_url'] . '">';*/
            echo '<h3>' . 'Description' . '</h3>' . '<p>' . $info['accroche_logement'] . '<p>';
            echo '<p>' . $info['descriptif_logement'] . '</p>';/*
            echo '<p>' . 'Arrivée' . $info['debut_plage_ponctuelle'] . 'Départ' . $info['fin_plage_ponctuelle'] . '</p>';*/
            ?>
            <a href='../demande_devis/demande_devis.php?logement=<?php echo $_GET['logement']; ?>'><button>Demander un devis</button></a>
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
            <?php


                $stmt = $dbh->prepare(
                    'SELECT nom, prenom,photo, contenu_avis
                                        from locbreizh._avis
                                            INNER JOIN locbreizh._compte ON auteur = id_compte'
                );


            $stmt->execute();

            $stmt = $dbh->prepare(
                "SELECT photo from locbreizh._compte c
                join locbreizh._logement l on l.id_proprietaire = c.id_compte
                where l.id_logement = {$_GET['logement']};"
            );
            $photo_proprio = $stmt->fetch();
            //$info = $stmt->fetch();
            foreach ($stmt->fetchAll() as $info) {
                echo '<img src="/Ressources/Images/compte.svg>';
                echo '<h4>' . $info['nom'] . ' ' . $info['prenom'] . '</h4>';
                echo '<img src="/Ressources/Images/star-fill 2.svg">' . '<h4>' .  $info['note_avis'] . ',0</p>';
                echo '<p>' . $info['contenu_avis'] . '</p>';
            }

            ?>Dubois
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
                

                <button type='button' disabled>Contacter le propriétaire</button>
            </div>

    </main>
    
    <footer class="container-fluid" >
        <div class="column">   
            <div class="text-center row">
                <p class="testfoot col-2"><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
                <p class="testfoot offset-md-2 col-2"><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
                <p class="testfoot offset-md-1 col-2"><a href="connexion.html"><img src="../svg/instagram.svg">  @LocBreizh</a></p>
                <p class="testfoot offset-md-1 col-2  "><a href="connexion.html"><img src="../svg/facebook.svg">  @LocBreizh</a></p>
            </div>
            <hr>  
            <div class="text-center row">
                <p class="offset-md-1 col-2 testfooter">©2023 Loc’Breizh</p>
                <p class="offset-md-1 col-3 testfooter" style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p class="offset-md-1 col-4 testfooter" >Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
        </div>
    </footer>
</body>

</html>


<style>
    .popup {
        display: none;
        position: fixed;
        top: 15%;
        left: 91%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }
</style>
<script>
// Ouvrir la popup
function openPopup() {
var popup = document.getElementById('popup');
popup.style.display = 'block';
}

// Fermer la popup
function closePopup() {
var popup = document.getElementById('popup');
popup.style.display = 'none';
}

// Ajouter des gestionnaires d'événements aux boutons
var profilButton = document.getElementById('profilButton');
profilButton.addEventListener('click', function() {
alert('Accéder au profil');
closePopup();
});

var deconnexionButton = document.getElementById('deconnexionButton');
deconnexionButton.addEventListener('click', function() {
alert('Se déconnecter');
closePopup();
});
</Script>
