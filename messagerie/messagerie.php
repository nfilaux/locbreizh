<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
</head>
<?php
    include('../parametre_connexion.php');
    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

    $user = '0000000001';
    $stmt = $dbh->prepare("SELECT * from locbreizh._compte join locbreizh._photo on locbreizh._compte.photo = locbreizh._photo.url_photo where locbreizh._compte.id_compte = '$user';");
    $stmt->execute();
    $photo_profil = $stmt->fetch();

    $stmt = $dbh->prepare("
                    WITH messageOrdre AS (
                        SELECT
                            c.id_conversation,
                            CASE
                                WHEN c.compte1 = '$user' THEN c.compte2
                                ELSE c.compte1
                            END AS id_autre_compte,
                            cp.nom AS nom_autre_compte,
                            cp.prenom AS prenom_autre_compte,
                            cp.photo as photo_autre_compte,
                            m.id_message,
                            m.contenu_message,
                            m.date_mess,
                            m.heure_mess,
                            (row_number() OVER (PARTITION BY c.id_conversation ORDER BY m.date_mess DESC, m.heure_mess DESC)) AS message_rank
                        FROM
                            locbreizh._conversation c
                        INNER JOIN locbreizh._compte cp ON (
                            cp.id_compte = CASE
                                WHEN c.compte1 = '$user' THEN c.compte2
                                ELSE c.compte1
                            END
                        )
                        LEFT JOIN locbreizh._message m ON c.id_conversation = m.conversation
                        WHERE
                            c.compte1 = '$user' OR c.compte2 = '$user'
                    )
                    SELECT
                        id_conversation,
                        id_autre_compte,
                        nom_autre_compte,
                        prenom_autre_compte,
                        photo_autre_compte,
                        id_message,
                        contenu_message,
                        date_mess,
                        heure_mess
                    FROM messageOrdre
                    WHERE message_rank = 1;");

    $stmt->execute();
    $liste_conv = $stmt->fetchAll();
    
    if(!isset($_GET['conv'])){
        
        $selectionne = $liste_conv[0]['id_conversation'];
    }
    else{
        $selectionne = $_GET['conv'];
    }
    $stmt = $dbh->prepare("
    SELECT c.id_conversation,
       c.compte1,
       c.compte2,
       cp1.nom as nom1,
       cp1.prenom as prenom1,
       cp1.photo as photo1,
       cp2.nom as nom2,
       cp2.prenom as prenom2,
       cp2.photo as photo2,
       m.id_message,
       m.contenu_message,
       m.date_mess,
       m.heure_mess,
       m.auteur
    FROM locbreizh._conversation c
    LEFT JOIN locbreizh._message m ON c.id_conversation = m.conversation
    INNER JOIN locbreizh._compte cp1 ON cp1.id_compte = c.compte1
    INNER JOIN locbreizh._compte cp2 ON cp2.id_compte = c.compte2
    WHERE c.id_conversation = '$selectionne'
    ORDER BY date_mess DESC,
        heure_mess DESC;");
    
    $stmt->execute();
    $liste_message = $stmt->fetchAll();
?>
<body>
    <header>
        <nav>
            <div id="logo">
                <img src="../image/logo.svg">
                <p>Loc’Breizh</p>
            </div>
            <img src="image/filtre.svg">
            <form name="formulaire" method="post" action="recherche.php" enctype="multipart/form-data">
                <input type="search" id="recherche" name="recherche" placeholder="Rechercher"><br>
                <input type="image" id="loupe" alt="loupe" src="../image/loupe.svg" />
            </form>
            <div>
                <img src="../image/reserv.svg">
                <a href="liste_reservations.html">Accéder à mes réservations</a>
            </div>
            <div id="parametre">
                <a href="messagerie.php"><img src="../image/messagerie.svg"></a>
                <a href="compte.php"><img src=<?php echo $photo_profil['url_photo']; ?>></a>
                <div>
        </nav>
    </header>
    <main>
        
        <!--partie de gauche-->
        <div>
            <!--barre de recherche-->
            <div>
                <img src="image/filtre.svg">
                <form name="formulaire" method="post" action="recherche_conv.php" enctype="multipart/form-data">
                    <input type="search" id="recherche_conv" name="recherche_conv" placeholder="Rechercher"><br>
                    <input type="image" id="loupe" alt="loupe" src="image/loupe.svg" />
                </form>
            </div>
            <!--liste conv-->
            <div>
                <?php
                    $tab_id_conv = [];
                    foreach($liste_conv as $conv){
                        // adding an underscore to not transform it into an int so we can compre it after
                        $tab_id_conv[] = '_' . $conv['id_conversation'];
                        ?>
                <div>
                    <a href=<?php echo "?conv=" . $conv['id_conversation'];?>>
                        <img src=<?php echo $conv['photo_autre_compte'];?> alt="image de profil">
                        <p><?php echo $conv['prenom_autre_compte'] . " " . $conv['nom_autre_compte']; ?></p>
                        <p><?php
                            //on cree un objet date pour changer sa forme
                            $date = new DateTime($conv['date_mess']);
                            // le format jour mois
                            $date_formatee = $date->format('j F');
                            // on coupe la fin du mois
                            $mois_cut = substr($date_formatee, 3, 4) . ".";
                            echo $date->format('j ') . $mois_cut;
                        ?></p>
                        <p><?php echo substr($conv['contenu_message'], 0, 30); ?></p>
                    </a>
                </div>
                <?php }?>
            </div>
        </div>
        
        <!--partie de droite-->
        <div>
            <!--infos conv-->
            <?php
                if(count($tab_id_conv) != 0 ){?>
                    <!--affichege entete conv-->
                    <div>
                        <img src=<?php ?> alt=<?php
                        if($liste_message[0]['compte1'] === $user){
                            echo $liste_message[0]['photo2']; 
                        }
                        else{
                            echo $liste_message[0]['photo1']; 
                        }
                        
                        ?>>
                        <p><?php 
                        if($liste_message[0]['compte1'] === $user){
                            echo $liste_message[0]['prenom2'] . " " . $liste_message[0]['nom2']; 
                        }
                        else{
                            echo $liste_message[0]['prenom1'] . " " . $liste_message[0]['nom1'];  
                        }
                        ?></p>
                    </div>
                    <div>
                    <?php 
                        foreach($liste_message as $message){
                            if($message['auteur'] === $message['compte1']){
                                $photo_mess = $message['photo1'];
                                
                            }
                            else{
                                $photo_mess = $message['photo2'];
                            }
                            ?>                      
                            <!--un seul message-->
                            <div>
                                <img src=<?php echo $photo_mess; ?> alt="photo de profil">
                                <!--contenu message + date... -->
                                <div>
                                    <p><?php echo $message['contenu_message']?></p>
                                    <p><?php echo $message['date_mess'] . ' ' . $message['heure_mess']; ?> </p>
                                </div>
                            </div>
                            
                        <?php
                    }?>
                    <!--champ pour ecrire le message-->
                    <div>
                        <form name="envoie_message" method="post" action="envoyer_message.php" enctype="multipart/form-data">
                            <input type="text" id="message" name="message" placeholder="Envoyer un message"><br>
                            <input type="image" id="envoie" alt="envoie" src="image/envoyer.svg" />
                        </form>
                    </div>
                    </div>
            <?php }?>
        </div>
    </main>
    <footer class="mt-4 container-fluid">
        <div class="mt-4 column">
            <div class="col-12 text-center">
                <a class="col-2" href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a>
                <a class="offset-md-1 col-2" href="tel:+33623455689">(+33) 6 23 45 56 89</a>
                <a class="offset-md-1 col-1" href="connexion.html"><img src="../image/instagram.svg">  @LocBreizh</a>
                <a class="offset-md-1 col-1" href="connexion.html"><img src="../image/facebook.svg">  @LocBreizh</a>
            </div>
            <hr>
            <div class="offset-md-1 col-10 mt-4 text-center row">
                <p class="offset-md-1 col-2">©2023 Loc’Breizh</p>
                <p class="offset-md-1 col-3" style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p class="offset-md-1 col-4" >Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
        </div>
    </footer>
</body>
</html>