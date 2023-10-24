<?php
    session_start();
    if(isset($SESSION['erreurs'])){
        $erreurs = $_SESSION['erreurs'];
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header class="row col-12">
        <div class="row col-3">
            <img src="../svg//logo.svg">
            <h2 style="margin-top: auto; margin-bottom: auto; margin-left: 10px;">Loc'Breizh</h2>
        </div>

        <div class="row col-3">
            <img class="col-2" src="../svg//filtre.svg">
            <input class="col-7" id="searchbar" type="text" name="search"
                style="height: 50px; margin-top: auto; margin-bottom: auto;">
            <img class="col-2" src="../svg//loupe.svg">
        </div>
        <div class="row col-3 offset-md-1">
            <img src="../svg//booklet-fill 1.svg">
            <a href="logement.php" style="margin: auto;margin-left: 10px;">
                <h4 style="color:#000;">Accèder à mes réservations</h4>
            </a>
        </div>


        <div class="col-2 row">
            <a class="offset-md-6 row"><img src="../svg/message.svg"></a>
            <a class="offset-md-2 row"><img src="../svg/compte.svg"></a>
        </div>
    </header>
    <main>
        <?php
            // id fictif
            $_SESSION['id'] = 1;
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
            
        ?>
        <style>#erreur {color : red;}</style>
        <h1>La demande de devis de <?php echo $infos_user['prenom'] . ' '. $infos_user['nom']; ?> !</h1>
        <form name="formulaire" action="ajouter_devis.php" method="post">

            <label for="date_arrivee">date d'arrivée:</label>
            <input type="date" id="date_arrivee" name="date_arrivee" value="<?php if(isset($_SESSION['valeurs_complete']['date_arrivee'])){ echo $_SESSION['valeurs_complete']['date_arrivee'];} ?>" required /> 
            <br />

            <label for="date_depart">date de départ:</label>
            <input type="date" id="date_depart" name="date_depart" value=<?php if(isset($_SESSION['valeurs_complete']['date_depart'])){ echo $_SESSION['valeurs_complete']['date_depart'];} ?> required /> 
            <br/>

            <?php
            if (isset($erreurs['cond_annul'])){
                echo '<p id="erreur">' . $erreurs['valide_dates'] . 'aaaaaaaaaaaa' . '</p>';
            }
            ?>

            <label for="nb_pers">nombre de personnes:</label>
            <input type="number" id="nb_pers" name="nb_pers" placeholder="nombre de personnes" value="<?php if(isset($_SESSION['valeurs_complete']['nb_pers'])){echo $_SESSION['valeurs_complete']['nb_pers'];} ?>" required />
            <br/>

            
            <label for="delais_accept">délais d'acceptation ( de 1 à 4 jours ) :</label>
            <input type="number" min="1" max="4" id="delais_accept" name="delais_accept" value="<?php if(isset($_SESSION['valeurs_complete']['delais_accept'])){echo $_SESSION['valeurs_complete']['delais_accept'];} ?>" required />
            <br />

            <label for="date_val">date validité du devis ( en mois) :</label>
            <input type="number" id="date_val" name="date_val" value="<?php if(isset($_SESSION['valeurs_complete']['date_val'])){echo $_SESSION['valeurs_complete']['date_val'];} ?>" required /> 
            <br />

            <label for="annulation">Condition annulation</label>
            <input type="text" id="annulation" name="annulation" value="<?php if(isset($_SESSION['valeurs_complete']['cond_annulation'])){echo $_SESSION['valeurs_complete']['cond_annulation'];} ?>" required/>

            <?php
            if (isset($erreurs['cond_annul'])){
                echo '<p id="erreur">' . $erreurs['cond_annul'] .  '</p>';
            }
            ?>

            <input type="hidden" id="id_demande" name="id_demande" value=<?PHP echo $_GET['demande']; ?>>

            <h1>Charges aditionnelles</h1>

            <input type="checkbox" id="animaux">
            <label for="animaux"> Animaux </label>

            <input type="checkbox" id="menage">
            <label for="menage"> Menage </label>

            <input type="text" id="vacanciers_sup" name="vacanciers_sup" placeholder="vacanciers supplémentaires" />

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
                    let html = "";
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
                    document.getElementById("envoyerDevisBtn").removeAttribute("disabled");
                }
            </script>
            <input type="button" value="Calculer" onclick="calcul()"/>
            <br/>
            <input type="hidden" id="id_demande" name ="id_demande" value=<?php echo $_GET['demande'];?>>
            <input type="submit" id="envoyerDevisBtn" value="Envoyer le devis" disabled />
        </form>
    </main>
    <footer class="container-fluid" >
        <div class="column">   
            <div class="text-center row">
                <p class="testfoot col-2"><a href="mailto:locbreizh@alaizbreizh.com">locbreizh@alaizbreizh.com</a></p>
                <p class="testfoot offset-md-2 col-2"><a href="tel:+33623455689">(+33) 6 23 45 56 89</a></p>
                <p class="testfoot offset-md-1 col-2"><a href="connexion.html"><img src="../svg/instagram.svg">  @LocBreizh</a></p>
                <p class="testfoot offset-md-1 col-2  "><a href="connexion.html"><img src="../svg/facebook.svg">  @LocBreizh</a></p>
            </div>
            <hr>  
            <div class="text-center row">
                <p class="offset-md-1 col-2 testfooter">©2023 Loc’Breizh</p>
                <p class="offset-md-1 col-3 testfooter" style="text-decoration: underline;"><a href="connexion.html">Conditions générales</a></p>
                <p class="offset-md-1 col-4 testfooter" >Développé par <a href="connexion.html" style="text-decoration: underline;">7ème sens</a></p>
            </div>
        </div>
    </footer>
</body>
</html>
