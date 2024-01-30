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
    <main class="mainacc">
        <div class="section-filters">
            <p class="acc-accroche">Rechercher votre logement selon votre critère</p>
            <div class="filters">
                <form action="filtrage.php" method="post" class="menu-filtre" onsubmit="return verifierChamps()">
                <div class="fil">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label for="prix_min">min<img src="../svg/money.svg" width="12" height="12"></label>
                        </div>
                        <input type="number" id="prix_min" name="prix_min" placeholder="<?php if (isset($_GET['prixMin'])){echo $_GET['prixMin'];} else {echo 0;} ?>" min="0"/>
                    </div>  
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label for="prix_max">max<img src="../svg/money.svg" width="12" height="12"></label>
                        </div>
                        <input type="number" id="prix_max" name="prix_max" placeholder="<?php if (isset($_GET['prixMax'])){echo $_GET['prixMax'];} else {echo 0;} ?>" min="0"/>
                    </div>
                    <div class="input-group-ville">
                        <div class="input-group-prepend">
                            <label for="lieu"><img src="../svg/map-pin-line.svg" width="25" height="25"></label>
                        </div>
                        <input type="text" id="lieu" name="lieu" placeholder="<?php if (isset($_GET['lieu'])){echo $_GET['lieu'];} else {echo 'Ville';} ?>"/> 
                    </div>
                    <div class="input-group-pers">
                        <div class="input-group-prepend">
                            <label for="personne"><img src="../svg/group.svg" width="25" height="25"></label>
                        </div>
                        <input type="number" id="personne" name="personne" placeholder="<?php if (isset($_GET['voyageurs'])){echo $_GET['voyageurs'];} else {echo 0;} ?>"/>   
                    </div>
                    <div class="input-group-proprio">
                        <div class="input-group-prepend">
                            <label for="proprietaire"><img src="../svg/home-office-fill.svg" width="25" height="25"></label>
                        </div>
                        <input type="text" id="proprietaire" name="proprietaire" placeholder="<?php if (isset($_GET['proprio'])){echo $_GET['proprio'];} else {echo 'Nom de propriétaire';} ?>"/>   
                    </div>
                    <button class="btn-fill" type="submit" id="filtrage">Filtrer</button>
                    </div>
                        
                    <?php if (isset($_GET['erreur'])){ ?>
                        <p class='err'>Le prix min doit être inférieur au prix max</p>
                    <?php }?>
                </form>
            </div>
        </div>
        
        <!-- Champs de séléction des Tris -->
        <select class="triage" id="tri" name="tri">
            <option value="none" hidden> Trier par : choisir tri</option>
            <option value="vide">Aucun tri</option> <!-- Retirer le tri actif -->
            <option value="prix_c">Prix (croissant)</option>
            <option value="prix_d">Prix (décroissant)</option>
        </select>

        <?php
        $filtre ='';
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
                    "SELECT photo_principale, libelle_logement, tarif_base_ht, nb_personnes_logement, id_logement, en_ligne, ville, code_postal
                    from locbreizh._logement l JOIN locbreizh._adresse ON l.id_adresse = _adresse.id_adresse
                    ORDER BY $tri;"
                );
            } else if(sizeof($_GET)>0){
                if (sizeof($_GET)==1){
                    foreach ($_GET as $NomFiltre => $choix) {
                        switch($NomFiltre){
                            case 'prixMin' :    $filtre = "WHERE tarif_base_ht>=$choix;";  break;
                            case 'prixMax' :    $filtre = "WHERE tarif_base_ht<=$choix;"; break;
                            case 'lieu' :       $filtre = "WHERE a.ville='$choix';"; break;
                            case 'proprio' :    $filtre = "JOIN locbreizh._proprietaire p ON l.id_proprietaire = p.id_proprietaire JOIN locbreizh._compte c ON p.id_proprietaire = c.id_compte WHERE LOWER(c.nom) = LOWER('$choix');"; break;
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
                    "SELECT
                    l.photo_principale,
                    l.libelle_logement,
                    l.tarif_base_ht,
                    l.nb_personnes_logement,
                    l.id_logement,
                    l.en_ligne,
                    a.ville,
                    a.code_postal
                    FROM locbreizh._logement l
                    JOIN locbreizh._adresse a ON l.id_adresse = a.id_adresse $filtre"
                );
            } else {
                // récupération des données de logement dans la base de donné
                $stmt = $dbh->prepare('SELECT photo_principale, libelle_logement, tarif_base_ht, nb_personnes_logement, id_logement, en_ligne, ville, code_postal 
                    FROM locbreizh._logement l
                    JOIN locbreizh._adresse a ON l.id_adresse = a.id_adresse ;'
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
            $res = $stmt->fetchAll();
            // affichage des données de logement
            if (count($res)<=0){ ?> 
                <p class="center" style="font-size: 1.5em;">Aucun logement trouvé</p>
    <?php   }
            foreach ($res as $card) {
                if ($card['en_ligne'] == true) {?>
                    <!-- <section class=""> 
                        <a href="../Logement/logement_detaille_visiteur.php?logement=<?php /*echo $card['id_logement'] ?>"> 
                            <div class="cardtel">
                                <article><img src="../Ressources/Images/<?php echo $card['photo_principale'] ?>" width="300" height="200"></article>
                                    <div class="cardphone">
                                        <article> <h3> <?php echo $card['libelle_logement']; ?></h3></article>
                                        <article> <p> <?php echo $card['ville'] . ", " . $card['code_postal']; ?></p></article>
                                        <article>
                                            <div class="accicone"><img src="../svg/money.svg" width="25" height="25"> 
                                                <h4><?php echo $card['tarif_base_ht']; ?> €</h4></div>
                                            <div class="accicone">
                                                <h4><?php echo $card['nb_personnes_logement']; */?> personnes</h4>
                                            </div>
                                        </article>
                                    </div>
                                </article>
                            </div>
                        </a>
                    </section> -->
                    <article class="logementCard cardtel">
                        <a href="../Logement/logement_detaille_visiteur.php?logement=<?php echo $card['id_logement'] ?>"> 
                            <img src="../Ressources/Images/<?php echo $card['photo_principale'] ?>">
                            <div class="infoContainer">
                                <div class="mainInfos">
                                    <span class="logementTitre"> <?php echo $card['libelle_logement']; ?></span>
                                    <span> <?php echo $card['ville'] . ", " . $card['code_postal']; ?></span>
                                </div>
                                <div class="otherInfos cardphone">
                                    <div>
                                        <img src="../svg/money.svg" width="25" height="25">
                                        <span><?php echo $card['tarif_base_ht']; ?> € </span>
                                    </div>
                                    <div>
                                        <img src="../svg/group.svg" width="25" height="25">
                                        </span><?php echo $card['nb_personnes_logement'];?> personnes</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
        <?php   } 
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