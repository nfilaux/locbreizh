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
        <form action="filtrage.php" method="post" class="menu-filtre" onsubmit="return verifierChamps()">
            <div>
                <label for="prix_min">Prix min :</label>
                <input type="number" id="prix_min" name="prix_min" placeholder="<?php if (isset($_GET['prixMin'])){echo $_GET['prixMin'];} else {echo 0;} ?>" min="0"/>
            </div>
            <div>
                <label for="prix_max">Prix max :</label>
                <input type="number" id="prix_max" name="prix_max" placeholder="<?php if (isset($_GET['prixMax'])){echo $_GET['prixMax'];} else {echo 0;} ?>" min="0"/>
            </div>
            <div>
                <label for="lieu">Ville :</label>
                <input type="text" id="lieu" name="lieu" placeholder="<?php if (isset($_GET['lieu'])){echo $_GET['lieu'];} else {echo 'Ville';} ?>"/>   
            </div>
            <div>
                <label for="proprietaire">Propriétaire :</label>
                <input type="text" id="proprietaire" name="proprietaire" placeholder="<?php if (isset($_GET['proprietaire'])){echo $_GET['proprietaire'];} else {echo 'Nom de propriétaire';} ?>"/>   
            </div>
            <div>
                <label for="personne">Nombre de voyageurs :</label>
                <input type="number" id="personne" name="personne" placeholder="<?php if (isset($_GET['voyageurs'])){echo $_GET['voyageurs'];} else {echo 0;} ?>"/>   
            </div>    
            <br>
            <button type="submit" id="filtrage">Filtrer</button>
        </form>
        
        <!-- Champs de séléction des Tris -->
        <div>
            <label for="tri">Trier par :</label>
                <select class="triage" id="tri" name="tri">
                    <option value="none" hidden>Choisir tri</option>
                    <option value="vide">Aucun tri</option> <!-- Retirer le tri actif -->
                    <option value="prix_c">Prix (croissant)</option>
                    <option value="prix_d">Prix (décroissant)</option>
                </select>
        <div>

        <?php
        try {
            include('../parametre_connexion.php');
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            if (isset($_GET['tri'])){
                switch($_GET['tri']){
                    case 'prix_c' : $tri = "tarif_base_ht ASC"; break;
                    case 'prix_d' : $tri = "tarif_base_ht DESC"; break;
                }

                // récupération des données de logement dans la base de donnée avec le tri
                $stmt = $dbh->prepare(
                    "SELECT photo_principale, libelle_logement, tarif_base_ht, nb_personnes_logement, id_logement, en_ligne
                    from locbreizh._logement ORDER BY $tri;"
                );
            } else if(sizeof($_GET)>0){
                if (sizeof($_GET)==1){
                    foreach ($_GET as $NomFiltre => $choix) {
                        switch($NomFiltre){
                            case 'prixMin' :    $filtre = "WHERE tarif_base_ht>=$choix";  break;
                            case 'prixMax' :    $filtre = "WHERE tarif_base_ht<=$choix"; break;
                            case 'lieu' :       $filtre = "NATURAL JOIN locbreizh._adresse WHERE _adresse.ville='$choix'"; break;
                            case 'proprio' :    $filtre = "JOIN locbreizh._proprietaire ON _logement.id_proprietaire=_proprietaire.id_proprietaire JOIN locbreizh._compte ON _compte.id_compte=_proprietaire.id_proprietaire WHERE _compte.nom='$choix'"; break;
                            case 'voyageurs' :  $filtre = "WHERE nb_personnes_logement=$choix;"; break;
                        }
                    }
                } else {
                    $prix1 = $_GET['prixMin'];
                    $prix2 = $_GET['prixMax'];
                    $filtre = "WHERE tarif_base_ht>=$prix1 AND tarif_base_ht<=$prix2";
                }

                // récupération des données de logement dans la base de donnée avec le filtre
                $stmt = $dbh->prepare(
                    "SELECT photo_principale, libelle_logement, tarif_base_ht, nb_personnes_logement, id_logement, en_ligne
                    from locbreizh._logement $filtre;"
                );
            } else {
                // récupération des données de logement dans la base de donnée
                $stmt = $dbh->prepare(
                    'SELECT photo_principale, libelle_logement, tarif_base_ht, nb_personnes_logement, id_logement, en_ligne
                    from locbreizh._logement;'
                );
            }
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

        ?> 
        
       
        
        <div class="card"> <?php

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

<script src="./actualiserTri.js" defer></script>
<script src="./actualiserFiltre.js" defer></script>