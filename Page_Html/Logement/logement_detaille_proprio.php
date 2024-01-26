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
    <script src="../scriptPopup.js"></script>
</head>

<body>
<?php 
        // appel script pour header
        include('../header-footer/choose_header.php');
    ?>
<main>
        <div>
        <?php

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
                <h3 class="logtitreP"><?php echo $info['accroche_logement'];?></h3>
                <div class="logrowb">
                    <div class="">
                        <h3 class="policetitre"><?php echo $info['libelle_logement']; ?></h3>
                    </div>
                </div>


                <div class="logrowb">
                    <div class="logcolumn">
                        <div class="slider-container">
                            <div class="slider">
                                <div class="slide">
                                        <img class="photosecondaireP" src="../Ressources/Images/<?php echo $info['photo_principale'];?> ">
                                    </div><?php
                                for ($i = 0 ; $i < 5; $i++) {
                                    if (isset($photos_secondaires[$i]['photo'])){?>
                                        <div class="slide">
                                            <img src="../Ressources/Images/<?php echo $photos_secondaires[$i]['photo'];?>">
                                        </div><?php
                                    }
                                };?>
                            </div>

                            <div class="controls">
                                <button class="left"><img src="../svg/arrow-left.svg"></button>
                                <ul></ul>
                                <button class="right"><img src="../svg/arrow-right.svg"></button>
                            </div>
                        </div>
                    </div>
                    <div class="logcolumn logdem">
                        <h3 class="policetitre">Description</h3>
                        <p class="description-detail"><?php echo $info['descriptif_logement']; ?></p>
                        <?php /*<p>Arrivée echo $info['debut_plage_ponctuelle'] Départ echo $info['fin_plage_ponctuelle'] </p>*/ ?> 
                    </div>
                </div>

                <div class="logrowb">
                    <div class="logcolumn">
                        <h3 class="policetitres">Calendrier</h3>
                        <div class="corpsCalendrier">
                            <div class="fondP">
                                <div class="teteCalendrier">
                                    <div class="fleches flechesP">
                                        <svg id="precedent" xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14">
                                            <path fill="#274065" d="m2.828 7 4.95 4.95-1.414 1.415L0 7 6.364.637 7.778 2.05 2.828 7Z"/>
                                        </svg>
                                    </div>
                                    <p class="date_actuelle date_actuelleP"></p>
                                </div>
                                <div class="calendrier">
                                    <ul class="semaines semainesP">
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
                            <div class="fondP">
                                <div class="teteCalendrier">
                                    <p class="date_actuelle date_actuelleP"></p>
                                    <div class="fleches flechesP">
                                        <svg id="suivant" xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14">
                                            <path fill="#274065" d="m2.828 7 4.95 4.95-1.414 1.415L0 7 6.364.637 7.778 2.05 2.828 7Z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="calendrier">
                                    <ul class="semaines semainesP">
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

                    <div class="logdem">
                        <div class="logrowb" id="datesPlage">
                            <p class="dateresa demresaP"></p>
                            <p class="dateresa demresaP"></p>
                        </div>
                        <form method="post">
                            <button class="btn-demlognoP" type="submit" disabled>Demander un devis</button>
                            <div class="logrowt">
                                <p class="nuit"><?php echo $info['tarif_base_ht'];?> €/nuit</p>
                            </div>
                        </form>
                    </div>
    
                </div>


                   
                <div class="logI">
                <h3 class="policetitres">Informations du logement</h3>
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
                        lave_vaisselle,
                        nb_personnes_logement
                        FROM locbreizh._logement
                        where id_logement = {$_GET['logement']}"
                    );
                    $stmt->execute();
                    $info = $stmt->fetch();
                ?>
                <div class="logrow">
                    <div class="logcp">
                        <h4 class="potitres">Equipements</h4>
                        <p><img src="../svg/tree-fill.svg"> jardin   <?php  echo $info['jardin']; ?> m<sup>2</sup></p>
                        <?php
                        if ($info['balcon'] == true) {
                            ?><p><img src="../svg/balcon.svg"><?php  echo 'Balcon'; ?></p><?php
                        }

                        if ($info['terrasse'] == true) {
                            ?><p><img src="../svg/terasse.svg"><?php  echo 'Terrasse'; ?></p><?php
                        }
                        if ($info['parking_privee'] == true) {
                            ?><p><img src="../svg/PARKING.svg"><?php  echo 'Parking privée'; ?></p><?php
                        }

                        if ($info['parking_public'] == true) {
                            ?><p><img src="../svg/PARKING.svg"><?php  echo 'Parking public'; ?></p><?php
                        }
                        if ($info['television'] == true) {
                            ?><p><img src="../svg/TELEVISION.svg"><?php  echo 'Television'; ?></p><?php
                        }
                        if ($info['wifi'] == true) {
                            ?><p><img src="../svg/WIFI.svg"><?php  echo 'Wifi'; ?></p><?php
                        }
                        if ($info['lave_linge'] == true) {
                            ?><p><img src="../svg/contrast-drop-2-fill.svg"><?php  echo 'Lave-linge'; ?></p><?php
                        }
                        if ($info['lave_vaisselle'] == true) {
                            ?><p><img src="../svg/CUISINE.svg"><?php  echo 'Cuisine équipée'; ?></p><?php
                        }
                        
                        ?>
                    </div>
                    <hr class="hr">
                    <div class="logcp">
                        <h4 class="potitres">Installations</h4>
                        <?php

                        if ($info['climatisation'] == true) {
                            ?><p><img src="../svg/windy-line.svg"><?php  echo 'Climatisation'; ?></p><?php
                        }
                        if ($info['piscine'] == true) {
                            ?><p><img src="../svg/PISCINE.svg"> <?php  echo 'Piscine'; ?></p><?php
                        }

                        if ($info['sauna'] == true) {
                            ?><p><img src="../svg/PISCINE.svg"><?php  echo 'Sauna'; ?></p><?php
                        }

                        if ($info['hammam'] == true) {
                            ?><p><img src="../svg/PISCINE.svg"><?php  echo 'Hammam'; ?></p><?php
                        }

                        if ($info['jacuzzi'] == true) {
                            ?><p><img src="../svg/PISCINE.svg"><?php  echo 'Jacuzzi'; ?></p><?php
                        }
                        ?>
                    </div>
                </div>
                <hr class="hr">
                <div class="logrow">
                    <div class="logcp">
                        <p><img src="../svg/CHAMBRE.svg"> <?php  echo $info['lit_simple'] ?> lit(s) simple(s)</p>
                        <p><img src="../svg/CHAMBRE.svg"><?php  echo $info['lit_double'] ?> lit(s) double(s)</p>
                        <p><img src="../svg/ruler.svg" width="24px" height="24px"><?php echo $info['surface_logement'];?>m<sup>2<sup></p>
                    </div>
                    <div class="logcp">
                        <p><img src="../svg/CHAMBRE.svg"><?php  echo $info['nb_chambre'] ?> chambre(s)</p>
                        <p><img src="../svg/SALLE_DE_BAIN.svg"><?php  echo $info['nb_salle_bain'] ?> salle(s) de bain</p>
                        <p><img src="../svg/group.svg" width="24px" height="24px"><?php echo $info['nb_personnes_logement'];?> personnes  </p>
                    </div>
                </div>
        </div>
                


        <script src="./scriptCalendrier.js"></script>



        <?php
            try {
                $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                $code = $dbh->prepare("SELECT code_planning FROM locbreizh._planning NATURAL JOIN locbreizh._logement WHERE id_logement = {$_GET['logement']};");

                $code->execute();

                $code = $code->fetch()['code_planning'];

                $plageDispo = $dbh->prepare("SELECT prix_plage_ponctuelle, jour_plage_ponctuelle FROM locbreizh._plage_ponctuelle INNER JOIN locbreizh._plage_ponctuelle_disponible
                ON _plage_ponctuelle.id_plage_ponctuelle = _plage_ponctuelle_disponible.id_plage_ponctuelle WHERE code_planning = {$code} ;");
                $plageDispo->execute();
                $plageDispo = $plageDispo->fetchAll();

            } catch (PDOException $e) {
                print "Erreur !:" . $e->getMessage() . "<br/>";
                die();
            }
        ?>

        <script>
            //Appel de la fonction pour créer les calendriers
            afficherCalendrier("inactif");

            changerDates();

            var tab = <?php echo json_encode($plageDispo); ?>;
            var tabRes = [];
            var tabMotif = [];
            for (i=0 ; i < tab.length; i++){
                split = tab[i]["jour_plage_ponctuelle"];
                part1 = split.split('-')[1];
                if (part1[0] == '0'){
                    part1 = part1[1];
                }
                part2 = split.split('-')[2];
                if (part2[0] == '0'){
                    part2 = part2[1];
                }
                tabRes[i] = part1 + "/" + part2 + "/" + split.split('-')[0];
                tabMotif[i] = tab[i]["prix_plage_ponctuelle"];
            }
            afficherPlages(tabRes, "normal", tabMotif, "D");
            if(document.getElementById(tabRes[0])){
                changerJour(tabRes[0]);
            }
        </script>
        
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
        </div>

        <hr class="hr">
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
       
    </main>
    
    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>

</html>
<script src="caroussel.js" defer></script>