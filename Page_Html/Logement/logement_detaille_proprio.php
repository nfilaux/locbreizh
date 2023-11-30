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
                        <textarea class="logPA" id='description' name='descriptionP' placeholder='<?php echo $info['descriptif_logement']; ?>' disabled></textarea>
                        <?php /*<p>Arrivée echo $info['debut_plage_ponctuelle'] Départ echo $info['fin_plage_ponctuelle'] </p>*/ ?>
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
                    'SELECT ville, nom_rue, numero_rue
                    from locbreizh._logement
                    natural JOIN locbreizh._adresse
                    where id_logement = :id'
                );
                $stmt->bindParam(':id', $_GET['logement']);


            $stmt->execute();
            $info = $stmt->fetch();
            ?>
            <p><?php  echo $info['numero_rue'] . ' ' . $info['nom_rue'] . ' ' . $info['ville'] ?></p>  
            
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
                    </a><div class="logrowc">
            </div>
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

    </main>
    
    <?php 
        echo file_get_contents('../header-footer/footer.html');
    ?>
</body>

</html>