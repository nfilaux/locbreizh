<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
</head>

<body>
    <?php 
        include('../header-footer/choose_header.php');
    ?>
    <main>
        <?php

        // récupération des données de logement dans la base de donnée
        try {
            include('../parametre_connexion.php');

            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $stmt = $dbh->prepare(
                'SELECT photo_principale, libelle_logement, tarif_base_ht, nb_personnes_logement, id_logement, en_ligne
                from locbreizh._logement;'
            );
        } catch (PDOException $e) {
            print "Erreur !:" . $e->getMessage() . "<br/>";
            die();
        }

        // fonction qui permet d'afficher la date de début et de fin d'une réservation
        function formatDate($start, $end)
        {
            $startDate = date('j', strtotime($start));
            $endDate = date('j', strtotime($end));
            $month = date('M', strtotime($end));

            return "$startDate-$endDate $month";
        }

        $stmt->execute();

        ?> <div class="card"> <?php

        // affichage des données de logement
        foreach ($stmt->fetchAll() as $card) {
            if ($card['en_ligne'] == true) {
                ?><section> <?php
                ?><a class="acclog" href="../Logement/logement_detaille_visiteur.php?logement=<?php echo $card['id_logement'] ?>"> <?php
                ?><div class="cardtel">
                <article><img src="../Ressources/Images/<?php echo $card['photo_principale'] ?>" width="300" height="200"></article><?php
                ?><div class="cardphone">
                <article>
                <h3> <?php echo $card['libelle_logement']; ?> </h3>
                </article><?php
                /*?> <img src="/Ressources/Images/star.svg">  <h4> <?php $card['note_avis']?> </h4><?php*/
                ?><article><div class="accicone">
                <img src="../svg/money.svg" width="25" height="25"> <h4><?php echo $card['tarif_base_ht']; ?> €</h4></div><?php
                /*?><h4><?php formatDate($card['debut_plage_ponctuelle'], $card['fin_plage_ponctuelle'])?></h4><?php*/
                ?><div class="accicone"><img src="../svg/group.svg" width="25" height="25"><h4><?php echo $card['nb_personnes_logement']; ?> personnes</h4></div>
                </article></div></div></a><?php
                ?></section><?php
            } /*else if ($card['en_ligne'] == false) {
                    echo "Ce logement est temporairement indisponible !";
                }*/
        }
        ?>

    </div>
    </main>
    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>

</html>
