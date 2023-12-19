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
    
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
</head>

<body class="pageproprio">
    <?php 
        include('../header-footer/choose_header.php');
        $cas_popup = $_GET["cs"];
    ?>

    <main class="MainTablo">
        <div class="headtabloP"> 
            <h1>Mon tableau de bord</h1>
        </div>
        <section class="Tablobord">
                <h2>Mes logements</h2>
                <div class="colreverse">
                <?php
                    
                    $stmt = $dbh->prepare(
                        "SELECT photo_principale, libelle_logement, tarif_base_ht, nb_personnes_logement, id_logement
                        from locbreizh._logement where id_proprietaire = {$_SESSION['id']};"
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
                    $stmt = $dbh->prepare(
                        "SELECT en_ligne,libelle_logement
                        from locbreizh._logement 
                        where id_logement = $id_log;"
                    );
                    $stmt->execute();
                    $infos_log = $stmt->fetch();

                    if ($infos_log["en_ligne"] == 1){
                        $bouton_desactiver = "METTRE_HORS_LIGNE1";  
                    } else{
                        $bouton_desactiver = "METTRE_EN_LIGNE";
                    }
                    
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
                                        <input type="hidden" name=<?php echo $id_un_logement ?> value=<?php echo $bouton_desactiver ?>>
                                        <button class="btn-desactive" name="bouton_changer_etat" type='submit'> <?php echo $bouton_desactiver; ?> </button>
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
                                        <p> Etes vous bien sûr de vouloir supprimer votre logement : <?php echo $infos_log["libelle_logement"]; ?> ? 
                                        <p class="erreur">Cette action est irreversible !</p> 
                                        <div id='boutons'>
                                            <button onclick="closePopup('validation','overlay_validation')" class="btn-ajoutlog">Annuler</button> 
                                            <a href="../Logement/supprimer_logement.php?idc=<?php echo $id_log?>" ><button class="btn-suppr">Supprimer</button></a>
                                        </div>
                                    </div>
                                    
                                    <script>
                                        let cas = document.getElementById("cas_bouton_suppr");
                                        if (cas.value == '1'){
                                            openPopup("erreur_suppr","overlay_erreur");
                                        } else if (cas.value == '2') {
                                            openPopup("validation","overlay_validation");
                                        } else if (cas.value =='3'){
                                            openPopup("confirm","overlay_confirm");
                                        }
                                    </script>
                            

                                    <?php
                                $nomPlage = 'plage' . $key; 
                                $overlayPlage = 'overlay' . $key?>
                            
                            <div class="overlay_plages" id='<?php echo $overlayPlage; ?>' onclick="closePopup('<?php echo $nomPlage; ?>', '<?php echo $overlayPlage; ?>')"></div>
                            <div id="<?php echo $nomPlage; ?>" class='plages'> 
                                    <h1>Ajouter une plage ponctuelle</h1><br>
                                    <form action="../Planning/plageBack.php" method="post">
                                        
                                        <label for="debut_plage_ponctuelle"> date de début de la plage : </label>
                                        <input type="date" id="debut_plage_ponctuelle" name="dateDeb" required/>
                                        <br><br>
                                        
                                        <label for="fin_plage_ponctuelle"> date de fin de la plage : </label>
                                        <input type="date" id="fin_plage_ponctuelle" name="dateFin" required/>
                                        <br><br>

                                        <label for="prix_plage_ponctuelle"> Prix : </label>
                                        <input type="text" id="prix_plage_ponctuelle" name="prix" placeholder="<?php echo $card['tarif_base_ht'] ?>" value="<?php echo $card['tarif_base_ht'] ?>"/>
                                        <br><?php erreur("prix") ?><br>

                                        <label for="indisponible"> Indisponible : </label>
                                        <input type="checkbox" id="indisponible" name="indisponible" value="false"/>
                                        <br><br>

                                        <input type="hidden" name="id_logement" value="<?php echo $card['id_logement'] ?>"/>

                                        <input type="hidden" name="overlayPopUp" value="<?php echo $overlayPlage ?>"/>
                                        <input type="hidden" name="nomPopUp" value="<?php echo $nomPlage ?>"/>

                    
                                        <button type="submit" class="btn-ajt">Ajouter</button>
                                    </form>
                                    
                                    <hr><h1>Les plages ponctuelles</h1><br>

                                    <?php
                                    try {
                                        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                                        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                                        $code = $dbh->prepare("SELECT code_planning FROM locbreizh._planning NATURAL JOIN locbreizh._logement WHERE id_logement = {$card['id_logement']};");

                                        $code->execute();

                                        $lesPlages = $dbh->prepare("SELECT id_plage_ponctuelle, debut_plage_ponctuelle, fin_plage_ponctuelle, prix_plage_ponctuelle, disponible FROM locbreizh._plage_ponctuelle WHERE code_planning = {$code->fetch()['code_planning']} ;");
                                        
                                        $lesPlages->execute();

                                    } catch (PDOException $e) {
                                        print "Erreur !:" . $e->getMessage() . "<br/>";
                                        die();
                                    }

                                    $lesPlages = $lesPlages->fetchAll();

                                    if($lesPlages != null){
                                        foreach($lesPlages as $plage){  ?>
                                            <div class="unePlage">
                                                <?php 
                                                $deb = new DateTime($plage['debut_plage_ponctuelle']);
                                                $fin = new DateTime($plage['fin_plage_ponctuelle']);
                                                $dispo = $plage['disponible']==true ? "oui" : "non";
                                                echo $deb->format("d/m/Y") . " - " . $fin->format("d/m/Y") . " | "  . "Prix = " . $plage['prix_plage_ponctuelle'] . " | Disponible : " . $dispo ;
                                                ?>
                                                <form action="../Planning/supprimerPlage.php" method=post>
                                                    <input type="hidden" name="overlayPopUp" value="<?php echo $overlayPlage ?>"/>
                                                    <input type="hidden" name="nomPopUp" value="<?php echo $nomPlage ?>"/>
                                                    <input type="hidden" name="id_plage_ponctuelle" value="<?php echo $plage['id_plage_ponctuelle'] ?>"/>
                                                    <button type="submit"><img class="btn-supr" src="../svg/croix_plage.svg" alt="supprimer" width="2em" height="2em"></button>
                                                </form>
                                            </div>
                                    <?php }
                                    } else { ?>
                                     <p> Aucune plage définie </p>
                                
                                <?php } ?>
                                            
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
    <?php } ?>
    
    <?php 
        echo file_get_contents('../header-footer/footerP.html');
    ?>
</body>

</html>
