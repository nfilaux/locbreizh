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
$stmt = $dbh->prepare("SELECT photo from locbreizh._compte where id_compte = {$_SESSION['id']};");
$stmt->execute();
$photo = $stmt->fetch();

$plageIndispo = [];
$plageDispo = []; 

?>

<script>
    //recupération des element du html qu'on vas remplir d'information
dateActuelle = [];
baliseJour =[];
precedentSuivant = [];
datesPlage = [];
boutonsDates = [];
prixSejour = [];

//création de dates qui vont êtres utilisé pour le premier et deuxieme calendrier
date = [];
anneeActuelle = [];
moisActuel = [];
date2 = [];
anneeActuelle2 = [];
moisActuel2 = [];

//constante pour les mois de l'année
const tabMois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

//tableaux qui gardent la liste des messages et de plages d'indisponibilitées
var tabDispo = [];
var tabPrix = [];
var classeDispo = [];
var tabIndispo = [];
var tabRaison = [];
var classeIndispo = [];

//tableau des calendriers du code HTML
calendrier = [];

//instanciation du debut et de la fin de la plage
premierID = [];
dernierID = [];

//classe des jours normaux
classeNormale = [];

//prix des plages sélectionner
prixPlage = [];

function instancier(id, nbCache){
    //recupération des element du html qu'on vas remplir d'information
    calendrier[id] = document.getElementById("calendrier" + id);
    dateActuelle[id] = calendrier[id].querySelectorAll(".date_actuelle");
    baliseJour[id] = calendrier[id].querySelectorAll(".jours");
    precedentSuivant[id] = calendrier[id].querySelectorAll(".fleches svg");
    datesPlage[id] = [document.querySelectorAll("#datesPlage .dateresa")[id*2], ''];
    datesPlage[id][1] = document.querySelectorAll("#datesPlage .dateresa")[id*2+1];
    if (nbCache == 4){
        boutonsDates[id] = [document.querySelectorAll(".jesuiscache")[id*4], '', '', ''];
        boutonsDates[id][1] = (document.querySelectorAll(".jesuiscache")[id*4+1]);
        boutonsDates[id][2] = (document.querySelectorAll(".jesuiscache")[id*4+2]);
        boutonsDates[id][3] = (document.querySelectorAll(".jesuiscache")[id*4+3]);
    }
    else{
        boutonsDates[id] = [document.querySelectorAll(".jesuiscache")[id*2], ''];
        boutonsDates[id][1] = (document.querySelectorAll(".jesuiscache")[id*2+1]);
    }
    prixSejour[id] = document.querySelectorAll(".nuit")[id];

    //création de dates qui vont êtres utilisé pour le premier et deuxieme calendrier
    date[id] = new Date();
    anneeActuelle[id] = date[id].getFullYear();
    moisActuel[id] = date[id].getMonth();
    date2[id] = new Date(anneeActuelle[id], moisActuel[id] + 1);
    anneeActuelle2[id] = date2[id].getFullYear();
    moisActuel2[id] = date2[id].getMonth();

    //instanciation du debut et de la fin de la plage
    premierID[id] = "";
    dernierID[id] = "";

    //classe des jours normaux
    classeNormale[id] = "";

    //permet de changer les dates des calendrier et de les actualiser quand on appuie sur les flèches
    precedentSuivant[id].forEach(element => {
        element.addEventListener("click", () => {
            //change les mois en fonction de si on appuie sur precedent ou suivant
            if (element.id === "precedent") {
                moisActuel[id] = moisActuel[id] - 1;
                moisActuel2[id] = moisActuel2[id] - 1;
            }
            else {
                moisActuel[id] = moisActuel[id] + 1;
                moisActuel2[id] = moisActuel2[id] + 1;
            }
            //recrée toutes les dates en fonctions des nouveaux mois et affiche les calendriers actualisés
            date[id] = new Date(anneeActuelle[id], moisActuel[id]);
            anneeActuelle[id] = date[id].getFullYear();
            moisActuel[id] = date[id].getMonth();
            date2[id] = new Date(anneeActuelle2[id], moisActuel2[id]);
            anneeActuelle2[id] = date2[id].getFullYear();
            moisActuel2[id] = date2[id].getMonth();
            afficherCalendrier(classeNormale[id], id);
            if (classeDispo[id]){
                afficherPlages(tabDispo[id], classeDispo[id], tabPrix[id], "D", id);
            }
            if (classeIndispo[id]){
                afficherPlages(tabIndispo[id], classeIndispo[id], tabRaison[id], "I", id);
            }
            if (premierID[id] !== ""){
                selection(premierID[id], dernierID[id], id);
            }
        })
    });
}

//fonction pour actualiser le calendrier en fonction des dates
function afficherCalendrier(classe, id) {
    classeNormale[id] = classe;
    //création des dates importantes du calendrier de gauche
    premierJourMois = new Date(anneeActuelle[id], moisActuel[id], 0).getDay();
    derniereDateMois = new Date(anneeActuelle[id], moisActuel[id] + 1, 0).getDate();
    derniereJourMois = new Date(anneeActuelle[id], moisActuel[id], derniereDateMois - 1).getDay();
    derniereDateMoisAvant = new Date(anneeActuelle[id], moisActuel[id], 0).getDate();
    texteListe = "";

    //création des dates importantes du calendrier de droite
    premierJourMois2 = new Date(anneeActuelle2[id], moisActuel2[id], 0).getDay();
    derniereDateMois2 = new Date(anneeActuelle2[id], moisActuel2[id] + 1, 0).getDate();
    derniereJourMois2 = new Date(anneeActuelle2[id], moisActuel2[id], derniereDateMois2 - 1).getDay();
    derniereDateMoisAvant2 = new Date(anneeActuelle2[id], moisActuel2[id], 0).getDate();
    texteListe2 = "";

    //création de variables qui permettent de créer le calendrier
    nbJours = 0;
    k = 0;

    //création du calendrier de gauche
    for (i = premierJourMois - 1; i >= 0; i--) {
        texteListe += '<li class="inactif">' + (derniereDateMoisAvant - i) + '</li>';
        nbJours++;
    }

    for (i = 1; i <= derniereDateMois; i++) {
        idJour = (moisActuel[id]+ 1) + '/' + (k + 1) + '/' + anneeActuelle[id];
        texteListe += '<li onclick="changerJour(this.id, ' + id + ')" id=' + id + "," + idJour + ' class="' + classe + '">' + i + '</li>';
        k++;
        nbJours++;
    }


    for (i = derniereJourMois; nbJours < 42; i++) {
        texteListe += '<li class="inactif">' + (i - derniereJourMois + 1) + '</li>';
        nbJours++;
    }


    if (dateActuelle[id].length == 2){
        //création du calendrier de droite
        for (i = premierJourMois2 - 1; i >= 0; i--) {
            texteListe2 += '<li class="inactif">' + (derniereDateMoisAvant2 - i) + '</li>';
            nbJours++;
        }

        for (i = 1; i <= derniereDateMois2; i++) {
            idJour = (moisActuel2[id] + 1) + '/' + (k + 1 - derniereDateMois) + '/' + anneeActuelle2[id];
            texteListe2 += '<li onclick="changerJour(this.id, ' + id + ')" id=' + id + "," + idJour + ' class="' + classe + '">' + i + '</li>';
            k++;
            nbJours++;
        }

        for (i = derniereJourMois2; nbJours < 84; i++) {
            texteListe2 += '<li class="inactif">' + (i - derniereJourMois2 + 1) + '</li>';
            nbJours++;
        }
        baliseJour[id][1].innerHTML = texteListe2;
        dateActuelle[id][1].innerHTML = (tabMois[moisActuel2[id]] + ' ' + anneeActuelle2[id]);
    }

    //affiche les mois/années des calendriers
    dateActuelle[id][0].innerHTML = (tabMois[moisActuel[id]] + ' ' + anneeActuelle[id]);
    baliseJour[id][0].innerHTML = texteListe;
}

//permet de créer une selection
function selection(debut, fin, id) {
    changerJour(debut, id);
    if (fin !== debut){
        changerJour(fin, id);
    }
}

//permet de changer les styles des jours en fonction de la selection
function changerJour(elem, id) {
    //cas ou l'id n'ais pas sur le calendrier
    if (document.getElementById(elem) && document.getElementById(elem).className !== "inactif"){
        //recupération de l'élément et réinitialistaion du calendrier
        element = document.getElementById(elem);
        nbActif = calendrier[id].getElementsByClassName("actif").length;
        entreDeux = calendrier[id].getElementsByClassName("entreDeux");
        nbEntreDeux = entreDeux.length;
        for (i = 0; i < nbEntreDeux; i++) {
            entreDeux[0].className = "normal";
        }
        //remet les plages
        if (tabDispo[id][0]){
            afficherPlages(tabDispo[id], classeDispo[id], tabPrix[id], "D", id);
        }
        if (tabIndispo[id][0]){
            afficherPlages(tabIndispo[id], classeIndispo[id], tabRaison[id], "I", id);
        }
        //cas où l'élément n'est pas une date de début ou de fin de palge
        if (element.className !== "actif") {
            //cas ou il n'y as aucune dates de sélectionner
            if (nbActif == 0) {
                premierID[id] = element.id;
                dernierID[id] = element.id;
                element.className = "actif";
            }
            //cas ou il il y a une ou deux dates de sélectionner
            else {
                let dateElem = new Date(element.id.split(',')[1]).getTime();
                let datePremier = new Date(premierID[id].split(',')[1]).getTime();
                let dateDernier = new Date(dernierID[id].split(',')[1]).getTime();
                milieu = (dateDernier + datePremier) / 2;
                //détermine si le nouveau jour seras le début ou la fin de la plage
                if (dateElem < milieu) {
                    if (nbActif > 1) {
                        if (tabDispo[id].includes(premierID[id]) ){
                            document.getElementById(premierID[id]).className = classeDispo[id];
                        }
                        else if(tabIndispo[id].includes(premierID[id])){
                            document.getElementById(premierID[id]).className = classeIndispo[id];
                        }
                        else{
                            document.getElementById(premierID[id]).className = "normal";
                        }
                    }
                    premierID[id] = element.id;
                    element.className = "actif";
                }
                else {
                    if (nbActif > 1) {
                        if (tabDispo[id].includes(dernierID[id]) ){
                            document.getElementById(dernierID[id]).className = classeDispo[id];
                        }
                        else if(tabIndispo[id].includes(dernierID[id])){
                            document.getElementById(dernierID[id]).className = classeIndispo[id];
                        }
                        else{
                            document.getElementById(dernierID[id]).className = "normal";
                        }
                    }
                    dernierID[id] = element.id;
                    element.className = "actif";
                }
                //active la zone de selection entre les deux dates
                datePremier = new Date(premierID[id].split(',')[1]).getTime();
                dateDernier = new Date(dernierID[id].split(',')[1]).getTime();
                listeJours = calendrier[id].querySelectorAll(".jours li");
                inactif = false;
                fini = false;
                loop = 0;
                while (!inactif && !fini){
                    jour = listeJours[loop];
                    if (!jour){
                        fini = true;
                    }
                    if (!fini){
                        let dateJour = new Date(jour.id.split(',')[1]).getTime();
                        if (dateJour < dateDernier && dateJour > datePremier) {
                            if (jour.className !== "inactif"){
                                jour.className = "entreDeux";
                            }
                            else{
                                inactif = true;
                            }
                            
                        }
                        else if (dateJour === dateDernier){
                            fini = true;
                        }
                    }
                    loop++;
                }
                if (inactif){
                    nbEntreDeux = calendrier[id].getElementsByClassName("entreDeux").length;
                    JourAChanger = document.getElementById(dernierID[id]);
                    JourAChanger.className = classeDispo[id];
                    if (nbEntreDeux > 0){
                        dernierID[id] = listeJours[loop-2].id;
                        document.getElementById(dernierID[id]).className = "actif";
                        dernierID[id].className = "actif";
                    }
                    else{
                        dernierID[id] = premierID[id];
                    }
                }
            }
        }
        //désactive le jour si on clique dessus
        else if (element.className === "actif") {
            if (nbActif == 2) {
                if (element.id === premierID[id]) {
                    premierID[id] = dernierID[id];
                }
                else {
                    dernierID[id] = premierID[id];
                }
            }
            if (tabDispo[id].includes(element.id) ){
                element.className = classeDispo[id];
            }
            else if(tabIndispo[id].includes(element.id)){
                element.className = classeIndispo[id];
            }
            else{
                element.className = "normal";
            }
        }
        nbActif = document.getElementsByClassName("actif").length;
        if (nbActif == 0){
            premierID[id] = "";
            dernierID[id] = "";
        }
        changerDates(id);
    }
}

//change les dates se trouvant à coté du calendrier et dans le formulaire pour demander un devis
function changerDates(id) {
    listeActif = calendrier[id].getElementsByClassName("actif");
    listeEntreDeux = calendrier[id].getElementsByClassName("entreDeux");
    pIdDate = premierID[id].split(',')[1];
    dIdDate = dernierID[id].split(',')[1];
    if (listeActif.length !== 0){
        //change format du premier ID
        if (pIdDate.split('/')[1].length == 1) {
            newPId = '0' + pIdDate.split('/')[1];
        }
        else {
            newPId = pIdDate.split('/')[1];
        }
        if (pIdDate.split('/')[0].length == 1) {
            newPId += '/0' + pIdDate.split('/')[0] + '/' + pIdDate.split('/')[2];
        }
        else {
            newPId += '/' + pIdDate.split('/')[0] + '/' + pIdDate.split('/')[2];
        }

        //change format du dernier ID
        if (dIdDate.split('/')[1].length == 1) {
            newDId = '0' + dIdDate.split('/')[1];
        }
        else {
            newDId = dIdDate.split('/')[1];
        }
        if (dIdDate.split('/')[0].length == 1) {
            newDId += '/0' + dIdDate.split('/')[0] + '/' + dIdDate.split('/')[2];
        }
        else {
            newDId += '/' + dIdDate.split('/')[0] + '/' + dIdDate.split('/')[2];
        }
        if (datesPlage[id][0]) {
            //envoie les dates dans le HTML pour l'affichage et pour l'envoie de devis
            datesPlage[id][0].innerHTML = "<p>Arrivée</p><p>" + newPId + "</p>";
            datesPlage[id][1].innerHTML = "<p>Départ</p><p>" + newDId + "</p>";
        }
        if (boutonsDates[id][0]) {
            newPId = newPId.split('/')[2] + "-" + newPId.split('/')[1] + "-" + newPId.split('/')[0];
            newDId = newDId.split('/')[2] + "-" + newDId.split('/')[1] + "-" + newDId.split('/')[0];
            for (i=0; i < boutonsDates[id].length; i+=2){
                boutonsDates[id][i].value = newPId;
                boutonsDates[id][i+1].value = newDId;
            }
        }
        if (prixSejour[id]){
            prixPlage[id] = 0;
            for (i=0; i<listeActif.length-1; i++){
                prixPlage[id] += parseInt(tabPrix[id][tabDispo[id].indexOf(listeActif[i].id.split(',')[1])]);
            }
            for (i=0; i<listeEntreDeux.length; i++){
                prixPlage[id] += parseInt(tabPrix[id][tabDispo[id].indexOf(listeEntreDeux[i].id.split(',')[1])]);
            }
            prixSejour[id].innerHTML = prixPlage[id] + "€ pour les nuits";
        }
    }
    else{
        if (datesPlage[id][0]) {
            //envoie les dates dans le HTML pour l'affichage et pour l'envoie de devis
            datesPlage[id][0].innerHTML = "<p>Arrivée</p><p>" + " ../../.... " + "</p>";
            datesPlage[id][1].innerHTML = "<p>Départ</p><p>" + " ../../.... " + "</p>";
        }
        if (boutonsDates[0]) {
            for (i=0; i < boutonsDates.length; i++){
                boutonsDates[i].value = "";
            }
        }
        if (prixSejour[id]){
            prixSejour[id].innerHTML = "0€ pour les nuits";
        }
    }
}

//fonction qui affiche les plages
function afficherPlages(tabPlage, classe, tabMotif, type, id){
    if (type !== "NI"){
        if (type === "D"){
            tabDispo[id] = tabPlage;
            tabPrix[id] = tabMotif;
            classeDispo[id] = classe;
        }
        else{
            tabIndispo[id] = tabPlage;
            tabRaison[id] = tabMotif;
            classeIndispo[id] = classe;
        }
        for (i=0; i < tabPlage.length; i++){
            if (document.getElementById(id + "," + tabPlage[i]) && document.getElementById(id + "," + tabPlage[i]).className !== "actif") {
                
                document.getElementById(id + "," + tabPlage[i]).className = classe;
                if (classe === "disponible"){
                    document.getElementById(id + "," + tabPlage[i]).title = "prix de la plage : " + tabMotif[i] + "€";
                }
                else if (classe === "indisponible"){
                    document.getElementById(id + "," + tabPlage[i]).title = "motif d'indisponibilité : " + tabMotif[i];
                }
            }
        }
    }
    else{
        tabIndispo[id] = [];
        tabRaison[id] = [];
        classeIndispo[id] = [];
    }
    
}
    numCalendrier = -1;
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page détaillé d'un logement</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
    <script src="plusAvis.js"></script>
</head>

<body>
    <?php 
        include('../header-footer/choose_header.php');
    ?>


    <main>
        <div>
        <?php
                $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                $stmt = $dbh->prepare(
                    "SELECT libelle_logement, nb_personnes_logement, surface_logement, tarif_base_ht, photo_principale, accroche_logement, descriptif_logement
                    from locbreizh._logement 
                    WHERE id_logement = {$_GET['logement']};"
                );

            $stmt->execute();
            $info = $stmt->fetch();
            $stmt = $dbh->prepare(
                "SELECT photo
                from locbreizh._photos_secondaires 
                WHERE logement = {$_GET['logement']}
                ORDER BY numero ASC;"
            );
            $stmt->execute();
            $photos_secondaires = $stmt->fetchAll();
            
            ?>
            <h3 class="logtitre"><?php echo $info['accroche_logement'];?></h3>
                <div class="logrowb">
                    <div class="">
                        <h3 class="policetitre"><?php echo $info['libelle_logement']; ?></h3>
                    </div>
                </div>


                <div class="logrowb">
                    <div class="logcolumn">
                        <div class="slider-container">
                            <div class="slider">
                                <div class="slide">
                                        <img class="photosecondaireP" src="../Ressources/Images/<?php echo $info['photo_principale'];?> ">
                                    </div><?php
                                for ($i = 0 ; $i < 5; $i++) {
                                    if (isset($photos_secondaires[$i]['photo'])){?>
                                        <div class="slide">
                                            <img src="../Ressources/Images/<?php echo $photos_secondaires[$i]['photo'];?>">
                                        </div><?php
                                    }
                                };?>
                            </div>

                            <div class="controls">
                                <button class="left"><img src="../svg/arrow-left.svg"></button>
                                <ul></ul>
                                <button class="right"><img src="../svg/arrow-right.svg"></button>
                            </div>
                        </div>
                    </div>
                    <div class="logcolumn logdem">
                        <h3 class="policetitre">Description</h3>
                        <p class="description-detail"><?php echo $info['descriptif_logement']; ?></p>
                        <?php /*<p>Arrivée echo $info['debut_plage_ponctuelle'] Départ echo $info['fin_plage_ponctuelle'] </p>*/ ?> 
                    </div>
                </div>
        
            <div class="logrowb">
                    <div class="logcolumn">

                        <h3 class="policetitre">Calendrier</h3>
                        <div class="corpsCalendrier" id="">
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


                        <div class="logdem">
                            <div class="logrowb" id="datesPlage">
                                <p class="dateresa"></p>
                                <p class="dateresa"></p>
                            </div>
                            <form action="../demande_devis/demande_devis.php?logement=<?php echo $_GET['logement']; ?>" method="post">
                                <input class="jesuiscache" type='hidden' name="arrive" id="arrive" value="">
                                <input class="jesuiscache" type='hidden' name="depart" id="depart" value="">
                                <button class="btn-demlog" type="submit" >Demander un devis</button>
                                <div class="logrowt">
                                    <p class="nuit"><?php echo $info['tarif_base_ht'];?> €/nuit</p>
                                </div>
                            </form>
                        </div>


                    
                </div>

            <div class="logI">
                <h3 class="policetitres">Informations du logement</h3>
                <?php
                    $stmt = $dbh->prepare(
                        "SELECT 
                        surface_logement,
                        nb_chambre,
                        lit_simple,
                        lit_double,
                        nb_salle_bain,
                        jardin,
                        balcon,
                        terrasse,
                        parking_public,
                        parking_privee,
                        sauna,
                        hammam,
                        piscine,
                        climatisation,
                        jacuzzi,
                        television,
                        wifi,
                        lave_linge,
                        lave_vaisselle,
                        nb_personnes_logement
                        FROM locbreizh._logement
                        where id_logement = {$_GET['logement']}"
                    );
                    $stmt->execute();
                    $info = $stmt->fetch();

                    try {
                        $Reqservices = $dbh->prepare("SELECT nom_service from locbreizh._services_compris where logement = {$_GET['logement']}");
                        $Reqservices->execute();
                        $services = $Reqservices->fetchAll();
                    } catch (PDOException $e) {
                        print "Erreur !:" . $e->getMessage() . "<br/>";
                        die();
                    }
                ?>
                <div class="logrow">
                    <div class="logcp">
                        <h4 class="potitres">Equipements</h4>
                        <p><img src="../svg/tree-fill.svg"> jardin <?php  echo $info['jardin']; ?> m<sup>2</sup></p>
                        <?php
                        if ($info['balcon'] == true) {
                            ?><p><img src="../svg/balcon.svg"><?php  echo 'Balcon'; ?></p><?php
                        }

                        if ($info['terrasse'] == true) {
                            ?><p><img src="../svg/terasse.svg"><?php  echo 'Terrasse'; ?></p><?php
                        }
                        if ($info['parking_privee'] == true) {
                            ?><p><img src="../svg/PARKING.svg"><?php  echo 'Parking privée'; ?></p><?php
                        }

                        if ($info['parking_public'] == true) {
                            ?><p><img src="../svg/PARKING.svg"><?php  echo 'Parking public'; ?></p><?php
                        }
                        if ($info['television'] == true) {
                            ?><p><img src="../svg/TELEVISION.svg"><?php  echo 'Television'; ?></p><?php
                        }
                        if ($info['wifi'] == true) {
                            ?><p><img src="../svg/WIFI.svg"><?php  echo 'Wifi'; ?></p><?php
                        }
                        if ($info['lave_linge'] == true) {
                            ?><p><img src="../svg/contrast-drop-2-fill.svg"><?php  echo 'Lave-linge'; ?></p><?php
                        }
                        if ($info['lave_vaisselle'] == true) {
                            ?><p><img src="../svg/CUISINE.svg"><?php  echo 'Cuisine équipée'; ?></p><?php
                        }
                        
                        ?>
                    </div>
                    <hr class="hr">
                    <div class="logcp">
                        <h4 class="potitres">Installations</h4>
                        <?php

                        if ($info['climatisation'] == true) {
                            ?><p><img src="../svg/windy-line.svg"><?php  echo 'Climatisation'; ?></p><?php
                        }
                        if ($info['piscine'] == true) {
                            ?><p><img src="../svg/PISCINE.svg"> <?php  echo 'Piscine'; ?></p><?php
                        }

                        if ($info['sauna'] == true) {
                            ?><p><img src="../svg/PISCINE.svg"><?php  echo 'Sauna'; ?></p><?php
                        }

                        if ($info['hammam'] == true) {
                            ?><p><img src="../svg/PISCINE.svg"><?php  echo 'Hammam'; ?></p><?php
                        }

                        if ($info['jacuzzi'] == true) {
                            ?><p><img src="../svg/PISCINE.svg"><?php  echo 'Jacuzzi'; ?></p><?php
                        }
                        ?>
                    </div>
                    <hr class="hr">
                    <div class="logcp">
                        <h4 class="potitres">Services</h4>
                        <?php
                        foreach ($services as $key => $value){

                            if ($value['nom_service'] == "navette") {
                                ?><p><img src="../svg/taxi-fill.svg" width="24" height ="24"><?php  echo 'Navette ou Taxi'; ?></p><?php
                            }
                            if ($value['nom_service'] == "menage") {
                                ?><p><img src="../svg/nettoyage.svg" width="24" height="24"> <?php  echo 'Menage'; ?></p><?php
                            }
                            if ($value['nom_service'] == "linge") {
                                ?><p><img src="../svg/t-shirt-air-line.svg" width="24" height ="24"><?php  echo 'Linge'; ?></p><?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <hr class="hr">
                <div class="logrow">
                    <div class="logcp">
                        <p><img src="../svg/CHAMBRE.svg"> <?php  echo $info['lit_simple'] ?> lit(s) simple(s)</p>
                        <p><img src="../svg/CHAMBRE.svg"><?php  echo $info['lit_double'] ?> lit(s) double(s)</p>
                        <p><img src="../svg/ruler.svg" width="24px" height="24px"><?php echo $info['surface_logement'];?>m<sup>2<sup></p>
                    </div>
                    <div class="logcp">
                        <p><img src="../svg/CHAMBRE.svg"><?php  echo $info['nb_chambre'] ?> chambre(s)</p>
                        <p><img src="../svg/SALLE_DE_BAIN.svg"><?php  echo $info['nb_salle_bain'] ?> salle(s) de bain</p>
                        <p><img src="../svg/group.svg" width="24px" height="24px"><?php echo $info['nb_personnes_logement'];?> personnes  </p>
                    </div>
                </div>
        </div>

        <script src="./scriptCalendrier.js"></script>

        <?php
            try {
                $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                $code = $dbh->prepare("SELECT code_planning FROM locbreizh._planning NATURAL JOIN locbreizh._logement WHERE id_logement = {$_GET['logement']};");

                $code->execute();

                $code = $code->fetch()['code_planning'];

                $plageDispo = $dbh->prepare("SELECT prix_plage_ponctuelle, jour_plage_ponctuelle FROM locbreizh._plage_ponctuelle INNER JOIN locbreizh._plage_ponctuelle_disponible
                ON _plage_ponctuelle.id_plage_ponctuelle = _plage_ponctuelle_disponible.id_plage_ponctuelle WHERE code_planning = {$code} ;");
                $plageDispo->execute();
                $plageDispo = $plageDispo->fetchAll();

            } catch (PDOException $e) {
                print "Erreur !:" . $e->getMessage() . "<br/>";
                die();
            }
        ?>

        <script>
            numCalendrier += 1;

            calendrier = document.getElementsByClassName("corpsCalendrier");
            calendrier[numCalendrier].id = "calendrier" + numCalendrier;

            //Appel de la fonction pour créer les calendriers
            instancier(numCalendrier);
            afficherCalendrier("inactif", numCalendrier);

            changerDates(numCalendrier, 2);

            var tabRes = [];
            var tabMotif = [];
            afficherPlages(tabRes, "indisponible", tabMotif, "NI", numCalendrier);

            var tab = <?php echo json_encode($plageDispo); ?>;
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
            afficherPlages(tabRes, "normal", tabMotif, "D", numCalendrier);
            if(document.getElementById(tabRes[0])){
                changerJour(tabRes[0]);
            }
        </script>

        <div>
            <?php
                $stmt = $dbh->prepare(
                    'SELECT nom, prenom,photo, contenu_avis
                                        from locbreizh._avis
                                            INNER JOIN locbreizh._compte ON auteur = id_compte'
                );


            $stmt->execute();

            $stmt = $dbh->prepare(
                "SELECT photo from locbreizh._compte c
                join locbreizh._logement l on l.id_proprietaire = c.id_compte
                where l.id_logement = {$_GET['logement']};"
            );
            $photo_proprio = $stmt->fetch();
            //$info = $stmt->fetch();
            foreach ($stmt->fetchAll() as $info) {
                echo '<img src="/Ressources/Images/compte.svg>';
                echo '<h4>' . $info['nom'] . ' ' . $info['prenom'] . '</h4>';
                echo '<img src="/Ressources/Images/star-fill 2.svg">' . '<h4>' .  $info['note_avis'] . ',0</p>';
                echo '<p>' . $info['contenu_avis'] . '</p>';
            }

            ?>
           
            
        </div>

        <hr class="hr">
        <div class="logcarte">
            <h3 class="policetitre">Localisation</h3>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1364671.57561899!2d-4.397375693978974!3d48.08372166501683!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4811ca61ae7e8eaf%3A0x10ca5cd36df24b0!2sBretagne!5e0!3m2!1sfr!2sfr!4v1702909132704!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php
                $stmt = $dbh->prepare(
                    'SELECT ville, nom_rue, numero_rue
                    from locbreizh._logement
                    natural JOIN locbreizh._adresse
                    where id_logement = :id'
                );
                $stmt->bindParam(':id', $_GET['logement']);


            $stmt->execute();
            $info = $stmt->fetch();
            ?>
            <p><?php echo 'Adresse : ' . $info['numero_rue'] . ' ' . $info['nom_rue'] . ' ' . $info['ville'] ?></p>   
            
        </div>
        <hr class="hr">
        <!--Les avis-->
        <?php 
            
            $stmt = $dbh->prepare('SELECT moyenne_avis
            from locbreizh._logement
            where id_logement = :logement;');
            $stmt->bindParam(':logement', $_GET['logement']);
            $stmt->execute();
            $moyenne = $stmt->fetch();

            $stmt = $dbh->prepare('SELECT contenu_avis, note_avis, nom, prenom, photo
            from locbreizh._avis a
            join locbreizh._compte c on a.auteur = c.id_compte
            where a.logement = :logement
            ORDER BY a.id_avis DESC;');
            $stmt->bindParam(':logement', $_GET['logement']);
            $stmt->execute();
            $avis = $stmt->fetchAll();

            $stmt = $dbh->prepare('SELECT client
            from locbreizh._reservation
            where client = :idC and logement = :logement;');
            $stmt->bindParam(':idC', $_SESSION['id']);
            $stmt->bindParam(':logement', $_GET['logement']);
            $stmt->execute();
            $peutA = $stmt->fetch();

            $form = false;
            if(isset($peutA['client'])){
                $form = true;
            }?>
            <div class="titreAvis">
                <h3 class="h3_avis">Avis</h3>
                <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="note_moyenne">
                <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg></label>
                <p class="sousTitreAvis"><?php echo $moyenne['moyenne_avis'];?> ⏺ <?php echo count($avis); ?> avis</p>
            </div>

            <?php
            if($form){ ?>
            <div class="rediger-avis">
                <p>Vous aussi donnez votre avis sur ce logement !</p>
                <form class="messageBox" action="envoyer_avis.php" method="post" id="avis_box">
                    <input type="hidden" id="logement_avis" name="logement" value="<?php echo $_GET['logement'];?>">
                    <div class="rating">
                        <input type="radio" id="star5" name="rate" value="5" />
                        <label for="star5" title="text">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg></label>
                        <input type="radio" id="star4" name="rate" value="4" />
                        <label for="star4" title="text">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg></label>
                        <input checked="" type="radio" id="star3" name="rate" value="3" />
                        <label for="star3" title="text">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg></label>
                        <input type="radio" id="star2" name="rate" value="2" />
                        <label for="star2" title="text">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg></label>
                        <input type="radio" id="star1" name="rate" value="1" />
                        <label for="star1" title="text">
                        <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid">
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg></label>
                    </div>

                    <textarea maxlength="499" placeholder="Rediger votre avis..." type="text" id="messageInput" name="contenu"></textarea>
                    <button id="sendButton">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 664 663">
                        <path fill="none" d="M646.293 331.888L17.7538 17.6187L155.245 331.888M646.293 331.888L17.753 646.157L155.245 331.888M646.293 331.888L318.735 330.228L155.245 331.888"></path>
                        <path stroke-linejoin="round" stroke-linecap="round" stroke-width="33.67" stroke="#6c6c6c" d="M646.293 331.888L17.7538 17.6187L155.245 331.888M646.293 331.888L17.753 646.157L155.245 331.888M646.293 331.888L318.735 330.228L155.245 331.888"></path>
                        </svg>
                    </button>

                </form>
            </div>
            <?php } ?>

            <div class="all-avis">

            <?php
            $nb_avis = 0;
            foreach($avis as $avi){
                $nb_avis++;
                ?>
                <div class="box-avis <?php if($nb_avis > 4){echo 'hidden';}?>">
                    <div class="avis-box-space-between">
                        <div class="header-box infoC">
                            <img src="../Ressources/Images/<?php echo $avi['photo'];?>" alt="Image de profil" title="Photo">
                            <div>
                                <p><?php echo $avi['prenom'] . ' ' . $avi['nom'];?></p>
                                <hr>
                            </div>
                        </div>
                        <div class="header-box">
                            <svg viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg" class="star-solid" fill="#ffa723">
                            <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg>
                            <p><?php echo $avi['note_avis'];?>/5</p>
                        </div>
                    </div>
                    <p><?php echo $avi['contenu_avis'];?></p>
                    <div class="avis-box-space-between">
                        <a href="">Répondre au commentaire</a>
                        <a href="">Signaler</a>
                    </div>
                </div>
            <?php } ?>
            </div>
            <?php
            
            if($nb_avis > 4){?>
                <div class="div_plus_avis"><button id="afficher-plus-avis">Afficher tous les avis (<?php echo count($avis) - 4;?>)</button></div>
            <?php } ?>
    </main>
    
    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>
</html>

<script src="caroussel.js" defer></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function() {
    // Fonction pour ajuster la hauteur de la zone de texte et du formulaire en fonction du contenu
    function ajusterHauteurTextarea() {
        var textarea = $(this);
        textarea.css('height', 'auto');
        textarea.css('height', (this.scrollHeight) + 'px');

        // Ajuster la hauteur du formulaire en fonction de la hauteur de la zone de texte
        var formulaire = textarea.closest('form');
        formulaire.css('height', 'auto');
        formulaire.css('height', formulaire.prop('scrollHeight') + 'px');
    }

    // Attacher la fonction ajusterHauteurTextarea à l'événement input
    $('#messageInput').on('input', ajusterHauteurTextarea);
});
</script>