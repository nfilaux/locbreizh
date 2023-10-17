<?php session_start(); ?>

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
    $row = $stmt->fetch();
?>
<body>
    <!--potentiel manière sans js-->
    <form action="messagerie.php" method="post">
            <input type="submit" name="divClic" value="Div 3">
    </form>

    <header>
        <nav>
            <div id="logo">
                <img src="image/logo.svg">
                <p>Loc’Breizh</p>
            </div>
            <img src="image/filtre.svg">
            <form name="formulaire" method="post" action="recherche.php" enctype="multipart/form-data">
                <input type="search" id="recherche" name="recherche" placeholder="Rechercher"><br>
                <input type="image" id="loupe" alt="loupe" src="image/loupe.svg" />
            </form>
            <div>
                <img src="image/reserv.svg">
                <a href="liste_reservations.html">Accéder à mes réservations</a>
            </div>
            <div id="parametre">
                <a href="messagerie.php"><img src="image/messagerie.svg"></a>
                <a href="compte.php"><img src=<?php echo $row['url_photo']; ?>></a>
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
                            ROW_NUMBER() OVER (PARTITION BY c.id_conversation ORDER BY m.date_mess DESC, m.heure_mess DESC) AS message_rank
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
                    $rows = $stmt->fetchAll();

                    foreach($rows as $row){?>

                <div>
                    <img src=<?php echo $row['photo_autre_compte'];?> alt="image de profil">
                    <p><?php echo $row['prenom_autre_compte'] . " " . $row['nom_autre_compte']; ?></p>
                    <p><?php
                        //on cree un objet date pour changer sa forme
                        $date = new DateTime($row['date_mess']);
                        // le format jour mois
                        $date_formatee = $date->format('j F');
                        // on coupe la fin du mois
                        $mois_cut = substr($date_formatee, 3, 4) . ".";
                        echo $date->format('j ') . $mois_cut;
                    ?></p>
                    <p><?php echo substr($row['contenu_message'], 0, 30); ?></p>
                </div>
                <?php }?>
            </div>
        </div>
        <!--partie de droite-->
        <div>
            <!--infos conv-->
            <div>
                <img src=<?php ?> alt="image de profil">
                <p>Prenom NOM</p>
            </div>
            <!--contenu conversation-->
            <div>
                <!--un seul message-->
                <div>
                    <img src="image/compte.svg" alt="photo de profil">
                    <!--contenu message + date... -->
                    <div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin id auctor lacus. Vivamus
                            ornare purus sit amet lacinia porta. Nullam feugiat rhoncus convallis. Vivamus turpis
                            ligula, fringilla a neque</p>
                        <p>15/09/2023 11:47</p>
                    </div>
                </div>
                <div>
                    <img src="image/compte.svg" alt="photo de profil">
                    <!--contenu message + date... -->
                    <div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin id auctor lacus. Vivamus
                            ornare purus sit amet lacinia porta. Nullam feugiat rhoncus convallis. Vivamus turpis
                            ligula, fringilla a neque</p>
                        <p>15/09/2023 11:47</p>
                    </div>
                </div>
                <div>
                    <img src="image/compte.svg" alt="photo de profil">
                    <!--contenu message + date... -->
                    <div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin id auctor lacus. Vivamus
                            ornare purus sit amet lacinia porta. Nullam feugiat rhoncus convallis. Vivamus turpis
                            ligula, fringilla a neque</p>
                        <p>15/09/2023 11:47</p>
                    </div>
                </div>
                <!--champ pour ecrire le message-->
                <div>
                    <form name="envoie_message" method="post" action="envoyer_message.php"
                        enctype="multipart/form-data">
                        <input type="text" id="message" name="message" placeholder="Envoyer un message"><br>
                        <input type="image" id="envoie" alt="envoie" src="image/envoyer.svg" />
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <!--partie haite du footer-->
        <div>
            <a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a>
            <a href="tel:+33623455689">(+33) 6 23 45 56 89</a>
            <div>
                <img src="image/instagram.svg" alt="logo instagram">
                <a href="https://www.instagram.com/LocBreizh/">@LocBreizh</a>
            </div>
            <div>
                <img src="image/facebook.svg" alt="logo facebook">
                <a href="https://fr-fr.facebook.com/LocBreizh/">@LocBreizh</a>
            </div>
        </div>
        <hr>
        <!--partie basse du footer-->
        <div>
            <p>©2023 Loc’Breizh</p>
            <a href="">Conditions générales</a>
            <p>Developpé par <a href="">7ème sens</a></p>
        </div>
    </footer>
</body>
</html>