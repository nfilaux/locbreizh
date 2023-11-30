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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire devis</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
</head>
<body>
    <?php 
        include('../header-footer/choose_header.php');
    ?>

    <main>
        <?php
            // id fictif
            include('../parametre_connexion.php');
            $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // obtenu par l'url ($_GET)
            $d_a ="2015-02-25-";
            $d_d ="2010-02-23";
            $date_val = "13.2";
            $delai_accept = "3";

            $reqNomClient = $dbh->prepare("SELECT nom, prenom FROM locbreizh._demande_devis INNER JOIN locbreizh._compte ON _demande_devis.client = id_compte WHERE num_demande_devis = {$_GET['demande']}");
            $reqNomClient->execute();
            $infos_user = $reqNomClient->fetch(); 

            // recupere le nombre maximum de personnes pour le logement
            $stmt = $dbh->prepare("SELECT nb_personnes_logement as nb_pers from locbreizh._logement l
            join locbreizh._demande_devis d on d.logement = l.id_logement
            where d.num_demande_devis = {$_GET['demande']};");
            $stmt->execute();
            $nb_max = $stmt->fetch();

            $num_demande = $_GET["demande"];
            
            //on récupère les informatiosn pour préremplir le devis en fonction de la demande de devis qui lui est associé
            
            $stmt = $dbh->prepare("SELECT date_arrivee,date_depart,nb_personnes from locbreizh._demande_devis where num_demande_devis = $num_demande;");
            $stmt->execute();
            $infos_demande = $stmt->fetch();

            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._comporte_charges_associee_demande_devis where num_demande_devis = $num_demande and nom_charges = 'menage';");
            $stmt->execute();
            $menage = $stmt->fetchColumn();

            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._comporte_charges_associee_demande_devis where num_demande_devis = $num_demande and nom_charges = 'animaux';");
            $stmt->execute();
            $animaux = $stmt->fetchColumn();

            $stmt = $dbh->prepare("SELECT prix_charges from locbreizh._comporte_charges_associee_demande_devis where num_demande_devis = $num_demande and nom_charges = 'personnes_supplementaires';");
            $stmt->execute();
            $vac_sup = $stmt->fetchColumn();

            //echo $num_demande;
            //print("c'est un chien mec" . $animaux);
            if ($animaux == ''){echo 'vide';};
            
        ?>
        <style>#erreur {color : red;}</style>
        <h1>La demande de devis de <?php echo $infos_user['prenom'] . ' '. $infos_user['nom']; ?> !</h1>
        <form name="formulaire" action="ajouter_devis.php" method="post">

            <label for="date_arrivee">date d'arrivée:</label>
            <input type="date" id="date_arrivee" name="date_arrivee" value="<?php if(isset($_SESSION['valeurs_complete']['date_arrivee'])){ echo $_SESSION['valeurs_complete']['date_arrivee'];}else{if(!isset($erreurs['valide_dates'])){echo $infos_demande['date_arrivee']; }}; ?>" required /> 
            <br/>

            <label for="date_depart">date de départ:</label>
            <input type="date" id="date_depart" name="date_depart" value="<?php if(isset($_SESSION['valeurs_complete']['date_depart'])){ echo $_SESSION['valeurs_complete']['date_depart'];}else{if(!isset($erreurs['valide_dates'])){echo $infos_demande['date_depart']; }}; ?>" required /> 
            <br/>

            <?php
            if (isset($erreurs['valide_dates'])){
                echo '<p id="erreur">' . $erreurs['valide_dates'] . '</p>';
            }
            ?>

            <label for="nb_pers">nombre de personnes:</label>
            <input type="number" id="nb_pers" name="nb_pers" placeholder="nombre de personnes" min="1" max=<?php echo $nb_max['nb_pers']; ?> value="<?php if(isset($_SESSION['valeurs_complete']['nb_pers'])){echo $_SESSION['valeurs_complete']['nb_pers'];}else{if(!isset($erreurs['valide_dates'])){echo $infos_demande['nb_personnes'];}}; ?>" required />
            <br/>

            
            <label for="delais_accept">délais d'acceptation ( de 1 à 4 jours ) :</label>
            <input type="number" min="1" max="4" id="delais_accept" name="delais_accept" value="<?php if(isset($_SESSION['valeurs_complete']['delais_accept'])){echo $_SESSION['valeurs_complete']['delais_accept'];} ?>" required />
            <br />

            <label for="date_val">date validité du devis ( en mois) :</label>
            <input type="number" id="date_val" name="date_val" value="<?php if(isset($_SESSION['valeurs_complete']['date_val'])){echo $_SESSION['valeurs_complete']['date_val'];} ?>" required /> 
            <br />

            <label for="annulation">Condition annulation</label>
            <input type="text" id="annulation" name="annulation" value="<?php if(isset($_SESSION['valeurs_complete']['annulation'])){echo $_SESSION['valeurs_complete']['annulation'];} ?>" required/>

            <?php
            if (isset($erreurs['cond_annul'])){
                echo '<p id="erreur">' . $erreurs['cond_annul'] .  '</p>';
            }
            ?>

            <input type="hidden" id="id_demande" name="id_demande" value=<?PHP echo $_GET['demande']; ?>>

            <h1>Charges aditionnelles</h1>

            <input type="checkbox" id="animaux" name="animaux" <?php if(isset($_SESSION['valeurs_complete']['animaux'])){echo 'checked';}else if ($menage !=''){echo 'checked';}; ?>>
            <label for="animaux"> Animaux </label>

            <input type="checkbox" id="menage" name="menage" <?php if(isset($_SESSION['valeurs_complete']['menage'])){echo 'checked';}else{if ($animaux !=''){echo 'checked';}}; ?>>
            <label for="menage"> Menage </label>

            <input type="text" id="vacanciers_sup" name="vacanciers_sup" min="0" max="100" placeholder="vacanciers supplémentaires" value="<?php if(isset($_SESSION['valeurs_complete']['vacanciers_sup'])){echo $_SESSION['valeurs_complete']['vacanciers_sup'];}else{if ($vac_sup!=''){echo $vac_sup;}}; ?>"/>

            <h1>Details pour le paiement</h1>

            <p>à renseigner</p>

            <label for="tarif_loc">Tarif HT de la location du logement (en €) :</label>
            <input type="number" id="tarif_loc" name="tarif_loc" value="<?php if(isset($_SESSION['valeurs_complete']['tarif_loc'])){echo $_SESSION['valeurs_complete']['tarif_loc'];} ?>" required /> 
            <br/>

            <label for="charges additionnelles">Charges additionnelles HT (en €) :</label>
            <input type="number" id="charges" name="charges" value="<?php if(isset($_SESSION['valeurs_complete']['charges'])){echo $_SESSION['valeurs_complete']['charges'];} ?>" required />
            <hr>

            <p>Calculer automatiquement</p>
            <div id="resultat">
                    <p> Total HT (en € ) </p>
                    <p> Total TTC (en € ) </p>
                    <p> Taxe de séjour (en € ) </p>
                    <p> Montant total du devis (en € ) </p>
                    <p> Frais de plateforme HT (en € ) </p>
                    <p> Frais de plateforme TTC (en € ) </p>
                <?php
                //}
                ?>
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
                    total_HT = prix_loc + prix_charges;
                    total_TTC = roundDecimal(total_HT * 1.1,2)
                    taxe_sejour = 120;
                    total_montant_devis = roundDecimal(total_TTC + taxe_sejour,2)
                    total_plateforme_HT = roundDecimal(total_montant_devis*1.01,2)
                    total_plateforme_TTC = roundDecimal(total_plateforme_HT * 1.2,2)
                    html += `<p> Total HT : ${total_HT}€</p>`;
                    html += `<p> Total TTC : ${total_TTC}€</p>`;
                    html += `<p> Taxe séjour : ${taxe_sejour}€</p>`;
                    html += `<p> Montant total du devis : ${total_montant_devis}€</p>`;
                    html += `<p> Frais de plateforme HT : ${total_plateforme_HT}€</p>`;
                    html += `<p> Frais de plateforme TTC: ${total_plateforme_TTC}€</p>`;
                    document.getElementById("resultat").innerHTML = html;
                    document.getElementById("envoyerDevisBtn").removeAttribute("disabled");
                }
            </script>
            <input type="button" value="Calculer" onclick="calcul()"/>
            <br/>
            <input type="hidden" id="id_demande" name ="id_demande" value=<?php echo $_GET['demande'];?>>
            <input type="submit" id="envoyerDevisBtn" value="Envoyer le devis" disabled />
        </form>
    </main>
    
    <?php 
        echo file_get_contents('../header-footer/footer.html');
    ?>

</body>
</html>