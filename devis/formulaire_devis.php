<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    session_start();
    $erreur = $_SESSION['erreurs'];
    // id fictif
    $_SESSION['id'] = 1;
    include('../parametre_connexion.php');
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // obtenu par l'url ($_GET)
    $id_demande = $_GET['num'];
    $d_a ="2015-02-25-";
    $d_d ="2010-02-23";
    $date_val = "13.2";
    $delai_accept = "3";


    $reqNomClient = $dbh->prepare("SELECT nom, prenom, pseudo FROM locbreizh._demande_devis INNER JOIN locbreizh._compte ON _demande_devis.client = id_compte WHERE num_demande_devis = $id_demande");
    $reqNomClient->execute();
    $res = $reqNomClient->fetch();    
    $pseudo = $res['pseudo'];
?>
<style>#erreur {color : red;}</style>
<h1>La demande de devis de <?php echo $pseudo; ?> !</h1>
    <form name="formulaire" action="ajouter_devis.php" method="post">

            <label for="date_arrivee">date d'arrivée:</label>
            <input type="date" id="date_arrivee" name="date_arrivee" value="<?php echo $_SESSION['valeurs_complete']['date_arrivee']; ?>" required /> 
            <br />

            <label for="date_depart">date de départ:</label>
            <input type="date" id="date_depart" name="date_depart" required /> 
            <br/>

            <?php
            if (isset($erreur['valide_dates'])){
                echo '<p>' . $SESSION['valeurs_complete']['date_arrivee'];
                echo '<p id="erreur">' . $erreur['valide_dates'] .  '</p>';
            }
            ?>

            <label for="nb_pers">nombre de personnes:</label>
            <input type="number" id="nb_pers" name="nb_pers" placeholder="nombre de personnes" value="<?php echo $_SESSION['valeurs_complete']['nb_pers']; ?>" required />
            <br/>

            
            <label for="delais_accept">délais d'acceptation ( de 1 à 4 jours ) :</label>
            <input type="number" min="1" max="4" id="delais_accept" name="delais_accept" value="<?php echo $_SESSION['valeurs_complete']['delais_accept']; ?>" required />
            <br />

            <label for="date_val">date validité du devis ( en mois) :</label>
            <input type="number" id="date_val" name="date_val" value="<?php echo $_SESSION['valeurs_complete']['date_val']; ?>" required /> 
            <br />

            <label for="annulation">Condition annulation</label>
            <input type="text" id="annulation" name="annulation" value="<?php echo $_SESSION['valeurs_complete']['cond_annul']; ?>" required/>

            <?php
            if (isset($erreur['cond_annul'])){
                echo '<p id="erreur">' . $erreur['cond_annul'] .  '</p>';
            }
            ?>

            <input type="hidden" id="id_demande" name="id_demande" value=<?PHP echo $_GET['num']; ?>>

            <h1>Charges aditionnelles</h1>

            <input type="checkbox" id="animaux">
            <label for="animaux"> Animaux </label>

            <input type="checkbox" id="menage">
            <label for="menage"> Menage </label>

            <input type="text" id="vacanciers_sup" name="vacanciers_sup" placeholder="vacanciers supplémentaires" />

            <h1>Details pour le paiement</h1>

            <p>à renseigner</p>

            <label for="tarif_loc">Tarif HT de la location du logement (en €) :</label>
            <input type="number" id="tarif_loc" name="tarif_loc" value="<?php echo $_SESSION['valeurs_complete']['tarif_loc']; ?>" required /> 
            <br/>

            <label for="charges additionnelles">Charges additionnelles HT (en €) :</label>
            <input type="number" id="charges" name="charges" value="<?php echo $_SESSION['valeurs_complete']['charges']; ?>" required />
            <hr>

            <p>Calculer automatiquement</p>
            <div id="resultat">
                <?php
                //if ($_GET['calcul'] == true){
                    ?>
                    <p> Total HT (en € ) </p>
                    <p> Total TTC (en € ) </p>
                    <p> taxe de séjour (en € ) </p>
                    <p> montant total du devis (en € ) </p>
                    <p> frais de plateforme HT (en € ) </p>
                    <p> frais de plateforme TTC (en € ) </p>
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
                    /*let html = "";
                    html += `<p> tarif_loc : ${prixloc}€</p>`;
                    document.getElementById("resultat").innerHTML = html;*/
                     // affiche ce qui est contenu dans la balise name
                    let html = "";
                    //prix_loc = 200;
                    total_HT = prix_loc + prix_charges;
                    total_TTC = roundDecimal(total_HT * 1.1,2)
                    taxe_sejour = 120;
                    total_montant_devis = roundDecimal(total_TTC + taxe_sejour,2)
                    total_plateforme_HT = roundDecimal(total_montant_devis*1.01,2)
                    total_plateforme_TTC = roundDecimal(total_plateforme_HT * 1.2,2)
                    html += `<p> Total HT : ${total_HT}€</p>`;
                    html += `<p> Total TTC : ${total_TTC}€</p>`;
                    html += `<p> montant total du devis : ${total_montant_devis}€</p>`;
                    html += `<p> frais de plateforme HT : ${total_plateforme_HT}€</p>`;
                    html += `<p> frais de plateforme TTC: ${total_plateforme_TTC}€</p>`;
                    document.getElementById("resultat").innerHTML = html;
                }
            </script>
            <input type="button" value="Calculer" onclick="calcul()"/>
            <br/>
    
            <input type="submit" value="Envoyer le devis" />
        </form>
<?php
?>
</body>
</html>


