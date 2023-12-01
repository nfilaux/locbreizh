<?php
    // lancement de la session
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link rel="stylesheet" href="../style.css">
</head>
<?php
    // inclusion d'une instance PDO
    include('../parametre_connexion.php');
    try {
        // prend les parametres de connexion dans le fichiers importés plus haut
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }

    // requete pour obtenir la photo de profil pour le header
    $stmt = $dbh->prepare("SELECT * from locbreizh._compte join locbreizh._photo on locbreizh._compte.photo = locbreizh._photo.url_photo where locbreizh._compte.id_compte = '{$_SESSION['id']}';");
    $stmt->execute();
    $photo_profil = $stmt->fetch();

    $stmt = $dbh->prepare("SELECT id_compte from locbreizh._compte c join locbreizh._client on c.id_compte = id_client where id_compte = {$_SESSION['id']} ;");
    $stmt->execute();
    $est_client = $stmt->fetch();

    if(isset($est_client['id_compte'])){
        $est_client = True;
    }
    else{
        $est_client = False;
    }



    $stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = {$_SESSION['id']};");
    $stmt->execute();
    $photo = $stmt->fetch();

    // requete pour recuperer la liste des conversations
    $stmt = $dbh->prepare("
                    WITH messageOrdre AS (
                        SELECT
                            c.id_conversation,
                            CASE
                                WHEN c.compte1 = '{$_SESSION['id']}' THEN c.compte2
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
                                WHEN c.compte1 = '{$_SESSION['id']}' THEN c.compte2
                                ELSE c.compte1
                            END
                        )
                        LEFT JOIN locbreizh._message m ON c.id_conversation = m.conversation
                        WHERE
                            c.compte1 = '{$_SESSION['id']}' OR c.compte2 = '{$_SESSION['id']}'
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
    // recuperation des lignes dans la variable test_conv
    $liste_conv = $stmt->fetchAll();
    
    //test pour savoir si un conversation est selectionnee (pour changer les messages à afficher)
    if(!isset($liste_conv[0])){
        $selectionne = NULL;
    }
    else if(!isset($_GET['conv'])){
        // selectionne la première conversation du resultat des requetes
        $selectionne = $liste_conv[0]['id_conversation'];
    }
    else{
        // selectionne la conv donnée en get
        $selectionne = $_GET['conv'];
    }
    // requete pour recuperer la liste des messages de la conversation selectionnee

    if($selectionne != NULL){
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
        // sotck les lignes de la requete dans liste_message
        $liste_message = $stmt->fetchAll();

        $stmt = $dbh->prepare("SELECT * from locbreizh._message_demande;");
        $stmt->execute();
        $liste_message_demande = $stmt->fetchAll();

        $stmt = $dbh->prepare("SELECT * from locbreizh._message_devis;");
        $stmt->execute();
        $liste_message_devis= $stmt->fetchAll();
    }
    
?>
<body>
<?php 
        include('../header-footer/choose_header.php');
    ?>
    <main class="messrowb">
        <!--partie de gauche de la page (liste des conversations)-->
        <div class="messcolumnconv">
            <!--barre de recherche (pour le filtre)-->
            <div class="messrow">
                <img src="../svg/filtre.svg">
                <form name="formulaire" method="post" action="recherche_conv.php" enctype="multipart/form-data">
                    <div class="messrow">
                        <input type="search" id="recherche_conv" name="recherche_conv" placeholder="Rechercher"><br>
                        <input type="image" id="loupe" alt="loupe" src="../svg/loupe.svg" />
                    </div>
                </form>
            </div>
            <!--liste conversations-->
            <div>
                <?php
                    // affiche une par une les conversations dans une div
                    // permet de stocker les id des conversations que l'on ajoute
                    $tab_id_conv = [];
                    foreach($liste_conv as $conv){
                        // ajoute les id
                        $tab_id_conv[] = $conv['id_conversation'];
                        ?>
                <div class="messcard">
                    <a href=<?php echo "?conv=" . $conv['id_conversation'];?>>
                        <!--image de profil-->
                        <img src=<?php echo '../Ressources/Images/' . $conv['photo_autre_compte'];?> alt="image de profil">
                        <!--prenom, nom-->
                        <p><?php echo $conv['prenom_autre_compte'] . " " . $conv['nom_autre_compte']; ?></p>
                        <!--date de la conversation-->
                        <p><?php
                            //on cree un objet date pour changer sa forme
                            $date = new DateTime($conv['date_mess']);
                            // le format jour mois
                            $date_formatee = $date->format('j F');
                            // on coupe la fin du mois
                            $mois_cut = substr($date_formatee, 3, 4) . ".";
                            echo $date->format('j ') . $mois_cut;
                        ?></p>
                        <!--on limite la taille du dernier message à afficher-->
                        <p><?php echo substr($conv['contenu_message'], 0, 30); ?></p>
                    </a>
                </div>
                <?php }?>
            </div>
        </div>

        <hr>
        <!--partie de droite (liste des messages de la conversation selectionnee)-->
        <div class="messcolumn">
            <?php
                // si il y a au moins une conversation
                if(count($tab_id_conv) != 0 ){?>
                    <!--affichage entete conversation (image de profil + prenom, nom)-->
                    <div>
                        <img src=<?php
                        // test pour connaitre quel photo de profil afficher
                        if($liste_message[0]['compte1'] === $_SESSION['id']){
                            echo '../Ressources/Images/' . $liste_message[0]['photo2']; 
                        }
                        else{
                            echo '../Ressources/Images/' . $liste_message[0]['photo1']; 
                        }?> alt='image de profil' width="150px" height="150px">

                        <p><?php 
                        // affichage du nom, prenom
                        if($liste_message[0]['compte1'] === $_SESSION['id']){
                            echo $liste_message[0]['prenom2'] . " " . $liste_message[0]['nom2']; 
                        }
                        else{
                            echo $liste_message[0]['prenom1'] . " " . $liste_message[0]['nom1'];  
                        }
                        ?></p>
                    </div>
                    <div>
                    <?php 
                        // affichage de la liste des messages avec les infos asscoiées
                        foreach($liste_message as $message){
                            echo in_array($message['id_message'], $liste_message_demande);
                            if($message['auteur'] === $message['compte1']){
                                $photo_mess = $message['photo1'];
                                
                            }
                            else{
                                $photo_mess = $message['photo2'];
                            }
                            ?>                      
                            <!--un seul message-->
                            <div>
                                <img src=<?php echo '../Ressources/Images/' . $photo_mess; ?> alt="photo de profil" width="150px" height="150px">
                                <!--contenu message + date... -->
                                <div>
                                    <div>
                                        <p><?php
                                            echo $message['contenu_message'];
                                            // on regarde si est le message est un message de demande de devis ou un devis
                                            $est_demande = false;
                                            $est_devis = false;
                                            foreach ($liste_message_demande as $message_demande) {
                                                if ($message_demande['id_message_demande'] === $message['id_message']) {
                                                    $est_demande = true;
                                                    echo " : <a href='../demande_devis/pdf_demande/{$message_demande['lien_demande']}' target=\"_blank\">voir la demande de devis</a>";
                                                }
                                            }
                                            foreach ($liste_message_devis as $message_devis) {
                                                if ($message_devis['id_message_devis'] === $message['id_message']) {
                                                    $est_devis = true;
                                                    echo " : <a href='../devis/pdf_devis/{$message_devis['lien_devis']}' target=\"_blank\">voir le devis</a>";
                                                }
                                            }
                                        ?></p>
                                        <?php 
                                            if($est_demande){
                                                $stmt = $dbh->prepare("SELECT accepte from locbreizh._message_demande m where m.id_message_demande = {$message['id_message']};");
                                                $stmt->execute();
                                                $statut = $stmt->fetch();

                                                $stmt = $dbh->prepare("SELECT id_compte from locbreizh._compte c join locbreizh._client on c.id_compte = id_client where id_compte = {$_SESSION['id']} ;");
                                                $stmt->execute();
                                                $est_client = $stmt->fetch();

                                                if(isset($est_client['id_compte'])){
                                                    $est_client = True;
                                                }
                                                else{
                                                    $est_client = False;
                                                }
                                                
                                                ?>
                                                <form method="post" action="accepter_demande.php?message=<?php echo $message['id_message']; ?>">
                                                    <button type="submit" <?php if(isset($statut['accepte']) || $est_client){ echo 'disabled';} ?>>Accepter</button>
                                                </form>
                                                <form method="post" action="refuser_demande.php?message=<?php echo $message['id_message']; ?>">
                                                    <button type="submit" <?php if(isset($statut['accepte']) || $est_client){ echo 'disabled';} ?>>Refuser</button>
                                                </form>   
                                        <?php } ?>
                                        <?php 
                                            if($est_devis){
                                                $stmt = $dbh->prepare("SELECT accepte from locbreizh._message_devis m where m.id_message_devis = {$message['id_message']};");
                                                $stmt->execute();
                                                $statut = $stmt->fetch();

                                                $stmt = $dbh->prepare("SELECT id_compte from locbreizh._compte c join locbreizh._client on c.id_compte = id_client where id_compte = {$_SESSION['id']} ;");
                                                $stmt->execute();
                                                $est_client = $stmt->fetch();

                                                $devis_annule = $statut['accepte']; 


                                                if(isset($est_client['id_compte'])){
                                                    $est_client = True;
                                                }
                                                else{
                                                    $est_client = False;
                                                }
                                                ?>
                                                <form method="post" action="accepter_devis.php?message=<?php echo $message['id_message']; ?>">
                                                    <button type="submit" <?php if(isset($statut['accepte']) || !$est_client || $devis_annule){ echo 'disabled';} ?>>Accepter</button>
                                                </form>
                                                <form method="post" action="refuser_devis.php?message=<?php echo $message['id_message']; ?>">
                                                    <button type="submit" <?php if(isset($statut['accepte']) || !$est_client || $devis_annule){ echo 'disabled';} ?>>Refuser</button>
                                                </form>
                                                <form method="post" action="annuler_devis.php?message=<?php echo $message['id_message']; ?>">
                                                    <button type="submit" <?php if(isset($statut['accepte']) || $est_client || $devis_annule){ echo 'disabled';} ?>>Annuler</button>
                                                </form>
                                        <?php } ?>
                                    </div>
                                    <p><?php 
                                        $date = explode('-', $message['date_mess']);
                                        echo $date[2] .'/' . $date[1] .'/' . $date[0] . ' ' . substr($message['heure_mess'], 0, 5); 
                                    ?> </p>
                                </div>
                            </div>
                        <?php
                    }?>
                    <hr>
                    <!--champ pour ecrire le message-->
                    <div>
                        <form name="envoie_message" method="post" action="envoyer_message.php" enctype="multipart/form-data">
                            <input type="text" id="message" name="message" placeholder="Envoyer un message"><br>
                            <input type="image" id="envoie" alt="envoie" src="../svg/envoyer.svg" />
                        </form>
                    </div>
                    </div>
            <?php }?>
        </div>
    </main>
    <?php
        echo file_get_contents('../header-footer/footer.html');
    ?>
    
</body>

</html>
