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
    <script src="./scriptCalendrier.js" defer></script>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
</head>

<body>
    <?php 
        include('../header-footer/choose_header.php');
    ?>


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
            $info = $stmt->fetch();
            $stmt = $dbh->prepare(
                "SELECT photo
                from locbreizh._photos_secondaires 
                WHERE logement = {$_GET['logement']};"
            );
            $stmt->execute();
            $photos_secondaires = $stmt->fetchAll();
            
            ?>
            <div class="logpc">
                <h3 class="logtitre"><?php echo $info['accroche_logement'];?></h3>
                <div class="logrowb">
                    <div class="logrowt">
                        <h3 class="policetitre"><?php echo $info['libelle_logement']; ?></h3>
                        <p>Logement de <?php echo $info['surface_logement'];?> m<sup>2</sup> pour <?php echo $info['nb_personnes_logement'];?> personnes  </p>
                    </div>
                    <div class="logrowt">
                        <p class="nuit"><?php echo $info['tarif_base_ht'];?> €/nuit</p>
                        <!--
                        <img src="/Ressources/Images/star-fill 1.svg"><h4> echo $info['note_avis'];,0</p>
                        -->
                    </div>
                </div>
                <div class="detailimg">
                    <?php
                        $cpt = 1;
                        for ($i = 0 ; $i <5; $i++){
                            if (isset($photos_secondaires[$i]['photo'])){
                                $cpt ++;
                            }
                        }
                        switch($cpt){
                            case 1 : ?><img class="photoprincipal" src="../Ressources/Images/<?php echo $info['photo_principale'];?> "><?php
                                     break;
                            case 2 : ?><img class="photosecondaireP" src="../Ressources/Images/<?php echo $info['photo_principale'];?> "><?php
                                    for ($i = 0 ; $i < 5; $i++) {
                                        if (isset($photos_secondaires[$i]['photo'])){
                                            ?><img class="photosecondaireP" src="../Ressources/Images/<?php echo $photos_secondaires[$i]['photo'];?>"><?php
                                        }
                                    } break; 
                            case 3 : ?><img class="photosecondaireP" src="../Ressources/Images/<?php echo $info['photo_principale'];?> "><?php
                                     for ($i = 0 ; $i < 5; $i++) {
                                            if (isset($photos_secondaires[$i]['photo'])){
                                                ?><img class="photosecondaireI" src="../Ressources/Images/<?php echo $photos_secondaires[$i]['photo'];?>"><?php
                                            }
                                        } break;
                            case 4 : ?><img class="photosecondaireP" src="../Ressources/Images/<?php echo $info['photo_principale'];?> "><?php
                                    for ($i = 0 ; $i < 5; $i++) {
                                        if (isset($photos_secondaires[$i]['photo'])){
                                            ?><img class="photosecondaireP" src="../Ressources/Images/<?php echo $photos_secondaires[$i]['photo'];?>"><?php
                                        }
                                    } break;
                            case 5 :?><img class="photosecondaireP5" src="../Ressources/Images/<?php echo $info['photo_principale'];?> "> <div class="imgsecondaire"><?php 
                                for ($i = 0 ; $i < 5; $i++) {
                                    if (isset($photos_secondaires[$i]['photo'])){
                                        ?><img class="photosecondaireI5" src="../Ressources/Images/<?php echo $photos_secondaires[$i]['photo'];?>"><?php
                                    }
                                } ?> </div> <?php break;
                            case 6 : ?><img class="photosecondaireP6" src="../Ressources/Images/<?php echo $info['photo_principale'];?> "><?php
                                    for ($i = 0 ; $i < 5; $i++) {
                                        if (isset($photos_secondaires[$i]['photo'])){
                                            ?><img class="photosecondaireP6" src="../Ressources/Images/<?php echo $photos_secondaires[$i]['photo'];?>"><?php
                                        }
                                    } break;
                        }
                        
                        ?>
                </div>
                <div class="logrowt">  
                <div class="logcolumn">
                        <h3 class="policetitre">Description</h3>
                        <p class="description-detail"><?php echo $info['descriptif_logement']; ?></p>
                        <?php /*<p>Arrivée echo $info['debut_plage_ponctuelle'] Départ echo $info['fin_plage_ponctuelle'] </p>*/ ?> 
                    </div>
                    <div class="logdem">
                        <div class="logrowb" id="datesPlage">
                            <p class="dateresa"></p>
                            <p class="dateresa"></p>
                        </div>
<<<<<<< HEAD
                        <form action="../Redirection/redirection_visiteur_demande_devis.php?logement=<?php echo $_GET['logement']; ?>" method="post">
                            <button class="btn-demlog" type="submit" >Demander un devis</button>
=======
                        <form class="center" action="../demande_devis/demande_devis.php?logement=<?php echo $_GET['logement']; ?>" method="post">
                            <input type='hidden' name="arrive" id="arrive" value="">
                            <input type='hidden' name="depart" id="depart" value="">
                            <button class="btn-demlog" type="submit">Demander un devis</button>
>>>>>>> 147c43b08d6913062bd32cd3f280fce69e60360f
                        </form>
                    </div>
                </div>
            </div>
        
            <div class="logrow">
            <div class="logcolumn">
                <h3 class="potitre">Services et équipements du logement :</h3>
                <?php
                    $stmt = $dbh->prepare(
                        "SELECT 
                        surface_logement,
                        nb_chambre,
                        lit_simple,
                        lit_double,
                        nb_salle_bain,
                        jardin,
                        balcon,
                        terrasse,
                        parking_public,
                        parking_privee,
                        sauna,
                        hammam,
                        piscine,
                        climatisation,
                        jacuzzi,
                        television,
                        wifi,
                        lave_linge,
                        lave_vaisselle
                        FROM locbreizh._logement
                        where id_logement = {$_GET['logement']}"
                    );
                    $stmt->execute();
                    $info = $stmt->fetch();
                ?>

<div class="logrow">
                    <div class="logcp">
                        <p><?php  echo $info['nb_chambre'] ?> chambre(s)</p>
                        <p><?php  echo $info['nb_salle_bain'] ?> salle(s) de bain</p>
                        <h4 class="potitres">Equipements</h4>
                        <p>jardin   <?php  echo $info['jardin']; ?> m<sup>2</sup></p>
                        <?php
                        if ($info['balcon'] == true) {
                            ?><p><?php  echo 'Balcon'; ?></p><?php
                        }

                        if ($info['terrasse'] == true) {
                            ?><p><?php  echo 'Terrasse'; ?></p><?php
                        }
                        if ($info['parking_privee'] == true) {
                            ?><p><?php  echo 'Parking privée'; ?></p><?php
                        }

                        if ($info['parking_public'] == true) {
                            ?><p><?php  echo 'Parking public'; ?></p><?php
                        }
                        if ($info['television'] == true) {
                            ?><p><?php  echo 'Television'; ?></p><?php
                        }
                        if ($info['wifi'] == true) {
                            ?><p><?php  echo 'Wifi'; ?></p><?php
                        }
                        if ($info['lave_linge'] == true) {
                            ?><p><?php  echo 'Lave-linge'; ?></p><?php
                        }
                        if ($info['lave_vaisselle'] == true) {
                            ?><p><?php  echo 'Cuisine équipée'; ?></p><?php
                        }
                        
                        ?>
                    </div>
                    <div class="logcp">
                        <p><?php  echo $info['lit_simple'] ?> lit(s) simple(s)</p>
                        <p><?php  echo $info['lit_double'] ?> lit(s) double(s)</p>
                        <h4 class="potitres">Installations</h4>
                        <?php

                        if ($info['climatisation'] == true) {
                            ?><p><?php  echo 'Climatisation'; ?></p><?php
                        }
                        if ($info['piscine'] == true) {
                            ?><p><?php  echo 'Piscine'; ?></p><?php
                        }

                        if ($info['sauna'] == true) {
                            ?><p><?php  echo 'Sauna'; ?></p><?php
                        }

                        if ($info['hammam'] == true) {
                            ?><p><?php  echo 'Hammam'; ?></p><?php
                        }

                        if ($info['jacuzzi'] == true) {
                            ?><p><?php  echo 'Jacuzzi'; ?></p><?php
                        }
                        ?>
                    </div>
                </div>
                
            </div>
            <hr class="hr">
            <div class="logcolumn">
                <h3 class="potitre">Calendrier</h3>
                <div class="corpsCalendrier">
                    <div class="fond">
                        <div class="teteCalendrier">
                            <div class="fleches">
                                <svg id="precedent" xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14">
                                    <path fill="#745086" d="m2.828 7 4.95 4.95-1.414 1.415L0 7 6.364.637 7.778 2.05 2.828 7Z"/>
                                </svg>
                            </div>
                            <p class="date_actuelle"></p>
                        </div>
                        <div class="calendrier">
                            <ul class="semaines">
                                <li>Lun</li>
                                <li>Mar</li>
                                <li>Mer</li>
                                <li>Jeu</li>
                                <li>Ven</li>
                                <li>Sam</li>
                                <li>Dim</li>
                            </ul>
                            <ul class="jours"></ul>
                        </div>
                    </div>
                    <div class="fond">
                        <div class="teteCalendrier">
                            <p class="date_actuelle"></p>
                            <div class="fleches">
                                <svg id="suivant" xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14">
                                    <path fill="#745086" d="m2.828 7 4.95 4.95-1.414 1.415L0 7 6.364.637 7.778 2.05 2.828 7Z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="calendrier">
                            <ul class="semaines">
                                <li>Lun</li>
                                <li>Mar</li>
                                <li>Mer</li>
                                <li>Jeu</li>
                                <li>Ven</li>
                                <li>Sam</li>
                                <li>Dim</li>
                            </ul>
                            <ul class="jours"></ul>
                        </div>
                    </div>
                </div>
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
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1364671.57561899!2d-4.397375693978974!3d48.08372166501683!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4811ca61ae7e8eaf%3A0x10ca5cd36df24b0!2sBretagne!5e0!3m2!1sfr!2sfr!4v1702909132704!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php
                $stmt = $dbh->prepare(
                    'SELECT ville, nom_rue, numero_rue
                    from locbreizh._logement
                    natural JOIN locbreizh._adresse
                    where id_logement = :id'
                );
                $stmt->bindParam(':id', $_GET['logement']);


            $stmt->execute();
            $info = $stmt->fetch();
            ?>
            <p><?php echo 'Adresse : ' . $info['numero_rue'] . ' ' . $info['nom_rue'] . ' ' . $info['ville'] ?></p>   
            
        </div>
        <hr>
            <div>
                <h3 class="policetitre">Conditions du logement</h3>

                <div class="logrow">
                    <div class="cardcondition">
                        <h4 class="potitre">Conditions d'annulation</h4>
                        <p>Culpa officia magna sit duis cillum laborum. Et labore fugiat ad ullamco excepteur nisi commodo nisi cupidatat nulla. Esse eu fugiat id veniam ipsum et dolor sint ullamco incididunt quis irure nulla. Mollit exercitation officia pariatur velit ullamco. Pariatur ipsum proident proident consectetur magna proident tempor ex commodo officia.
                        </p>
                    </div>

                    <div class="cardcondition">
                        <h4 class="poetitre">Conditions de paiement</h4>
                        <p>Culpa officia magna sit duis cillum laborum. Et labore fugiat ad ullamco excepteur nisi commodo nisi cupidatat nulla. Esse eu fugiat id veniam ipsum et dolor sint ullamco incididunt quis irure nulla. Mollit exercitation officia pariatur velit ullamco. Pariatur ipsum proident proident consectetur magna proident tempor ex commodo officia.
                        </p>
                    </div>
                </div>
                <div class="logrow">
                <div class="cardcondition">
                    <h4 class="potitre">Informations d'arrivée</h4>
                    <p>Culpa officia magna sit duis cillum laborum. Et labore fugiat ad ullamco excepteur nisi commodo nisi cupidatat nulla. Esse eu fugiat id veniam ipsum et dolor sint ullamco incididunt quis irure nulla. Mollit exercitation officia pariatur velit ullamco. Pariatur ipsum proident proident consectetur magna proident tempor ex commodo officia.
                    </p>
                </div>

                <div class="cardcondition">
                    <h4 class="potitre">Informations de départ</h4>
                    <p>Culpa officia magna sit duis cillum laborum. Et labore fugiat ad ullamco excepteur nisi commodo nisi cupidatat nulla. Esse eu fugiat id veniam ipsum et dolor sint ullamco incididunt quis irure nulla. Mollit exercitation officia pariatur velit ullamco. Pariatur ipsum proident proident consectetur magna proident tempor ex commodo officia.
                    </p>
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
                    <a href=""><button class="btn-accueil" type='button'>Contacter le propriétaire</button></a>
                </div>
            </div>

    </main>
    
    <?php 
        echo file_get_contents('../header-footer/footer.html');
    ?>
</body>
</html>
