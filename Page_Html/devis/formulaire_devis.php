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
    $stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = {$_SESSION['id']};");
    $stmt->execute();
    $photo = $stmt->fetch();

    //on récupère l'id de la taxe de séjour associé au logement du devis
    
    /*$stmt = $dbh->prepare("SELECT taxe_sejour from locbreizh._logement where id_compte = {$_SESSION['id']};");
    $stmt->execute();
    $photo = $stmt->fetch();*/
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

            $stmt = $dbh->prepare("SELECT prix_charges 
            FROM locbreizh._demande_devis d 
            JOIN locbreizh._logement l ON  d.logement = l.id_logement 
            JOIN locbreizh._possede_charges_associee_logement c on c.id_logement = l.id_logement
            WHERE num_demande_devis = $num_demande and nom_charges = 'menage';");
            $stmt->execute();
            $menage1 = $stmt->fetch();
            
            $stmt = $dbh->prepare("SELECT prix_charges 
            FROM locbreizh._demande_devis d 
            JOIN locbreizh._logement l ON  d.logement = l.id_logement 
            JOIN locbreizh._possede_charges_associee_logement c on c.id_logement = l.id_logement
            WHERE num_demande_devis = $num_demande and nom_charges = 'animaux';");
            $stmt->execute();
            $animaux1 = $stmt->fetch();

            $stmt = $dbh->prepare("SELECT prix_charges 
            FROM locbreizh._demande_devis d 
            JOIN locbreizh._logement l ON  d.logement = l.id_logement 
            JOIN locbreizh._possede_charges_associee_logement c on c.id_logement = l.id_logement
            WHERE num_demande_devis = $num_demande and nom_charges = 'personnes_supplementaires';");
            $stmt->execute();
            $vac_sup1 = $stmt->fetch();


            $stmt = $dbh->prepare("SELECT prix_charges 
            from locbreizh._comporte_charges_associee_demande_devis
            WHERE num_demande_devis = $num_demande and nom_charges = 'menage';");
            $stmt->execute();
            $menage2 = $stmt->fetch();

            $stmt = $dbh->prepare("SELECT prix_charges 
            from locbreizh._comporte_charges_associee_demande_devis 
            WHERE num_demande_devis = $num_demande and nom_charges = 'animaux';");
            $stmt->execute();
            $animaux2 = $stmt->fetch();


            $stmt = $dbh->prepare("SELECT nombre 
            from locbreizh._comporte_charges_associee_demande_devis 
            WHERE num_demande_devis = $num_demande and nom_charges = 'personnes_supplementaires';");
            $stmt->execute();
            $vac_sup2 = $stmt->fetch();

            // taxe de sejour
            $stmt = $dbh->prepare("SELECT prix_journalier_adulte 
            FROM locbreizh._demande_devis d 
            JOIN locbreizh._logement l ON  d.logement = l.id_logement 
            join locbreizh._taxe_sejour t on l.taxe_sejour = t.id_taxe
            WHERE num_demande_devis = $num_demande;");
            $stmt->execute();
            $taxe = $stmt->fetch();
            
        ?>
    <form name="formulaire" action="ajouter_devis.php" method="post">
    <fieldset>
        <h1 class="policetitre colorbleu">La demande de devis de <?php echo $infos['prenom'] . ' '. $infos['nom']; ?> !</h1>
        
    
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
                    if (isset($_SESSION['erreurs']['valide_dates'])){ ?>
                        <p id="erreur" class=erreur><?php echo $_SESSION['erreurs']['valide_dates']; ?></p>
                    <?php } ?>
                <div class="logrow">
                    <div class="log2vct"> 
                        <label for="date_val">Durée de validité du devis (en jour)</label>
                        <input class="valduree" step="1" type="number" id="date_val" name="date_val" min="1" max="999" value="<?php if(isset($_SESSION['valeurs_complete']['date_val'])){echo $_SESSION['valeurs_complete']['date_val'];} ?>" required /> 
                    </div>

                    <div class="annulation">
                        <label for="annulation">Condition annulation</label>
                        <select id="annulation" name="annulation" class="devis_select">
                            <option value="stricte" <?php if(isset($_SESSION['valeurs_complete']['annulation'])) {if($_SESSION['valeurs_complete']['annulation'] == 'stricte') { ?> selected <?php }}?>>Stricte</option>
                            <option value="flexible" <?php if(isset($_SESSION['valeurs_complete']['annulation'])) {if($_SESSION['valeurs_complete']['annulation'] == 'flexible') { ?> selected <?php }}?>>Flexible</option>
                            <option value="non_remboursable" <?php if(isset($_SESSION['valeurs_complete']['annulation'])) {if($_SESSION['valeurs_complete']['annulation'] == 'non_remboursable') { ?> selected <?php }}?>>Non remboursable</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="cardSupplementsP">
            <h2 style="text-align:center;  font-family: 'Quicksand';">Charges aditionnelles</h2>
            <div class="logcheckbox">
                <!--pre-remplie les infos si ils sont dans get-->
                <input type="checkbox" id="animaux" name="animaux" <?php if(isset($_SESSION['valeurs_complete']['animaux'])){echo "checked";} else if(isset($animaux2['prix_charges']) && $animaux2['prix_charges'] !=''){ echo 'checked';}; ?>>
                <label for="animaux"> Animaux </label>
                </div>
                <div class="logcheckbox">
                <!--pre-remplie les infos si ils sont dans get-->
                <input type="checkbox" id="menage" name="menage" <?php  if(isset($_SESSION['valeurs_complete']['menage'])){echo "checked";} else if (isset($menage2['prix_charges']) && $menage2['prix_charges'] !=''){echo 'checked';}; ?>>
                 <label for="menage"> Ménage </label>
                </div>
                <!--pre-remplie les infos si ils sont dans get-->
                <div class="logpc">
                <label style="text-align:center;" for="nb_pers_supp">Vacanciers supplémentaires</label>
                <input class="lognb" type="text" id="vacanciers_sup" name="vacanciers_sup" min="0" max="100" placeholder="0" value="<?php if(isset($_SESSION['valeurs_complete']['vacanciers_sup'])){echo $_SESSION['valeurs_complete']['vacanciers_sup'];}else if (isset($vac_sup2['nombre']) && $vac_sup2['nombre']!=''){echo $vac_sup2['nombre'];}; ?>"/>
                </div>
            </div>
            </div>

            <input type="hidden" id="id_demande" name="id_demande" value=<?PHP echo $_GET['demande']; ?>>
            
        </fieldset>

        <?php 

        ?>
        <fieldset>
            <h1 class="policetitre colorbleu">Details pour le paiement</h1>
            <div class="devisrow">
                <p class="ren">Calculé grâce au planning : </p>
                <div class="deviscol">
                <label for="tarif_loc">Tarif moyen HT par nuit(en €) :</label>
                <input class="logvct" type="number" id="tarif_loc" name="tarif_loc" value="<?php if(isset($_SESSION['valeurs_complete']['tarif_loc'])){echo $_SESSION['valeurs_complete']['tarif_loc'];} ?>" required readonly/> 
                </div>
            </div>
            
            <hr class="hrP">

            
            <div id="resultat" class="deviscol">
            <input type="hidden" id="nuitees" name="nuitees" value="" readonly>
            <input type="hidden" id="prixCharges" name="prixCharges" value="" readonly>


                <div class="devisrow">
                    <p class="ren">Prix :</p>
                </div>

                <div class="devisrow">
                    <div class="deviscol">
                        <div class="deviscolinput">
                            <p>Sous total HT (en €) </p>
                            <input class="inputprix" id="sousTotal_HT" name="sousTotal_HT" value="" readonly>
                        </div>
                        <div class="deviscolinput">
                            <p>Frais de service HT (en €) </p>
                            <input class="inputprix" id="fraisService_HT" name="fraisService_HT" value="" readonly>
                        </div> 
                    </div> 

                    <div class="deviscol">    
                        <div class="devisrowp">
                            
                            <div class="deviscolinput">
                                <p>Sous total TTC (en €) </p>
                                <input class="inputprix" id="sousTotal_TTC" name="sousTotal_TTC" value="" readonly>
                            </div>
                        </div>
                        <div class="devisrowp">
                            
                            <div class="deviscolinput">
                                <p>Frais de service TTC (en €) </p>
                                <input class="inputprix" id="fraisService_TTC" name="fraisService_TTC" value="" readonly>
                            </div>
                        </div>

                        <div class="devisrowp">
                            <div class="deviscolinput">
                                <p>Total taxe_sejour (en €) </p>
                                <input class="inputprix" id="taxe_sejour" name="taxe_sejour" value="" readonly>
                            </div>
                        </div>
                        
                        <div class="devisrowp">
                            <div class="deviscolinput">
                                <p>Prix total (en €) </p>
                                <input class="inputprix" id="prixTotal" name="prixTotal" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div>
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

        if (isset($_GET['erreur']) && $_GET['erreur'] === '0'){
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
<script>
    var menage = <?php echo json_encode($menage1); ?>;
    var animaux = <?php echo json_encode($animaux1); ?>;
    var vac_sup = <?php echo json_encode($vac_sup1); ?>;
    var taxe_sejour = <?php echo json_encode($taxe); ?>;

    function arrondi(number) {
    return number.toFixed(2);
}
    let totalSum = 0;
    
    function getPrixPlagePonctuelle() {
        totalSum = 0;
        const num_demande = <?php echo $_GET['demande']; ?>; // On récupère le num de la demande de devis

        // récupère la date d'arrivée et de départ
        const dateArrivee = document.getElementById('date_arrivee').value;
        const dateDepart = document.getElementById('date_depart').value;

        fetch(`total_tarif_nuit.php?num_demande=${num_demande}&date_arrivee=${dateArrivee}&date_depart=${dateDepart}`)
            .then(response => response.json())
            .then(data => {
                // récupère l'ensemble des prix puis on en fait la moyenne pour l'afficher
                const prices = data.prices;
                totalSum = prices.reduce((sum, price) => sum + parseFloat(price), 0);
                const average = prices.length > 0 ? totalSum / prices.length : 0;

                const totalInputElement = document.getElementById('tarif_loc');
                totalInputElement.value = `${average}`;
                calcul();

            })
            .catch(error => {
                console.error('Error fetching Prices:', error);
            });
    }
    getPrixPlagePonctuelle();

    document.getElementById('date_arrivee').addEventListener('input', getPrixPlagePonctuelle);
    document.getElementById('date_depart').addEventListener('input', getPrixPlagePonctuelle);


    function calcul() {

        const nb_personnes = parseInt(document.getElementById('nb_pers').value)|| 0;
        const nb_pers_supp = parseInt(document.getElementById('vacanciers_sup').value) || 0;

        // Vérifiez si les cases à cocher sont cochées
        const menageChecked = document.getElementById('menage').checked;
        const animauxChecked = document.getElementById('animaux').checked;

        // Calculez total_charges en fonction des cases cochées
        let total_charges = 0;
        if (menageChecked) {
            total_charges += parseFloat(menage.prix_charges);
        }
        if (animauxChecked) {
            total_charges += parseFloat(animaux.prix_charges);
        }
        total_charges += parseFloat(vac_sup.prix_charges) * nb_pers_supp;

        const sousTotal_HT = totalSum + total_charges;
        const sousTotal_TTC = sousTotal_HT * 1.1;
        const fraisService_HT = 0.01 * sousTotal_HT;
        const fraisService_TTC = fraisService_HT * 1.2;
        const total_taxe_sejour =  taxe_sejour.prix_journalier_adulte * (nb_pers_supp + nb_personnes);
        const prixTotal = sousTotal_TTC + fraisService_TTC + total_taxe_sejour;

        // modifie tous les champs concernés
        document.getElementById('nuitees').value = `${arrondi(totalSum)}`;
        document.getElementById('prixCharges').value = `${arrondi(total_charges)}`;
        document.getElementById('sousTotal_HT').value = `${arrondi(sousTotal_HT)}`;
        document.getElementById('sousTotal_TTC').value = `${arrondi(sousTotal_TTC)}`;
        document.getElementById('fraisService_HT').value = `${arrondi(fraisService_HT)}`;
        document.getElementById('fraisService_TTC').value = `${arrondi(fraisService_TTC)}`;
        document.getElementById('taxe_sejour').value = `${arrondi(total_taxe_sejour)}`;
        document.getElementById('prixTotal').value = `${arrondi(prixTotal)}`;
    }
    document.getElementById('vacanciers_sup').addEventListener('input', calcul);
    document.getElementById('menage').addEventListener('input', calcul);
    document.getElementById('animaux').addEventListener('input', calcul);
    document.getElementById('nb_pers').addEventListener('input', calcul);

    document.getElementById('tarif_loc').addEventListener('input', calcul);


    // delai pour attendre le chargement de la page et les autres calcul précédents
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            calcul();
    }, 300);
    });


    

</script>