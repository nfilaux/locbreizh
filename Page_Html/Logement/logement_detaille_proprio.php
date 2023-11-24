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
</head>

<body>
<header>
    <a href="../Accueil/Tableau_de_bord.php">
        <img class="logot" src="../svg/logo.svg">
        <h2>Loc'Breizh</h2>
    </a>
        <div class="brecherche">
            <img src="../svg/filtre.svg">
            <input id="searchbar" type="text" name="search">
            <img src="../svg/loupe.svg">
        </div>

        <img src="../svg/booklet-fill 1.svg">
        <a href="../Accueil/Tableau_de_bord.php"><h4>Accéder à mon tableau de bord</h4></a>

        <div class="imghead">
            <a href="../messagerie/messagerie.php"><img src="../svg/message.svg"></a>
            <a onclick="openPopup()"><img id="pp" class="imgprofil" src="../Ressources/Images/<?php echo $photo['photo']; ?>" width="50" height="50"></a> 
        </div>
        <div id="popup" class="popup">
        <a href="">Accéder au profil</a>
        <br>
        <a href="../Compte/SeDeconnecter.php">Se déconnecter</a>
        <a onclick="closePopup()">Fermer la fenêtre</a>
    </div>
    </header>
<main>
        <div>
        <?php
                $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                $stmt = $dbh->prepare(
                    "SELECT libelle_logement, nb_personnes_logement, surface_logement, tarif_base_ht, photo_principale, accroche_logement, descriptif_logement
                    from locbreizh._logement 
                    WHERE id_logement = {$_GET['logement']};"
                );

                /*$stmt = $dbh->prepare(
                    "SELECT libelle_logement, nb_personnes_logement, surface_logement, tarif_base_ht, note_avis, photo_principale, photo, accroche_logement, descriptif_logement, debut_plage_ponctuelle, fin_plage_ponctuelle
                    from locbreizh._logement 
                        INNER JOIN locbreizh._avis ON logement = id_logement
                        INNER JOIN locbreizh._photos_secondaires p ON p.logement = id_logement
                        INNER JOIN locbreizh._planning ON _planning.code_planning = _logement.code_planning
                        INNER JOIN locbreizh._plage_ponctuelle ON _planning.code_planning = _plage_ponctuelle.code_planning
                        WHERE id_logement = {$_GET['logement']};"
                );*/


            $stmt->execute();
            $info = $stmt->fetch();?>
            <div class="logpc">
                <h3 class="logtitre"><?php echo $info['accroche_logement'];?></h3>
                <div class="logrowb">
                    <div class="logrowt">
                        <h3 class="policetitre"><?php echo $info['libelle_logement']; ?></h3>
                        <p>pour <?php echo $info['nb_personnes_logement'];?>  personnes</p>
                        <p>logement de <?php echo $info['surface_logement'];?> m<sup>2</sup> </p>
                    </div>
                    <div class="logrowt">
                        <p class="nuit"><?php echo $info['tarif_base_ht'];?> €/nuit</p>
                        <!--
                        <img src="/Ressources/Images/star-fill 1.svg"><h4> echo $info['note_avis'];,0</p>
                        -->
                    </div>
                </div>
                <img src="../Ressources/Images/<?php echo $info['photo_principale'];?> ">

                <!-- 
                <img src="<?php// echo $info['photo_url'];?> ">
                <img src="<?php// echo $info['photo_url'];?> ">
                -->
                <div class="logrowt">  
                    <div class="logcolumn">
                        <h3 class="policetitre">Description</h3>
                        <p><?php echo $info['descriptif_logement']; ?></p>
                        <?php /*<p>Arrivée echo $info['debut_plage_ponctuelle'] Départ echo $info['fin_plage_ponctuelle'] </p>*/ ?>
                        <a href="" class='voir_plus'>
                            <hr> 
                            <h4>Voir plus</h4> 
                            <hr>
                        </a>
                    </div>
                    <div class="logc">
                        <a class="center"><button class="btn-demlogno">Demander un devis</button></a>
                    </div>
                </div>
            </div>
        

        <div class="logrow">
            <div class="logcolumn">
                <h3 class="potitre">Services et équipements du logement</h3>
                <?php
                    $stmt = $dbh->prepare(
                        'SELECT nb_chambre, nb_salle_bain, lave_vaisselle, wifi, piscine, sauna, hammam, climatisation, jacuzzi, television, lave_linge, parking_public, parking_privee, balcon, terrasse, jardin FROM locbreizh._logement'
                    );
                    $stmt->execute();
                    $info = $stmt->fetch();
                ?>

                <div class="logrow">
                    <div class="logcp">
                        <p><?php  echo $info['nb_chambre'] ?> Chambres</p><?php
                        if ($info['lave_vaisselle'] == true) {
                            ?><p><?php  echo 'Cuisine équipée'; ?></p><?php
                        }

                        if ($info['wifi'] == true) {
                            ?><p><?php  echo 'Wifi inclus'; ?></p><?php
                        }

                        if ($info['piscine'] == true) {
                            ?><p><?php  echo 'Piscine incluse'; ?></p><?php
                        }

                        if ($info['sauna'] == true) {
                            ?><p><?php  echo 'Sauna inclus'; ?></p><?php
                        }

                        if ($info['hammam'] == true) {
                            ?><p><?php  echo 'Hammam inclus'; ?></p><?php
                        }

                        if ($info['jacuzzi'] == true) {
                            ?><p><?php  echo 'Jacuzzi inclus'; ?></p><?php
                        }

                        if ($info['climatisation'] == true) {
                            ?><p><?php  echo 'Climatisation incluse'; ?></p><?php
                        }?>
                    </div>
                    <div class="logcp">
                        <p><?php  echo $info['nb_salle_bain'] ?> Salles de bain</p><?php
                        if ($info['television'] == true) {
                            ?><p><?php  echo 'Television inclus'; ?></p><?php
                        }

                        if ($info['lave_linge'] == true) {
                            ?><p><?php  echo 'Lave-linge inclus'; ?></p><?php
                        }

                        if ($info['parking_privee'] == true) {
                            ?><p><?php  echo 'Parking privée inclus'; ?></p><?php
                        }

                        if ($info['parking_public'] == true) {
                            ?><p><?php  echo 'Parking public inclus'; ?></p><?php
                        }

                        if ($info['balcon'] == true) {
                            ?><p><?php  echo 'Balcon inclus'; ?></p><?php
                        }

                        if ($info['terrasse'] == true) {
                            ?><p><?php  echo 'Terrasse incluse'; ?></p><?php
                        }?>
                    </div>
                </div>
                <p>Surface du jardin : <?php  echo $info['jardin']; ?> m<sup>2</sup></p>
            </div>
            <hr class="hr">
            <div class="logcolumn">
                <h3 class="potitre">Calendrier</h3>
            </div>
        </div>
        
        <div>
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

            ?>
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
                Dubois
                <a href=''>Répondre au commentaire</a>
                <a href=''>Signaler</a>

                <div >
                    <hr>
                    <h4>Voir plus</h4>
                    <img src='../svg/arrow-down-s-line (1) 1.svg'>
                    <hr>
                </div>
                <hr>
            -->
            
        </div>

        <hr>
        <div class="logcarte">
            <h3 class="policetitre">Localisation</h3>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d83998.77845041982!2d2.2644625084947463!3d48.85893831264307!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1f06e2b70f%3A0x40b82c3688c9460!2sParis!5e0!3m2!1sfr!2sfr!4v1697885937861!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

            <?php
                $stmt = $dbh->prepare(
                    'SELECT _logement.id_adresse, ville, nom_rue, numero_rue, pays
                        from locbreizh._logement
                            INNER JOIN locbreizh._adresse ON _logement.id_adresse = _adresse.id_adresse'
                );


            $stmt->execute();
            $info = $stmt->fetch();
            ?>
            <p><?php  echo $info['numero_rue'] . ' ' . $info['nom_rue'] . ' ' . $info['ville'] . ' ' . $info['pays']; ?></p>  
            
        </div>
        <hr>
            <div>
                <h3 class="policetitre">Conditions du logement</h3>

                <div class="logrow">
                    <div class="cardcondition">
                        <h4 class="policetitre">Conditions d'annulation</h4>
                        <p>Culpa officia magna sit duis cillum laborum. Et labore fugiat ad ullamco excepteur nisi commodo nisi cupidatat nulla. Esse eu fugiat id veniam ipsum et dolor sint ullamco incididunt quis irure nulla. Mollit exercitation officia pariatur velit ullamco. Pariatur ipsum proident proident consectetur magna proident tempor ex commodo officia.
                        </p>
                        <a href="" class='voir_plusR'>
                            Voir plus  
                        </a>
                    </div>

                    <div class="cardcondition">
                        <h4 class="policetitre">Conditions de paiement</h4>
                        <p>Culpa officia magna sit duis cillum laborum. Et labore fugiat ad ullamco excepteur nisi commodo nisi cupidatat nulla. Esse eu fugiat id veniam ipsum et dolor sint ullamco incididunt quis irure nulla. Mollit exercitation officia pariatur velit ullamco. Pariatur ipsum proident proident consectetur magna proident tempor ex commodo officia.
                        </p>
                        <a href="" class='voir_plusR'>
                            Voir plus  
                        </a>
                    </div>
                </div>
                <div class="logrow">
                <div class="cardcondition">
                    <h4 class="policetitre">Informations d'arrivée</h4>
                    <p>Culpa officia magna sit duis cillum laborum. Et labore fugiat ad ullamco excepteur nisi commodo nisi cupidatat nulla. Esse eu fugiat id veniam ipsum et dolor sint ullamco incididunt quis irure nulla. Mollit exercitation officia pariatur velit ullamco. Pariatur ipsum proident proident consectetur magna proident tempor ex commodo officia.
                    </p>
                    <a href="" class='voir_plusR'>
                        Voir plus  
                    </a>
                </div>

                <div class="cardcondition">
                    <h4 class="policetitre">Informations de départ</h4>
                    <p>Culpa officia magna sit duis cillum laborum. Et labore fugiat ad ullamco excepteur nisi commodo nisi cupidatat nulla. Esse eu fugiat id veniam ipsum et dolor sint ullamco incididunt quis irure nulla. Mollit exercitation officia pariatur velit ullamco. Pariatur ipsum proident proident consectetur magna proident tempor ex commodo officia.
                    </p>
                    <a href="" class='voir_plusR'>
                        Voir plus  
                    </a>
                </div>
            </div>
            <div class="logrowc">
                <p>Veuillez consultez</p>
                <p><a href=''>Le réglemement intérieur</a></p>
            </div>    
            </div>
            <hr>
            <div class="logrowc">
            <?php   
                        $stmt = $dbh->prepare("SELECT nom,prenom,photo from locbreizh._compte JOIN locbreizh._logement ON id_compte=id_proprietaire WHERE id_logement= {$_GET['logement']} ;");

                        $stmt->execute();
                        $info = $stmt->fetch();
                ?>
                <img class="imgprofil" src="../Ressources/Images/<?php echo $info['photo']; ?>" width="100" height="100">
                <div class="logcp">
                    <h4 class="policetitre">Par <?php echo "{$info['prenom']}  {$info['nom']}";?></h4>
                    <button class="btn-accueil" type='button' disabled>Contacter le propriétaire</button>
                </div>
            </div>

    </main>
    
    <footer>
            <div class="tfooter">
                <p><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
                <p><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
                <a class="margintb" href="connexion.html"><img src="../svg/instagram.svg">  <p>@LocBreizh</p></a>
                <a  class="margintb" href="connexion.html"><img src="../svg/facebook.svg">  <p>@LocBreizh</p></a>
            </div>
            <hr>  
            <div class="bfooter">
                <p>©2023 Loc’Breizh</p>
                <p style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p>Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
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