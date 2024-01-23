<?php
    session_start();
    if(isset($_SESSION['erreurs'])){
        $erreurs = $_SESSION['erreurs'];
    }
    include('../parametre_connexion.php');
    try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print "Erreur !:" . $e->getMessage() . "<br/>";
        die();
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire devis</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
    <script src="../scriptPopupFeedback.js"></script>
</head>
<body>
    <?php 
        include('../header-footer/choose_header.php');
    ?>

    <main class="MainTablo">
        <div class="headtabloP"> 
            <h1>Faire mon devis</h1>
        </div>
        <?php
            // id fictif
            include('../parametre_connexion.php');
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $num_demande = $_GET["demande"];

            $stmt = $dbh->prepare("SELECT 
                nom,
                prenom, 
                date_arrivee,
                date_depart, 
                nb_personnes,
                nb_personnes_logement
                FROM locbreizh._demande_devis 
                JOIN locbreizh._compte ON _demande_devis.client = id_compte 
                join locbreizh._logement on _logement.id_logement = logement
                WHERE num_demande_devis = $num_demande"
            );
            $stmt->execute();
            $infos = $stmt->fetch(); 

            // recupere le nombre maximum de personnes pour le logement

            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._comporte_charges_associee_demande_devis 
            where num_demande_devis = $num_demande and nom_charges = 'menage';");
            $stmt->execute();
            $menage = $stmt->fetch();

            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._comporte_charges_associee_demande_devis 
            where num_demande_devis = $num_demande and nom_charges = 'animaux';");
            $stmt->execute();
            $animaux = $stmt->fetch();

            $stmt = $dbh->prepare("SELECT prix_charges, nombre from locbreizh._comporte_charges_associee_demande_devis 
            where num_demande_devis = $num_demande and nom_charges = 'personnes_supplementaires';");
            $stmt->execute();
            $vac_sup = $stmt->fetch();
            
        ?>
    <fieldset>
        <h1 class="policetitre colorbleu">La demande de devis de <?php echo $infos['prenom'] . ' '. $infos['nom']; ?> !</h1>
        <form name="formulaire" action="ajouter_devis.php" method="post">
    
        <div class="logrow">
            <div class="devispc">
                <div class="logrow">
                    <div class="log3vct">  
                        <label for="date_arrivee">Date d'arrivée</label>
                        <input class="logvct" type="date" id="date_arrivee" name="date_arrivee" value="<?php if(isset($_SESSION['valeurs_complete']['date_arrivee'])){
                                                                                                                echo $_SESSION['valeurs_complete']['date_arrivee'];}
                                                                                                            else{echo $infos['date_arrivee']; }; ?>" required /> 
                    </div>
                    <div class="log3vct">   
                        <label for="date_depart">Date de départ</label>
                        <input class="logvct" type="date" id="date_depart" name="date_depart" value="<?php if(isset($_SESSION['valeurs_complete']['date_depart'])){ echo $_SESSION['valeurs_complete']['date_depart'];}else{echo $infos['date_depart']; }; ?>" required /> 
                    </div>
                    <div class="log3vct">  
                    <label for="nb_pers">Nombre de personnes</label>
                    <!--appel php pour set la max value de nb personne par rapport au choix du proprio-->
                    <input class="logvct" type="number" id="nb_pers" name="nb_pers" placeholder="nombre de personnes" min="1" max=<?php echo $infos['nb_personnes_logement']; ?> value="<?php if(isset($_SESSION['valeurs_complete']['nb_pers'])){echo $_SESSION['valeurs_complete']['nb_pers'];}else{ echo $infos['nb_personnes'];}; ?>" required />
                    </div>
                </div>
                <?php
                    if ($_SESSION['erreurs']['valide_dates']){ ?>
                        <p id="erreur" class=erreur><?php echo $_SESSION['erreurs']['valide_dates']; ?></p>
                    <?php } ?>
                <div class="logrow">
                    <div class="log2vct">  
                        <label for="delais_accept">Délais d'acceptation ( de 1 à 4 jours )</label>
                        <input class="logvct" type="number" min="1" max="4" id="delais_accept" name="delais_accept" value="<?php if(isset($_SESSION['valeurs_complete']['delais_accept'])){echo $_SESSION['valeurs_complete']['delais_accept'];} ?>" required />
                    </div>
                    <div class="log2vct"> 
                        <label for="date_val">Date validité du devis ( en mois)</label>
                        <input class="logvct" type="number" id="date_val" name="date_val" value="<?php if(isset($_SESSION['valeurs_complete']['date_val'])){echo $_SESSION['valeurs_complete']['date_val'];} ?>" required /> 
                    </div>
                </div>
            </div>

            <div class="cardSupplementsP">
            <h2 style="text-align:center;  font-family: 'Quicksand';">Charges aditionnelles</h2>
                <div class="logcheckbox">
                <!--pre-remplie les infos si ils sont dans get-->
                <input type="checkbox" id="animaux" name="animaux" <?php  if(isset($_SESSION['valeurs_complete']['menage'])){echo "checked";} else if ($menage['prix_charges'] !=''){echo 'checked';}; ?>>
                 <label for="animaux"> Animaux </label>
                </div>
                <div class="logcheckbox">
                <!--pre-remplie les infos si ils sont dans get-->
                <input type="checkbox" id="menage" name="menage" <?php if(isset($_SESSION['valeurs_complete']['animaux'])){echo "checked";} else if($animaux['prix_charges'] !=''){ echo 'checked';}; ?>>
                <label for="menage"> Menage </label>
                </div>
                <!--pre-remplie les infos si ils sont dans get-->
                <div class="logpc">
                <label style="text-align:center;" for="nb_pers_supp">Vacanciers supplémentaires</label>
                <input class="lognb" type="text" id="vacanciers_sup" name="vacanciers_sup" min="0" max="100" placeholder="0" value="<?php if(isset($_SESSION['valeurs_complete']['vacanciers_sup'])){echo $_SESSION['valeurs_complete']['vacanciers_sup'];}else if ($vac_sup['nombre']!=''){echo $vac_sup['nombre'];}; ?>"/>
                </div>
            </div>
            </div>

            <input type="hidden" id="id_demande" name="id_demande" value=<?PHP echo $_GET['demande']; ?>>

            <div class="annulation">
                <label for="annulation">Condition annulation :</label>
                <select id="annulation" name="annulation" class="devis_select">
                    <option value="stricte" <?php if(isset($_SESSION['valeurs_complete']['annulation'])) {if($_SESSION['valeurs_complete']['annulation'] == 'stricte') { ?> selected <?php }}?>>Stricte</option>
                    <option value="flexible" <?php if(isset($_SESSION['valeurs_complete']['annulation'])) {if($_SESSION['valeurs_complete']['annulation'] == 'flexible') { ?> selected <?php }}?>>Flexible</option>
                    <option value="non_remboursable" <?php if(isset($_SESSION['valeurs_complete']['annulation'])) {if($_SESSION['valeurs_complete']['annulation'] == 'non_remboursable') { ?> selected <?php }}?>>Non remboursable</option>
                </select>
            </div>
        </fieldset>

        <?php 

            
                            ???
            $stmt = $dbh->prepare("SELECT prix_plage_ponctuelle
            FROM locbreizh._demande_devis d
              JOIN locbreizh._logement l ON d.logement = l.id_logement
              join locbreizh._planning p on p.code_planning = l.code_planning
              join locbreizh._plage_ponctuelle p1 on p1.code_planning = p.code_planning
              join locbreizh._plage_ponctuelle_disponible p2 on p2.id_plage_ponctuelle = p1.id_plage_ponctuelle
            WHERE num_demande_devis = $num_demande and jour_plage_ponctuelle >= '???' and jour_plage_ponctuelle <= '???';");
            $stmt->execute();
            $tarif = $stmt->fetch();

            // taxe de sejour
            $stmt = $dbh->prepare("SELECT tarif_base_HT FROM locbreizh._demande_devis d 
            JOIN locbreizh._logement l ON  d.logement = l.id_logement 
            WHERE num_demande_devis = $num_demande;");
            $stmt->execute();
            $tarif_base = $stmt->fetch();

        ?>
        <fieldset>
            <h1 class="policetitre colorbleu">Details pour le paiement</h1>
            <div class="devisrow">
                <p class="ren">A VERIFIER</p>
                <div class="deviscol">
                <label for="tarif_loc">Tarif HT de la location du logement (en €) :</label>
                <input class="logvct" type="number" id="tarif_loc" name="tarif_loc" value="<?php if(isset($_SESSION['valeurs_complete']['tarif_loc'])){echo $_SESSION['valeurs_complete']['tarif_loc'];} ?>" required /> 
                </div>
                <input class="btn-ajoutlog" type="button" value="Calculer" onclick="calcul()"/>
            </div>
            
            <hr class="hrP">

            
            <div id="resultat" class="deviscol">
                    <div class="devisrow">
                    <p class="ren">Calculer automatiquement</p>
                        <div class="deviscolinput">
                            <p> Total HT (en €) </p>
                            <input class="logvct" id="totalht" name="totalht" value="" disabled>
                        </div>
                        <div class="deviscolinput">
                            <p> Total TTC (en €) </p>
                            <input class="logvct" id="totalht" name="totalht" value="" disabled>
                        </div>
                        <div class="deviscolinput">
                            <p> Taxe de séjour (en €) </p>
                            <input class="logvct" id="totalht" name="totalht" value="" disabled>
                        </div>
                    </div>
                    <div class="devisrow">
                    <div class="devisvct">
                            <p> Montant total du devis (en €) </p>
                            <input class="logvct" id="totalht" name="totalht" value=""  disabled>
                        </div>
                    <div class="devisvct">
                            <p> Frais de plateforme HT (en €) </p>
                            <input class="logvct" id="totalht" name="totalht" value="" disabled>
                        </div>
                    <div class="devisvct">
                        <p> Frais de plateforme TTC (en €) </p>
                            <input class="logvct" id="totalht" name="totalht" value="" disabled>
                        </div>
                    </div>
            </div>
            <script>
                function roundDecimal(nombre, precision){
                    var precision = precision || 2;
                    var tmp = Math.pow(10, precision);
                    return Math.round( nombre*tmp )/tmp;
                }

                function calcul() {
                    let baliseprixloc = document.getElementById("tarif_loc")
                    let prix_loc = baliseprixloc.value
                    let baliseprixcharges = document.getElementById("charges")
                    let prix_charges = baliseprixcharges.value
                    let html = "";
                    total_HT = parseInt(prix_loc) + parseInt(prix_charges);
                    total_TTC = roundDecimal(total_HT * 1.1,2)
                    taxe_sejour = 120;
                    total_montant_devis = roundDecimal(total_TTC + taxe_sejour,2)
                    total_plateforme_HT = roundDecimal(total_montant_devis*1.01,2)
                    total_plateforme_TTC = roundDecimal(total_plateforme_HT * 1.2,2)
                    html += `<div class="deviscol">`;
                    html += `<div class="devisrow">`;
                    html += `<p class="ren">Calculer automatiquement</p>`;
                    html += `<div class="deviscolinput"><p> Total HT (en €) </p><input class="logvct" id="totalht" name="totalht" value="${total_HT}€" disabled></div>`;
                    html += `<div class="deviscolinput"><p> Total TTC (en €) </p><input class="logvct" id="totalht" name="totalht" value="${total_TTC}€" disabled></div>`;
                    html += `<div class="deviscolinput"><p> Taxe de séjour (en €) </p><input class="logvct" id="totalht" name="totalht" value="${taxe_sejour}€" disabled></div>`;
                    html += '</div>';
                    html += `<div class="devisrow">`;
                    html += `<div class="devisvct"><p> Montant total du devis</p><input class="logvct" id="totalht" name="totalht" value="${total_montant_devis}€"  disabled></div>`;
                    html += `<div class="devisvct"><p> Frais de plateforme HT</p><input class="logvct" id="totalht" name="totalht" value="${total_plateforme_HT}€" disabled></div>`;
                    html += `<div class="devisvct"><p> Frais de plateforme TT</p><input class="logvct" id="totalht" name="totalht" value="${total_plateforme_TTC} €" disabled></div>`;
                    html += '</div>';
                    html += '</div>';
                    document.getElementById("resultat").innerHTML = html;
                    document.getElementById("envoyerDevisBtn").removeAttribute("disabled");
                }
            </script>
            <br/>
            <input type="hidden" id="id_demande" name ="id_demande" value=<?php echo $_GET['demande'];?>>
            </fieldset>
            <input class="btn-envoidevis" type="submit" id="envoyerDevisBtn" value="Envoyer le devis" />
        </form>
        <div id="overlayDemandeDeDevis" onclick="closePopupFeedback('popupFeedback', 'overlayDemandeDeDevis')"></div>
        <div id="popupFeedback" class="popupFeedback">
            <p>Votre devis a bien été envoyée !</p>
            <a href="../gestion_devis/gestion_des_devis_proprio.php" class="btn-accueil"></button>OK</a>
        </div>
    </main>
    
    <?php 
        echo file_get_contents('../header-footer/footerP.html');

        if ($_GET['erreur'] === '0'){
            ?>
            <script>
                openPopupFeedback('popupFeedback', 'overlayDemandeDeDevis');
            </script>
            <?php
        }

        unset($_SESSION['valeurs_complete']);
        unset($_SESSION['erreurs']['valide_dates']);
    ?>

</body>
</html>