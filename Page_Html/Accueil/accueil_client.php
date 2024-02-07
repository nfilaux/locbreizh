<?php
// début de la session pour récupérer l'id du compte connecté
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

$stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = :id_compte;");
$stmt->bindParam(':id_compte', $_SESSION['id']);
$stmt->execute();
$photo = $stmt->fetch();
?>
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
                            <input type="number" id="prix_min" name="prix_min" placeholder="<?php if (isset($_GET['prix_min'])) {
                                                                                                echo $_GET['prix_min'];
                                                                                            } else {
                                                                                                echo 0;
                                                                                            } ?>" min="0" />
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <label for="prix_max">max<img src="../svg/money.svg" width="12" height="12"></label>
                            </div>
                            <input type="number" id="prix_max" name="prix_max" placeholder="<?php if (isset($_GET['prix_max'])) {
                                                                                                echo $_GET['prix_max'];
                                                                                            } else {
                                                                                                echo 0;
                                                                                            } ?>" min="0" />
                        </div>
                        <div class="input-group-ville">
                            <div class="input-group-prepend">
                                <label for="lieu"><img src="../svg/map-pin-line.svg" width="25" height="25"></label>
                            </div>
                            <input type="text" id="lieu" name="lieu" placeholder="<?php if (isset($_GET['lieu'])) {
                                                                                        echo $_GET['lieu'];
                                                                                    } else {
                                                                                        echo 'Ville';
                                                                                    } ?>" />
                        </div>
                        <div class="input-group-pers">
                            <div class="input-group-prepend">
                                <label for="personne"><img src="../svg/group.svg" width="25" height="25"></label>
                            </div>
                            <input type="number" id="personne" name="personne" placeholder="<?php if (isset($_GET['personne'])) {
                                                                                                echo $_GET['personne'];
                                                                                            } else {
                                                                                                echo 0;
                                                                                            } ?>" />
                        </div>
                        <div>
                            <div class="input-group-prepend">
                                <label for="date1">Début <img src="../svg/calendar.svg" width="14" height="14"></label>
                            </div>
                            <input type="date" id="date1" name="date1" value="<?php if (isset($_GET['date1'])) {echo $_GET['date1']; }?>" />
                        </div>
                        <div>
                            <div class="input-group-prepend">
                                <label for="date2">Fin <img src="../svg/calendar.svg" width="14" height="14"></label>
                            </div>
                            <input type="date" id="date2" name="date2" value="<?php if (isset($_GET['date2'])) {echo $_GET['date2']; }?>" />
                        </div>
                        <a class="btn-filtre">
                            <img src="../svg/filtre.svg" width="20" height="20" onclick="openPopup('filtre','ovFiltre')">
                        </a>
                        
                    </div>

                    <?php if (isset($_GET['erreur'])) { ?>
                        <p class='err'>Le prix min doit être inférieur au prix max</p>
                    <?php } ?>
                </form>

            </div>
        </div>


        <div id="ovFiltre" onclick="closePopup('filtre', 'ovFiltre')" class=""></div>
        <div id="filtre" class="filtrage">
            <span class="fltitre">Filtres</span><hr class="hr">
            <form action="filtrage.php" method="post">
                <h3 class="flptitre">Par type de logement</h3>
                <div class="radio-inputs">
                        <label>
                            <input class="radio-input" type="checkbox" name="typeH" value="maison" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'maison')!==false){echo 'checked';}?>>
                                <span class="radio-tile">
                                    <span class="radio-icon">
                                        <img src="../svg/house.svg" width="25" height="25">
                                    </span>
                                    <span class="radio-label">Maison</span>
                                </span>
                        </label>
                        <label>
                            <input class="radio-input" type="checkbox" name="typeH" value="appartement" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'appartement')!==false){echo 'checked';}?>>
                            <span class="radio-tile">
                                <span class="radio-icon">
                                    <img src="../svg/appartement.svg" width="25" height="25">
                                </span>
                                <span class="radio-label">Appartement</span>
                            </span>
                        </label>
                        <label>
                            <input class="radio-input" type="checkbox" name="typeH" value="chateau" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'chateau')!==false){echo 'checked';}?>>
                            <span class="radio-tile">
                                <span class="radio-icon">
                                    <img src="../svg/castle.svg" width="25" height="25">
                                </span>
                                <span class="radio-label">Château</span>
                            </span>
                        </label>
                        <label>
                            <input class="radio-input" type="checkbox" name="typeH" value="manoir" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'manoir')!==false){echo 'checked';}?>>
                            <span class="radio-tile">
                                <span class="radio-icon">
                                    <img src="../svg/manoir.svg" width="25" height="25">
                                </span>
                                <span class="radio-label">Manoir</span>
                            </span>
                        </label>
                </div>

                <hr class="hr"><h3 class="flptitre">Par équipement</h3>
                <ul class="liste-filtre">
                    <div>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="balcon" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'balcon')!==false){echo 'checked';}?>>
                                Balcon
                            </label>         
                        </li>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="Terrasse" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'Terrasse')!==false){echo 'checked';}?>>
                                Terrasse
                            </label>        
                        </li>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="piscine" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'piscine')!==false){echo 'checked';}?>>
                                Piscine
                            </label>        
                        </li>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="jardin" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'jardin')!==false){echo 'checked';}?>>
                                Jardin
                            </label>        
                        </li>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="jacuzzi" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'jacuzzi')!==false){echo 'checked';}?>>
                                Jacuzzi
                            </label>        
                        </li>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="sauna" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'sauna')!==false){echo 'checked';}?>>
                                Sauna
                            </label>        
                        </li>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="climatisation" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'climatisation')!==false){echo 'checked';}?>>
                                Climatisation
                            </label>        
                        </li>
                    </div>
                    <div>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="parkPrive" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'parkPrive')!==false){echo 'checked';}?>>
                                Parking privé
                            </label>        
                        </li>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="parkPublic" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'parkPublic')!==false){echo 'checked';}?>>
                                Parking public
                            </label>        
                        </li>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="television" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'television')!==false){echo 'checked';}?>>
                                Télévision 
                            </label>        
                        </li>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="wifi" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'wifi')!==false){echo 'checked';}?>>
                                Wifi
                            </label>        
                        </li>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="laveLinge" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'laveLinge')!==false){echo 'checked';}?>>
                                Lave linge
                            </label>        
                        </li>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="laveVaisselle" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'laveVaisselle')!==false){echo 'checked';}?>>
                                Lave vaisselle
                            </label>        
                        </li>
                        <li>
                            <label class="cyberpunk-checkbox-label">
                                <input type="checkbox" name="equipement[]" value="hammam" class="cyberpunk-checkbox" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'hammam')!==false){echo 'checked';}?>>
                                Hammam
                            </label>        
                        </li>
                    </div>
                    
                </ul>

                <hr class="hr"><h3 class="flptitre">Par service</h3>
                <div class="radio-inputs">
                    <label>
                        <input class="radio-input" type="checkbox" name="typeH" value="menage" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'menage')!==false){echo 'checked';}?>>
                            <span class="radio-tile">
                                <span class="radio-icon">
                                    <img src="../svg/nettoyage.svg" width="25" height="25">
                                </span>
                                <span class="radio-label">Ménage</span>
                            </span>
                    </label>
                    <label>
                        <input class="radio-input" type="checkbox" name="typeH" value="navette" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'navette')!==false){echo 'checked';}?>>
                            <span class="radio-tile">
                                <span class="radio-icon">
                                    <img src="../svg/taxi-fill.svg" width="25" height="25">
                                </span>
                                <span class="radio-label">Navette</span>
                            </span>
                    </label>
                    <label>
                        <input class="radio-input" type="checkbox" name="typeH" value="linge" <?php if((isset($_GET['filtre'])) && strpos($_GET['filtre'], 'linge')!==false){echo 'checked';}?>>
                            <span class="radio-tile">
                                <span class="radio-icon">
                                    <img src="../svg/t-shirt-air-line.svg" width="25" height="25">
                                </span>
                                <span class="radio-label">Linge</span>
                            </span>
                    </label>
                </div>
                <hr class="hr">
            </form>
        </div>

        <!-- Champs de séléction des Tris -->
        <select class="triage" id="tri" name="tri">
            <option value="none" hidden> Trier par : choisir tri</option>
            <option value="vide">Aucun tri</option> <!-- Retirer le tri actif -->
            <option value="prix_c">Prix (croissant)</option>
            <option value="prix_d">Prix (décroissant)</option>
        </select>

        <?php
        $filtre = '';
        try {
            include('../parametre_connexion.php');
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            
            if(isset($_GET['filtre'])){
                $mesFiltres = explode(",",$_GET['filtre']);
            }
            if(isset($_GET['tri'])){
                $monTri = $_GET['tri'];
            }
            

            if(isset($mesFiltres)||(isset($monTri))){
                // Traitement de l'argument de tri
                $tri="";
                if (isset($monTri)) {
                    switch ($monTri) {
                        case 'prix_c':
                            $tri = " ORDER BY tarif_base_ht ASC";
                            break;
                        case 'prix_d':
                            $tri = " ORDER BY tarif_base_ht DESC";
                            break;
                    }
                } 

                // Traitement des arguments de filtres
                $filtrage="";
                $join="";
                
                if (isset($mesFiltres)){
                    foreach($mesFiltres as $filtre){
                        switch($filtre){
                            // Equipements
                            case 'balcon':
                                $filtrage .= " AND l.balcon = true"; break;
                            case 'terrasse':
                                $filtrage .= " AND l.terrasse = true"; break;
                            case 'piscine':
                                $filtrage .= " AND l.piscine = true"; break;
                            case 'jardin':
                                $filtrage .= " AND l.jardin > 0"; break;
                            case 'jacuzzi':
                                $filtrage .= " AND l.jacuzzi = true"; break;
                            case 'sauna':
                                $filtrage .= " AND l.sauna = true"; break;
                            case 'parkPrive':
                                $filtrage .= " AND l.parking_privee = true"; break;
                            case 'parkPublic':
                                $filtrage .= " AND l.parking_public = true"; break;
                            case 'television':
                                $filtrage .= " AND l.television = true"; break;
                            case 'laveLinge':
                                $filtrage .= " AND l.lave_linge = true"; break;
                            case 'laveVaisselle':
                                $filtrage .= " AND l.lave_vaisselle = true"; break;
                            case 'climatisation':
                                $filtrage .= " AND l.climatisation = true"; break;
                            case 'hammam':
                                $filtrage .= " AND l.hammam = true"; break;
                            // Types d'hébergement
                            case 'maison':
                                $filtrage .= " AND l.nature_logement = 'maison'"; break;
                            case 'appartement':
                                $filtrage .= " AND l.nature_logement = 'appartement'"; break;
                            case 'manoir':
                                $filtrage .= " AND l.nature_logement = 'manoir'"; break;
                            case 'chateau':
                                $filtrage .= " AND l.nature_logement = 'chateau'"; break;
                            // Services
                            case 'menage':
                                $filtrage .= $join .=" JOIN locbreizh._service_compris s ON l.id_logement=s.logement "; " AND s.nom_service='menage'"; break;
                            case 'navette':
                                $filtrage .= $join .=" JOIN locbreizh._service_compris s ON l.id_logement=s.logement "; " AND s.nom_service='navette'"; break;
                            case 'linge':
                                $filtrage .= $join .=" JOIN locbreizh._service_compris s ON l.id_logement=s.logement "; " AND s.nom_service='linge'"; break;
                        }
                    }
                }
            }

            // Filtres : INPUT (barre de recherche)
            
            // -- LES PRIX
            if ((isset($_GET['prix_min'])) && (isset($_GET['prix_max']))&&($_GET['prix_min']>$_GET['prix_max'])){
                //-- erreur
                echo "ERREUR: prix minimum > prix maximum !";
            }else if ((isset($_GET['prix_min'])) && (isset($_GET['prix_max']))){
                $prixMin = $_GET['prix_min'];
                $prixMax = $_GET['prix_max'];   
                $filtrage .= " AND tarif_base_ht >= $prixMin AND tarif_base_ht <= $prixMax";
            } else if (isset($_GET['prix_min'])){
                $prixMin = $_GET['prix_min'];
                $filtrage .= " AND tarif_base_ht >= $prixMin ";
            } else if (isset($_GET['prix_max'])){
                $prixMax = $_GET['prix_max'];   
                $filtrage .= " AND tarif_base_ht <= $prixMax ";
            }

            // -- LIEU
            if ((isset($_GET['lieu']))){
                $lieu = $_GET['lieu'];
                // Voir pour vérifier ou changer à la ville la plus ressemblante à l'input comme sur AJOUTER LOGEMENT
                $join .= " JOIN locbreizh._adresse a ON l.id_adresse = a.id_adresse ";
                $filtrage .= "AND a.ville=$lieu ";
            }

            // -- VOYAGEURS
            if ((isset($_GET['personne']))){
                $voyageurs = $_GET['personne'];
                $filtrage .= "AND nb_personnes_logement >= $voyageurs";
            }

            // -- DATES
            
            if ((isset($_GET['date1']))&&(isset($_GET['date2']))){
                $dateDeb = $_GET['date1'];
                $dateFin = $_GET['date2'];

            } else if (isset($_GET['date1'])){
                $dateDeb = $_GET['date1'];

            } else if (isset($_GET['date2'])){
                $dateFin = $_GET['date2'];

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
                JOIN locbreizh._adresse a ON l.id_adresse = a.id_adresse $join WHERE l.en_ligne=true $filtrage $tri ;"
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

        

        ?> <div class="card"> <?php
        $res = $stmt->fetchAll();

        // affichage des données de logement
        if (count($res)<=0){ ?> 
            <p class="center" style="font-size: 1.5em;">Aucun logement trouvé</p>
<?php   }
        foreach ($res as $card) {
            if ($card['en_ligne'] == true) {
                ?>
                <article class="logementCard cardtel">
                    <a href="../Logement/logement_detaille_client.php?logement=<?php echo $card['id_logement'] ?>"> 
                        <img src="../Ressources/Images/<?php echo $card['photo_principale'] ?>">
                        <div class="infoContainer">
                            <div class="mainInfos">
                                <span class="logementTitre"> <?php echo $card['libelle_logement']; ?></span>
                                <span> <?php echo $card['ville'] . ", " . $card['code_postal']; ?></span>
                            </div>
                            <div class="otherInfos cardtel">
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
                <?php
            } 
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