<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="icon" href="../svg/logo.svg">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="./carte.js"></script>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
</head>

<body onload="init()">
    <?php
    $filtre = '';
    include('../header-footer/choose_header.php');
    ?>
    <main class="mainacc">
    <div class="section-filters">
            <!--<p class="acc-accroche">Rechercher votre logement selon votre critère</p>-->
            <div class="filters">
                <div class="menu-filtre">
                    <div class="fil">
                        <div class="input-group" style="width: 10%;">
                            <div class="input-group-prepend">
                                <label for="prix_min">min<img src="../svg/money.svg" width="12" height="12"></label>
                            </div>
                            <input type="number" id="prix_min" name="prix_min" placeholder="<?php if (isset($_GET['prix_min'])) {
                                                                                                echo $_GET['prix_min'];
                                                                                            } else {
                                                                                                echo 0;
                                                                                            } ?>" min="0" />
                        </div>
                        <div class="input-group" style="width: 10%;">
                            <div class="input-group-prepend">
                                <label for="prix_max">max<img src="../svg/money.svg" width="12" height="12"></label>
                            </div>
                            <input type="number" id="prix_max" name="prix_max" placeholder="<?php if (isset($_GET['prix_max'])) {
                                                                                                echo $_GET['prix_max'];
                                                                                            } else {
                                                                                                echo 0;
                                                                                            } ?>" min="0" />
                        </div>
                        <div class="input-group input-group-ville">
                            <div class="input-group-prepend">
                                <label for="lieu"><img src="../svg/map-pin-line.svg" width="25" height="25"></label>
                            </div>
                            <input type="text" id="lieu" name="lieu" placeholder="<?php if (isset($_GET['lieu'])) {
                                                                                        echo $_GET['lieu'];
                                                                                    } else {
                                                                                        echo 'Ville';
                                                                                    } ?>" />
                        </div>
                        <div class="input-group input-group-pers">
                            <div class="input-group-prepend">
                                <label for="personne"><img src="../svg/group.svg" width="25" height="25"></label>
                            </div>
                            <input type="number" id="personne" name="personne" placeholder="<?php if (isset($_GET['personne'])) {
                                                                                                echo $_GET['personne'];
                                                                                            } else {
                                                                                                echo 0;
                                                                                            } ?>" />
                        </div>
                        <div class="input-group" style="width: 15%;">
                            <div class="input-group-prepend">
                                <label for="date1">Début <img src="../svg/calendar.svg" width="14" height="14"></label>
                            </div>
                            <input type="date" id="date1" name="date1" value="<?php if (isset($_GET['date1'])) {echo $_GET['date1']; }else {echo date('Y-m-d');}?>" />
                        </div>
                        <div class="input-group" style="width: 15%;">
                            <div class="input-group-prepend">
                                <label for="date2">Fin <img src="../svg/calendar.svg" width="14" height="14"></label>
                            </div>
                            <input type="date" id="date2" name="date2" value="<?php if (isset($_GET['date2'])) {echo $_GET['date2']; } else {echo date('Y-m-d');}?>" />
                        </div>
                        <hr style="margin: 5px;">
                        <a class="btn-filtre">
                            <img src="../svg/filtre.svg" width="20" height="20" onclick="openPopup('filtre','ovFiltre')">
                        </a>
                        
                    </div>

                    <div class="contain-supF">
                        <?php 
                        // Supprimer Filtres 
            
                        // -- LES PRIX
                        if ((isset($_GET['prix_min'])) || (isset($_GET['prix_max']))){?>
                            <button class="btn-supF" id="btn-supF">
                                <span id="prix">Prix</span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-miterlimit="2" stroke-linejoin="round" fill-rule="evenodd" clip-rule="evenodd"><path fill-rule="nonzero" d="m12.002 2.005c5.518 0 9.998 4.48 9.998 9.997 0 5.518-4.48 9.998-9.998 9.998-5.517 0-9.997-4.48-9.997-9.998 0-5.517 4.48-9.997 9.997-9.997zm0 1.5c-4.69 0-8.497 3.807-8.497 8.497s3.807 8.498 8.497 8.498 8.498-3.808 8.498-8.498-3.808-8.497-8.498-8.497zm0 7.425 2.717-2.718c.146-.146.339-.219.531-.219.404 0 .75.325.75.75 0 .193-.073.384-.219.531l-2.717 2.717 2.727 2.728c.147.147.22.339.22.531 0 .427-.349.75-.75.75-.192 0-.384-.073-.53-.219l-2.729-2.728-2.728 2.728c-.146.146-.338.219-.53.219-.401 0-.751-.323-.751-.75 0-.192.073-.384.22-.531l2.728-2.728-2.722-2.722c-.146-.147-.219-.338-.219-.531 0-.425.346-.749.75-.749.192 0 .385.073.531.219z"></path></svg>
                                </span>
                            </button>
                        <?php } 

                        // -- LIEU 
                        if (isset($_GET['lieu'])) { ?>
                            <button class="btn-supF" id="btn-supF">
                                <span id="lieu">Ville</span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-miterlimit="2" stroke-linejoin="round" fill-rule="evenodd" clip-rule="evenodd"><path fill-rule="nonzero" d="m12.002 2.005c5.518 0 9.998 4.48 9.998 9.997 0 5.518-4.48 9.998-9.998 9.998-5.517 0-9.997-4.48-9.997-9.998 0-5.517 4.48-9.997 9.997-9.997zm0 1.5c-4.69 0-8.497 3.807-8.497 8.497s3.807 8.498 8.497 8.498 8.498-3.808 8.498-8.498-3.808-8.497-8.498-8.497zm0 7.425 2.717-2.718c.146-.146.339-.219.531-.219.404 0 .75.325.75.75 0 .193-.073.384-.219.531l-2.717 2.717 2.727 2.728c.147.147.22.339.22.531 0 .427-.349.75-.75.75-.192 0-.384-.073-.53-.219l-2.729-2.728-2.728 2.728c-.146.146-.338.219-.53.219-.401 0-.751-.323-.751-.75 0-.192.073-.384.22-.531l2.728-2.728-2.722-2.722c-.146-.147-.219-.338-.219-.531 0-.425.346-.749.75-.749.192 0 .385.073.531.219z"></path></svg>
                                </span>
                            </button>
                        <?php }
                        

                        // -- VOYAGEURS
                        if ((isset($_GET['personne']))){ ?>
                            <button class="btn-supF" id="btn-supF">
                                <span id="personne">Voyageurs</span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-miterlimit="2" stroke-linejoin="round" fill-rule="evenodd" clip-rule="evenodd"><path fill-rule="nonzero" d="m12.002 2.005c5.518 0 9.998 4.48 9.998 9.997 0 5.518-4.48 9.998-9.998 9.998-5.517 0-9.997-4.48-9.997-9.998 0-5.517 4.48-9.997 9.997-9.997zm0 1.5c-4.69 0-8.497 3.807-8.497 8.497s3.807 8.498 8.497 8.498 8.498-3.808 8.498-8.498-3.808-8.497-8.498-8.497zm0 7.425 2.717-2.718c.146-.146.339-.219.531-.219.404 0 .75.325.75.75 0 .193-.073.384-.219.531l-2.717 2.717 2.727 2.728c.147.147.22.339.22.531 0 .427-.349.75-.75.75-.192 0-.384-.073-.53-.219l-2.729-2.728-2.728 2.728c-.146.146-.338.219-.53.219-.401 0-.751-.323-.751-.75 0-.192.073-.384.22-.531l2.728-2.728-2.722-2.722c-.146-.147-.219-.338-.219-.531 0-.425.346-.749.75-.749.192 0 .385.073.531.219z"></path></svg>
                                </span>
                            </button>
                        <?php }

                        // -- DATES
                        if ((isset($_GET['date1']))||(isset($_GET['date2']))){ ?>
                            <button class="btn-supF" id="btn-supF">
                                <span id="date">Date</span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-miterlimit="2" stroke-linejoin="round" fill-rule="evenodd" clip-rule="evenodd"><path fill-rule="nonzero" d="m12.002 2.005c5.518 0 9.998 4.48 9.998 9.997 0 5.518-4.48 9.998-9.998 9.998-5.517 0-9.997-4.48-9.997-9.998 0-5.517 4.48-9.997 9.997-9.997zm0 1.5c-4.69 0-8.497 3.807-8.497 8.497s3.807 8.498 8.497 8.498 8.498-3.808 8.498-8.498-3.808-8.497-8.498-8.497zm0 7.425 2.717-2.718c.146-.146.339-.219.531-.219.404 0 .75.325.75.75 0 .193-.073.384-.219.531l-2.717 2.717 2.727 2.728c.147.147.22.339.22.531 0 .427-.349.75-.75.75-.192 0-.384-.073-.53-.219l-2.729-2.728-2.728 2.728c-.146.146-.338.219-.53.219-.401 0-.751-.323-.751-.75 0-.192.073-.384.22-.531l2.728-2.728-2.722-2.722c-.146-.147-.219-.338-.219-.531 0-.425.346-.749.75-.749.192 0 .385.073.531.219z"></path></svg>
                                </span>
                            </button>
                        <?php } 

                        // -- Type d'hébergement
                        if (isset($_GET['typeH'])){ ?>
                            <button class="btn-supF" id="btn-supF">
                                <span id="typeH">Type d'hébergement</span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-miterlimit="2" stroke-linejoin="round" fill-rule="evenodd" clip-rule="evenodd"><path fill-rule="nonzero" d="m12.002 2.005c5.518 0 9.998 4.48 9.998 9.997 0 5.518-4.48 9.998-9.998 9.998-5.517 0-9.997-4.48-9.997-9.998 0-5.517 4.48-9.997 9.997-9.997zm0 1.5c-4.69 0-8.497 3.807-8.497 8.497s3.807 8.498 8.497 8.498 8.498-3.808 8.498-8.498-3.808-8.497-8.498-8.497zm0 7.425 2.717-2.718c.146-.146.339-.219.531-.219.404 0 .75.325.75.75 0 .193-.073.384-.219.531l-2.717 2.717 2.727 2.728c.147.147.22.339.22.531 0 .427-.349.75-.75.75-.192 0-.384-.073-.53-.219l-2.729-2.728-2.728 2.728c-.146.146-.338.219-.53.219-.401 0-.751-.323-.751-.75 0-.192.073-.384.22-.531l2.728-2.728-2.722-2.722c-.146-.147-.219-.338-.219-.531 0-.425.346-.749.75-.749.192 0 .385.073.531.219z"></path></svg>
                                </span>
                            </button>
                        <?php } ?>
                    </div>

                    <div style="display: flex; justify-content: space-evenly;">
                        <?php if ((isset($_GET['prix_min'])) && (isset($_GET['prix_max']))&&($_GET['prix_min']>$_GET['prix_max'])){?>
                            <p class='err'>Le prix min doit être inférieur au prix max.</p>
                        <?php } ?>
                        <?php if ((isset($_GET['personne'])&&($_GET['personne']<0))){?>
                            <p class='err'>Le nombre de voyageurs doit être positif.</p>
                        <?php } ?>
                        <?php if ((isset($_GET['date1']))&&(isset($_GET['date2']))&&(strtotime($_GET['date1'])>strtotime($_GET['date2']))){ ?>
                            <p class='err'>La date de début doit être inférieur à la date de fin.</p>
                        <?php } ?>
                        <?php if ((isset($_GET['date1']))&&($_GET['date1']< date('Y-m-d'))){ ?>
                            <p class='err'>La date de début doit être supérieur à la date d'aujourd'hui.</p>
                        <?php } ?>
                        <?php if ((isset($_GET['date2']))&&($_GET['date2']< date('Y-m-d'))){ ?>
                            <p class='err'>La date de fin doit être supérieur à la date d'aujourd'hui.</p>
                        <?php } ?>
                    </div>
                    
                </div>

            </div>
        </div>

    <div class="maincatalog">

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

                $filtrage="";
                $join="";
                $tri="";

                if(isset($mesFiltres)||(isset($monTri))){
                    // Traitement de l'argument de tri
                    
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
                                // Services
                                /*case 'menage':
                                    $join = " JOIN locbreizh._service_compris s ON l.id_logement=s.logement "; $filtrage .= " AND s.nom_service='menage'"; break;
                                case 'navette':
                                    $join = " JOIN locbreizh._service_compris s ON l.id_logement=s.logement "; $filtrage .= " AND s.nom_service='navette'"; break;
                                case 'linge':
                                    $join = " JOIN locbreizh._service_compris s ON l.id_logement=s.logement "; $filtrage .= " AND s.nom_service='linge'"; break;*/
                            }
                        }
                    }
                }

                // Filtres : INPUT (barre de recherche)
                
                // -- LES PRIX
                if ((isset($_GET['prix_min'])) && (isset($_GET['prix_max']))){
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
                
                // Voir pour vérifier ou changer à la ville la plus ressemblante à l'input comme sur AJOUTER LOGEMENT
                if (isset($_GET['lieu'])) {
                    $lieu = $dbh->quote($_GET['lieu']);
                    $filtrage .= "AND a.ville=$lieu ";
                }
                

                // -- VOYAGEURS
                if ((isset($_GET['personne']))){
                    $voyageurs = $_GET['personne'];
                    $filtrage .= "AND nb_personnes_logement >= $voyageurs";
                }

                // -- DATES
                if ((isset($_GET['date1']))&&(isset($_GET['date2']))){
                    $dateDeb = new DateTime($_GET['date1']);
                    $dateFin = new DateTime($_GET['date2']);
                    $nbJours = ($dateFin->diff($dateDeb))->days +1;
                    $filtrage .= " AND id_logement IN (
                        SELECT DISTINCT id_logement
                        FROM locbreizh._plage_ponctuelle p 
                        JOIN locbreizh._plage_ponctuelle_disponible pd ON p.id_plage_ponctuelle = pd.id_plage_ponctuelle
                        JOIN locbreizh._logement l ON l.code_planning = p.code_planning
                        WHERE p.jour_plage_ponctuelle BETWEEN :date_debut AND :date_fin GROUP BY id_logement
                        HAVING COUNT(*) >= $nbJours 
                    )";
                } else if (isset($_GET['date1'])){
                    $dateDeb = new DateTime($_GET['date1']);
                    $dateDeb = $dateDeb->format('Y-m-d'); // Formatage de la date si nécessaire

                    $filtrage .= " AND id_logement IN (
                                    SELECT DISTINCT id_logement
                                    FROM locbreizh._plage_ponctuelle p 
                                    JOIN locbreizh._plage_ponctuelle_disponible pd ON p.id_plage_ponctuelle = pd.id_plage_ponctuelle
                                    JOIN locbreizh._logement l ON l.code_planning = p.code_planning
                                    WHERE p.jour_plage_ponctuelle = :date_debut 
                                )";
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

                if ((isset($_GET['date1']))&&(isset($_GET['date2']))){
                    $stmt->bindParam(':date_debut', $dateDeb->format('Y-m-d'));
                    $stmt->bindParam(':date_fin', $dateFin->format('Y-m-d'));
                } else if (isset($_GET['date1'])){
                    $stmt->bindParam(':date_debut', $dateDeb);
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

        <div class="acc-with-map">
            <!-- Champs de séléction des Tris -->
            <select class="triage" id="tri" name="tri">
                <option value="none" hidden> Trier par : choisir tri</option>
                <option value="vide">Aucun tri</option> <!-- Retirer le tri actif -->
                <option value="prix_c">Prix (croissant)</option>
                <option value="prix_d">Prix (décroissant)</option>
            </select> 
            <hr class="hr" style="width:100%;">
                
            <section class="card">
                    
                    <?php
                    $res = $stmt->fetchAll();

                    // affichage des données de logement
                    if (count($res) <= 0) { ?>
                        <p style="font-size: 1.5em;">Aucun logement trouvé</p>
                        <?php   } foreach ($res as $card) {
                        if ($card['en_ligne'] == true) { ?>
                                <article class="logementCard cardtel scale-up-center">
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
                                                    </span><?php echo $card['nb_personnes_logement']; ?> personnes</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                        <?php   }
                                        }
                        ?>
                </section>
            </div>
            <section id="containerMap">
                <div id="map"></div>
            </section>  

            <div id="ovFiltre" onclick="closePopup('filtre', 'ovFiltre')"></div>
            <!-- <div id="filtre" class="filtrage" style="display="none"> -->
            <div class="filtrage">
                <span class="fltitre">Filtres</span><hr class="hr">
                <div>
                    <h3 class="flptitre">Par type de logement</h3>
                    <div class="radio-inputs" style="flex-wrap: wrap;">
                            <label>
                                <input class="radio-input" type="radio" name="typeH" value="maison"<?php if((isset($_GET['typeH'])) && strpos($_GET['typeH'], 'maison')!==false){echo 'checked';}?>>
                                    <span class="radio-tile">
                                        <span class="radio-icon">
                                            <img src="../svg/house.svg" width="25" height="25">
                                        </span>
                                        <span class="radio-label">Maison</span>
                                    </span>
                            </label>
                            <label>
                                <input class="radio-input" type="radio" name="typeH" value="appartement" <?php if((isset($_GET['typeH'])) && strpos($_GET['typeH'], 'appartement')!==false){echo 'checked';}?>>
                                <span class="radio-tile">
                                    <span class="radio-icon">
                                        <img src="../svg/appartement.svg" width="25" height="25">
                                    </span>
                                    <span class="radio-label">Appartement</span>
                                </span>
                            </label>
                            <label>
                                <input class="radio-input" type="radio" name="typeH" value="chateau" <?php if((isset($_GET['typeH'])) && strpos($_GET['typeH'], 'chateau')!==false){echo 'checked';}?>>
                                <span class="radio-tile">
                                    <span class="radio-icon">
                                        <img src="../svg/castle.svg" width="25" height="25">
                                    </span>
                                    <span class="radio-label">Château</span>
                                </span>
                            </label>
                            <label>
                                <input class="radio-input" type="radio" name="typeH" value="manoir" <?php if((isset($_GET['typeH'])) && strpos($_GET['typeH'], 'manoir')!==false){echo 'checked';}?>>
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

                    <h3 class="flptitre">Par service</h3>
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
                    <a class="center btn-accueil" style="text-decoration: none; color:white;" href="./accueil_visiteur.php"> Réinitialiser </a>
                </div>
            </div>
        </div>
    </main>
    <?php
    // appel du footer
    include('../header-footer/choose_footer.php');
    ?>
</body>

</html>

<?php
    if (isset($_GET['filtre']) || isset($_GET['typeH'])) {?>
        <script> openPopup('filtre','ovFiltre'); </script>
<?php } ?>

<script src="../scriptPopup.js" defer></script>
<script src="./actualiserTri.js" defer></script>
<script src="./actualiserFiltre.js" defer></script>