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
    //recupération des element du html qu'on vas remplir d'information
var dateActuelle = [];
var baliseJour =[];
var precedentSuivant = [];
var datesPlage = [];
var boutonsDates = [];
var prixSejour = [];

//création de dates qui vont êtres utilisé pour le premier et deuxieme calendrier
var date = [];
var anneeActuelle = [];
var moisActuel = [];
var date2 = [];
var anneeActuelle2 = [];
var moisActuel2 = [];

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
var calendrier = [];

//instanciation du debut et de la fin de la plage
var premierID = [];
var dernierID = [];

//classe des jours normaux
var classeNormale = [];

//prix des plages sélectionner
var prixPlage = [];

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
        texteListe += '<li class="inactif">' + (derniereDateMoisAvant - i) + '<p style="margin-top : 3px;" class="prixPlanning">&zwnj;</p></li>';
        nbJours++;
    }

    for (i = 1; i <= derniereDateMois; i++) {
        idJour = (moisActuel[id]+ 1) + '/' + (k + 1) + '/' + anneeActuelle[id];
        texteListe += '<li onclick="changerJour(this.id, ' + id + ')" id=' + id + "," + idJour + ' class="' + classe + '">' + i + '<p style="margin-top : 3px;" class="prixPlanning">&zwnj;</p></li>';
        k++;
        nbJours++;
    }


    for (i = derniereJourMois; nbJours < 42; i++) {
        texteListe += '<li class="inactif">' + (i - derniereJourMois + 1) + '<p style="margin-top : 3px;" class="prixPlanning">&zwnj;</p></li>';
        nbJours++;
    }


    if (dateActuelle[id].length == 2){
        //création du calendrier de droite
        for (i = premierJourMois2 - 1; i >= 0; i--) {
            texteListe2 += '<li class="inactif">' + (derniereDateMoisAvant2 - i) + '<p style="margin-top : 3px;" class="prixPlanning">&zwnj;</p></li>';
            nbJours++;
        }

        for (i = 1; i <= derniereDateMois2; i++) {
            idJour = (moisActuel2[id] + 1) + '/' + (k + 1 - derniereDateMois) + '/' + anneeActuelle2[id];
            texteListe2 += '<li onclick="changerJour(this.id, ' + id + ')" id=' + id + "," + idJour + ' class="' + classe + '">' + i + '<p style="margin-top : 3px;" class="prixPlanning">&zwnj;</p></li>';
            k++;
            nbJours++;
        }

        for (i = derniereJourMois2; nbJours < 84; i++) {
            texteListe2 += '<li class="inactif">' + (i - derniereJourMois2 + 1) + '<p style="margin-top : 3px;" class="prixPlanning">&zwnj;</p></li>';
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
            entreDeux[0].className = "indisponible";
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
            if (premierID[id] == '' && dernierID[id] == '') {
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
                    if (premierID[id] !== dernierID[id]) {
                        if (tabDispo[id].includes(premierID[id]) ){
                            document.getElementById(premierID[id]).className = classeDispo[id];
                        }
                        else if(tabIndispo[id].includes(premierID[id])){
                            document.getElementById(premierID[id]).className = classeIndispo[id];
                        }
                        else{
                            if (document.getElementById(premierID[id])){
                                document.getElementById(premierID[id]).className = "indisponible";
                            }
                        }
                    }
                    premierID[id] = element.id;
                    element.className = "actif";
                }
                else {
                    if (premierID[id] !== dernierID[id]) {
                        if (tabDispo[id].includes(dernierID[id]) ){
                            document.getElementById(dernierID[id]).className = classeDispo[id];
                        }
                        else if(tabIndispo[id].includes(dernierID[id])){
                            document.getElementById(dernierID[id]).className = classeIndispo[id];
                        }
                        else{
                            if (document.getElementById(dernierID[id])){
                                document.getElementById(dernierID[id]).className = "indisponible";
                            }
                        }
                    }
                    dernierID[id] = element.id;
                    element.className = "actif";
                }
                //remet les plages
                if (tabDispo[id][0]){
                    afficherPlages(tabDispo[id], classeDispo[id], tabPrix[id], "D", id);
                }
                if (tabIndispo[id][0]){
                    afficherPlages(tabIndispo[id], classeIndispo[id], tabRaison[id], "I", id);
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
                if (dernierID[id] != premierID[id]){
                    openPopup('selection' + id, 'overlayCal' + id);
                }
            }
        }
        //désactive le jour si on clique dessus
        else if (element.className === "actif") {
            if (premierID[id] !== dernierID[id]) {
                if (element.id === premierID[id]) {
                    premierID[id] = dernierID[id];
                }
                else {
                    dernierID[id] = premierID[id];
                }
            }
            else{
                premierID[id] = "";
                dernierID[id] = "";
            }
            if (tabDispo[id].includes(element.id) ){
                element.className = classeDispo[id];
            }
            else if(tabIndispo[id].includes(element.id)){
                element.className = classeIndispo[id];
            }
            else{
                element.className = "indisponible";
            }
            //remet les plages
            if (tabDispo[id][0]){
                afficherPlages(tabDispo[id], classeDispo[id], tabPrix[id], "D", id);
            }
            if (tabIndispo[id][0]){
                afficherPlages(tabIndispo[id], classeIndispo[id], tabRaison[id], "I", id);
            }
        }
        changerDates(id);
    }
    else if (premierID[id] !== ""){
        console.log(premierID[id]);
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
            for (i=0; i<listeActif.length; i++){
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
                if (tabMotif[i][0] == "Réservation"){
                    document.getElementById(id + "," + tabPlage[i]).className = "reserver";
                }
                else if (tabMotif[i][0] == "Demande devis"){
                    document.getElementById(id + "," + tabPlage[i]).className = "devis";
                }
                else{
                    document.getElementById(id + "," + tabPlage[i]).className = classe;
                }
                if (classe == "disponible"){
                    document.getElementById(id + "," + tabPlage[i]).innerHTML = tabPlage[i].split("/")[1] + '<p class="prixPlanning">' + tabMotif[i] + "€</p>";
                }
                else if (classe == "indisponible"){
                    if (tabMotif[i][1]){
                        document.getElementById(id + "," + tabPlage[i]).innerHTML = tabPlage[i].split("/")[1] + '<p class="prixPlanning">' + tabMotif[i][1] + "€</p>";
                    }
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

function deselectionner(popup, overlay, id){
    selection(premierID[id], dernierID[id], id);
    dernierID[id] = "";
    premierID[id] = "";
    closePopup(popup, overlay);
}

numCalendrier = -1;
</script>


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
        if(isset($_GET["cs"])){
            $cas_popup = $_GET["cs"];
        }
        else{
            $cas_popup = '';
        }

        if(isset($_GET["idlog"])){
            $logsuppr = $_GET["idlog"];
            $stmt = $dbh->prepare("SELECT libelle_logement from locbreizh._logement where id_logement = $logsuppr;");
            $stmt->execute();
            $nomlogsuppr = $stmt->fetchColumn();
        }
        else{
            $logsuppr = '';
            $nomlogsuppr = '';
        }
    ?>

    <main class="MainTablo">
        <div class="headtabloP"> 
            <h1>Mes Logements</h1>
        </div>
        <section class="Tablobord">
                <div class="colreverse">
                <?php
                    
                    $stmt = $dbh->prepare(
                        "SELECT * from locbreizh._logement where id_proprietaire = {$_SESSION['id']} ORDER BY id_logement ASC;"
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
                $infos_log = [];
                foreach ($liste_mes_logements as $key => $card) {
                    $infos_log[$card['id_logement']] = $card;
                }
                foreach ($liste_mes_logements as $key => $card) {
                    $id_log = $card['id_logement'];

                    
                    $nomPlage = 'plage' . $key; 
                    $overlayPlage = 'overlay' . $key;
                    $overlayCal = 'overlayCal' . $key;
                    $nomSelection = 'selection' . $key;
                    
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
                                    <form id="enligne<?php echo $id_un_logement ?>" action="ChangeEtat.php" method="post">
                                    <?php
                                    if ($infos_log[$id_log]["en_ligne"] == 1){
                                        $bouton_desactiver = "METTRE HORS LIGNE";?>
                                        <input type="hidden" name=<?php echo $id_un_logement ?> value="<?php echo htmlentities($bouton_desactiver) ?>">
                                        <button style="margin-top : 15px; margin-right : 10px; margin-left: 10px;" class="btn-desactive" type='submit'> <?php echo $bouton_desactiver; ?> </button> <?php
                                    } else{
                                        $bouton_desactiver = "METTRE EN LIGNE";?>
                                        <input type="hidden" name=<?php echo $id_un_logement ?> value="<?php echo htmlentities($bouton_desactiver) ?>">
                                        <button style="margin-top : 15px; margin-right : 10px; margin-left: 10px;" class="btn-active" type='submit'> <?php echo $bouton_desactiver; ?> </button> <?php
                                    }
                                    ?>
                                        
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
                                        <p> Etes vous bien sûr de vouloir supprimer votre logement : <?php echo $nomlogsuppr ?> ? 
                                        <p class="erreur">Cette action est irreversible !</p> 
                                        <div id='boutons'>
                                            <button onclick="closePopup('validation','overlay_validation')" class="btn-ajoutlog">Annuler</button> 
                                            <a href="../Logement/supprimer_logement.php?idc=<?php echo $logsuppr ?>" ><button class="btn-suppr">Supprimer</button></a>
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
                                    <div class="overlay_plages" id='<?php echo $overlayCal; ?>' onclick="deselectionner('<?php echo $nomSelection; ?>', '<?php echo $overlayCal; ?>', '<?php echo $key; ?>')"></div>
                                    <div id="<?php echo $nomSelection; ?>" class='ajoutSelection'>
                                        <div class="formulaire_selection">
                                            <form action="../Planning/plageBack.php" method="post">
                                                
                                                <?php erreur("plage") ?>
                                                <input class="jesuiscache" type='hidden' name="debut_plage_ponctuelle" id="debut_plage_ponctuelle" value="" required>
                                                <input class="jesuiscache" type='hidden' name="fin_plage_ponctuelle" id="fin_plage_ponctuelle" value="" required>

                                                <label for="prix_plage_ponctuelle"> Prix : </label>
                                                <input type="text" id="prix_plage_ponctuelle" name="prix" placeholder="<?php echo $card['tarif_base_ht'] ?>" value="<?php echo $card['tarif_base_ht'] ?>" required/>
                                                <br><?php erreur("prix") ?><br>

                                                <input type="hidden" name="id_logement" value="<?php echo $card['id_logement'] ?>"/>

                                                <input type="hidden" name="overlayPopUp" value="<?php echo $overlayPlage ?>"/>
                                                <input type="hidden" name="nomPopUp" value="<?php echo $nomPlage ?>"/>

                            
                                                <button type="submit" class="btn-ajoutlog">Ajouter plage</button>
                                            </form>

                                            <form class="formSupprPlage" action="../Planning/supprimerPlage.php" method="post">
                                                
                                                <input class="jesuiscache" type='hidden' name="debut_plage_suppr" id="debut_plage_suppr" value="" required>
                                                <input class="jesuiscache" type='hidden' name="fin_plage_suppr" id="fin_plage_suppr" value="" required>
                                                
                                                <input type="hidden" name="id_logement" value="<?php echo $card['id_logement'] ?>"/>

                                                <input type="hidden" name="overlayPopUp" value="<?php echo $overlayPlage ?>"/>
                                                <input type="hidden" name="nomPopUp" value="<?php echo $nomPlage ?>"/>

                                                <button type="submit" class="btn-suppr">Supprimer plage</button>
                                            </form>
                                        </div>
                                    </div>
                                    <h1>Ajouter une plage ponctuelle</h1><br>
                                    <div class="logcolumn">
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

                                    <h1>Légende</h1>
                                    <div class="legendeCalendrier">
                                        <p class="legendeLibre">Libre</p>
                                        <p class="legendeDemande">Demande de devis</p>
                                        <p class="legendeRéserver">Réservé</p>
                                        <p class="legendeIndisponible">Indisponible</p>
                                    </div>

                                    <?php
                                    try {
                                        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
                                        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                                        $code = $dbh->prepare("SELECT code_planning FROM locbreizh._planning NATURAL JOIN locbreizh._logement WHERE id_logement = {$card['id_logement']};");

                                        $code->execute();

                                        $code = $code->fetch()['code_planning'];

                                        $lesPlages = $dbh->prepare("SELECT id_plage_ponctuelle, jour_plage_ponctuelle FROM locbreizh._plage_ponctuelle WHERE code_planning = {$code} ;");
                                        
                                        $lesPlages->execute();

                                        $plageIndispo = $dbh->prepare("SELECT libelle_indisponibilite, jour_plage_ponctuelle, prix_plage_ponctuelle FROM locbreizh._plage_ponctuelle INNER JOIN locbreizh._plage_ponctuelle_indisponible
                                        ON _plage_ponctuelle.id_plage_ponctuelle = _plage_ponctuelle_indisponible.id_plage_ponctuelle WHERE code_planning = {$code} ;");
                                        $plageIndispo->execute();
                                        $plageIndispo = $plageIndispo->fetchAll();

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
                                    instancier(numCalendrier, 4);
                                    afficherCalendrier("indisponible", numCalendrier);

                                    var tab = <?php echo json_encode($plageIndispo); ?>;
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
                                        tabMotif[i] = [tab[i]["libelle_indisponibilite"], ''];
                                        tabMotif[i][1] = tab[i]["prix_plage_ponctuelle"];
                                    }
                                    afficherPlages(tabRes, "indisponible", tabMotif, "I", numCalendrier);

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
                                    afficherPlages(tabRes, "disponible", tabMotif, "D", numCalendrier);

                                    if (!tabRes[0]){
                                        document.querySelector("#enligne<?php echo json_encode($id_un_logement); ?> .btn-active").disabled = true;
                                        document.querySelector("#enligne<?php echo json_encode($id_un_logement); ?> .btn-active").className = "btn-desactiveGris";
                                    }
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