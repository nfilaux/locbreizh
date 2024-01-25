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
    // fontion pour afficher les erreurs de modification
    function erreur($nomErreur){
        if(isset($_SESSION["erreurs"][$nomErreur])){
            ?><p class="profil-erreurs"><?php echo $_SESSION["erreurs"][$nomErreur]?></p><?php
            unset($_SESSION["erreurs"][$nomErreur]);
        }
    }

    $plageIndispo = [];
    $plageDispo = [];  
?>

<script>
    numCalendrier = -1;
</script>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../Logement/scriptCalendrier.js"></script>
    <script src="../scriptPopup.js"></script>
</head>

<body class="pageproprio">
    <?php 
        include('../header-footer/choose_header.php');
        $cas_popup = $_GET["cs"];
    ?>

    <main class="MainTablo">
        <div class="headtabloP"> 
            <h1>Mes Logements</h1>
        </div>
        <section class="Tablobord">
                <div class="colreverse">
                <?php
                    
                    $stmt = $dbh->prepare(
                        "SELECT * from locbreizh._logement where id_proprietaire = {$_SESSION['id']};"
                    );

                    function formatDate($start, $end)
                {
                    $startDate = date('j', strtotime($start));
                    $endDate = date('j', strtotime($end));
                    $month = date('M', strtotime($end));

                    return "$startDate-$endDate $month";
                }

                $stmt->execute();
                $liste_mes_logements = $stmt->fetchAll();
                foreach ($liste_mes_logements as $key => $card) {
                    $id_log = $card['id_logement'];
                    $infos_log[$id_log] = $card;
                }

                foreach ($liste_mes_logements as $key => $card) {
                    $id_log = $card['id_logement'];

                    if ($infos_log[$id_log]["en_ligne"] == 1){
                        $bouton_desactiver = "METTRE HORS LIGNE";  
                    } else{
                        $bouton_desactiver = "METTRE EN LIGNE";
                    }
                    $nomPlage = 'plage' . $key; 
                    $overlayPlage = 'overlay' . $key
                    
                    ?>
                        <div class="cardlogmainP">
                            <img src="../Ressources/Images/<?php echo $card['photo_principale']?>">
                            <section class="logcp">
                                <div class="logrowb">
                                    <div>
                                        <h3 class="titrecard"><?php echo $card['libelle_logement'] ?></h3>
                                        <hr class="hrcard">
                                    </div>
                                    <a class="calend" onclick="openPopup('<?php echo $nomPlage; ?>', '<?php echo $overlayPlage; ?>')"><img src="../svg/calendar.svg" alt="Gérer calendrier" title="Calendrier"></a>    
                                    <a class="btn-modiftst" href="../Logement/modifierLogement.php?id_logement=<?php echo $card['id_logement'] ?>">
                                        <button class="btn-modif"> Modifier
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 10 10">
                                            <path stroke="#345C99" stroke-width=".3" d="M.917.503h8.405v8.405H.917z"/>
                                            <path fill="#345C99" d="M2.58 7.205h.513l3.378-3.378-.513-.513L2.58 6.692v.513Zm5.803.725H1.855V6.39l4.873-4.873a.363.363 0 0 1 .513 0l1.026 1.026a.363.363 0 0 1 0 .513L4.119 7.205h4.264v.725ZM6.471 2.8l.513.514.513-.513-.513-.513-.513.513Z"/>
                                        </svg>
                                    </button>
                                    </a>
                                </div>
                                
                                <div class="logrowb">
                                    <a href="../Logement/logement_detaille_proprio.php?logement=<?php echo $id_log ?>"><button class="btn-ajoutlog">CONSULTER</button></a>
                                    <?php $id_un_logement = $id_log; ?>
                                    <form action="ChangeEtat.php" method="post">
                                        <input type="hidden" name=<?php echo $id_un_logement ?> value="<?php echo htmlentities($bouton_desactiver) ?>">
                                        <button class="btn-desactive" type='submit'> <?php echo $bouton_desactiver; ?> </button>
                                    </form>
                                    <input type="hidden" id="cas_bouton_suppr" value=<?php echo $cas_popup ?>>
                                    <a href="../Logement/supprimer_logement.php?id=<?php echo $id_log ?>"><button class="btn-suppr">SUPPRIMER</button></a>
                                </div>
                                
                                <div class="logrowb">

                                    <div class="overlay_plages" id="overlay_erreur" onclick="closePopup('erreur_suppr','overlay_erreur')"></div>
                                    <div id="erreur_suppr" class="plages" class="erreur" > <p> Impossible de supprimer un logement lié à une réservation ! <p> <button onclick="closePopup('erreur_suppr','overlay_erreur')" class="btn-ajoutlog">Ok</button></div>

                                    <div class="overlay_plages" id="overlay_confirm" onclick="closePopup('confirm','overlay_confirm')"></div>
                                    <div id="confirm" class="plages"> <p class="valid"> Votre logement vient d'être supprimé avec succès ! <p> <button onclick="closePopup('confirm','overlay_confirm')" class="btn-ajoutlog">Ok</button></div>
                                    
                                    <div class="overlay_plages" id="overlay_validation" onclick="closePopup('validation','overlay_validation')"></div>
                                    <div id="validation" class="plages"> 
                                        <p> Etes vous bien sûr de vouloir supprimer votre logement : <?php echo $infos_log[$_GET["idlog"]]["libelle_logement"]; ?> ? 
                                        <p class="erreur">Cette action est irreversible !</p> 
                                        <div id='boutons'>
                                            <button onclick="closePopup('validation','overlay_validation')" class="btn-ajoutlog">Annuler</button> 
                                            <a href="../Logement/supprimer_logement.php?idc=<?php echo $_GET["idlog"]?>" ><button class="btn-suppr">Supprimer</button></a>
                                        </div>
                                    </div>
                                    
                                    <script>
                                        cas = document.getElementById("cas_bouton_suppr");
                                        if (cas.value == '1'){
                                            openPopup("erreur_suppr","overlay_erreur");
                                        } else if (cas.value == '2') {
                                            openPopup("validation","overlay_validation");
                                        } else if (cas.value =='3'){
                                            openPopup("confirm","overlay_confirm");
                                        }
                                    </script>
                            
                            <div class="overlay_plages" id='<?php echo $overlayPlage; ?>' onclick="closePopup('<?php echo $nomPlage; ?>', '<?php echo $overlayPlage; ?>')"></div>
                            <div id="<?php echo $nomPlage; ?>" class='plages'> 
                                    <h1>Ajouter une plage ponctuelle</h1><br>
                                    <div class="logcolumn">
                                        <div class="corpsCalendrier">
                                            <div class="fond">
                                                <div class="teteCalendrier">
                                                    <div class="fleches">
                                                        <svg id="precedent" xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14">
                                                            <path fill="#745086" d="m2.828 7 4.95 4.95-1.414 1.415L0 7 6.364.637 7.778 2.05 2.828 7Z"/>
                                                        </svg>
                                                    </div>
                                                    <p class="date_actuelle"></p>
                                                </div>
                                                <div class="calendrier">
                                                    <ul class="semaines">
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
                                            <div class="fond">
                                                <div class="teteCalendrier">
                                                    <p class="date_actuelle"></p>
                                                    <div class="fleches">
                                                        <svg id="suivant" xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14">
                                                            <path fill="#745086" d="m2.828 7 4.95 4.95-1.414 1.415L0 7 6.364.637 7.778 2.05 2.828 7Z"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="calendrier">
                                                    <ul class="semaines">
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

                                    <form action="../Planning/plageBack.php" method="post">
                                        
                                        <?php erreur("plage") ?>
                                        <input class="jesuiscache" type='hidden' name="debut_plage_ponctuelle" id="debut_plage_ponctuelle" value="" required>
                                        <input class="jesuiscache" type='hidden' name="fin_plage_ponctuelle" id="fin_plage_ponctuelle" value="" required>

                                        <label for="prix_plage_ponctuelle"> Prix : </label>
                                        <input type="text" id="prix_plage_ponctuelle" name="prix" placeholder="<?php echo $card['tarif_base_ht'] ?>" value="<?php echo $card['tarif_base_ht'] ?>" required/>
                                        <br><?php erreur("prix") ?><br>

                                        <label for="indisponible"> Indisponible : </label>
                                        <input type="checkbox" id="indisponible" name="indisponible" value="false" onchange="changer(this.checked)"/>
                                        <br><br>

                                        <label for="libelleIndispo"> Raison d'indisponibilité : </label>
                                        <input type="text" id="libelleIndispo" name="libelleIndispo" disabled=true/>
                                        <br><?php erreur("libelleIndispo") ?><br>

                                        <input type="hidden" name="id_logement" value="<?php echo $card['id_logement'] ?>"/>

                                        <input type="hidden" name="overlayPopUp" value="<?php echo $overlayPlage ?>"/>
                                        <input type="hidden" name="nomPopUp" value="<?php echo $nomPlage ?>"/>

                    
                                        <button type="submit" class="btn-ajt">Ajouter plage</button>
                                    </form>

                                    <form action="../Planning/supprimerPlage.php" method="post">
                                        
                                        <input class="jesuiscache" type='hidden' name="debut_plage_suppr" id="debut_plage_suppr" value="" required>
                                        <input class="jesuiscache" type='hidden' name="fin_plage_suppr" id="fin_plage_suppr" value="" required>
                                        
                                        <input type="hidden" name="id_logement" value="<?php echo $card['id_logement'] ?>"/>

                                        <input type="hidden" name="overlayPopUp" value="<?php echo $overlayPlage ?>"/>
                                        <input type="hidden" name="nomPopUp" value="<?php echo $nomPlage ?>"/>

                                        <button type="submit" class="btn-ajt">Supprimer plage</button>
                                    </form>

                                
                                    <script type="text/javascript">
                                        function changer(etat){
                                            if (etat){
                                                document.getElementById("prix_plage_ponctuelle").disabled = true;
                                                document.getElementById("libelleIndispo").disabled = false;
                                            }
                                            else{
                                                document.getElementById("prix_plage_ponctuelle").disabled = false;
                                                document.getElementById("libelleIndispo").disabled = true;
                                            }
                                        }
                                    </script>

                                    <?php
                                    try {
                                        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                                        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                                        $code = $infos_log[$id_log]['code_planning'];

                                        $stmt = $dbh->prepare("SELECT libelle_indisponibilite, jour_plage_ponctuelle FROM locbreizh._plage_ponctuelle INNER JOIN locbreizh._plage_ponctuelle_indisponible
                                        ON _plage_ponctuelle.id_plage_ponctuelle = _plage_ponctuelle_indisponible.id_plage_ponctuelle WHERE code_planning = {$code} ;");
                                        $stmt->execute();
                                        $plageIndispo[$id_log] = $stmt->fetchAll();

                                        $stmt = $dbh->prepare("SELECT prix_plage_ponctuelle, jour_plage_ponctuelle FROM locbreizh._plage_ponctuelle INNER JOIN locbreizh._plage_ponctuelle_disponible
                                        ON _plage_ponctuelle.id_plage_ponctuelle = _plage_ponctuelle_disponible.id_plage_ponctuelle WHERE code_planning = {$code} ;");
                                        $stmt->execute();
                                        $plageDispo[$id_log] = $stmt->fetchAll();

                                    } catch (PDOException $e) {
                                        print "Erreur !:" . $e->getMessage() . "<br/>";
                                        die();
                                    }
                                    ?>

                                <script>
                                    numCalendrier += 1;
                                    //Appel de la fonction pour créer les calendriers
                                    instancier(numCalendrier);
                                    afficherCalendrier("normal", numCalendrier);

                                    var tab = <?php echo json_encode($plageIndispo[$id_log]); ?>;
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
                                        tabMotif[i] = tab[i]["libelle_indisponibilite"];
                                    }
                                    afficherPlages(tabRes, "indisponible", tabMotif, "I", numCalendrier);

                                    var tab = <?php echo json_encode($plageDispo[$id_log]); ?>;
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
                                    afficherPlages(tabRes, "disponible", tabMotif, "D", numCalendrier);
                                </script>
                                            
                                </div>  
                            </div>
                        
                    </section>
</div>
                    <?php
                }
                ?>
                </div>
            <a href="../Logement/remplir_formulaire.php"><button class="btn-ajoutlog" >AJOUTER UN LOGEMENT</button></a>

        </section>    
    </main>

    <?php
    if ((isset($_GET['popup'])&&(isset($_GET['overlay'])))){?>
        <script> openPopup(<?php echo "'". $_GET['popup'] ."'" ?>, <?php echo "'". $_GET['overlay'] ."'" ?>) </script>
    <?php } 
    
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>

</html>
