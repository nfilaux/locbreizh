<?php
session_start();
//print_r($_POST);
$_SESSION["erreurs"] = [];
$_SESSION["valeurs_complete"] = $_POST;

if ($_POST['nb_chambresP'] > 0 ){
    if ($_POST['nb_lit_simpleP'] <= 0 && $_POST['nb_lit_doubleP'] <= 0){
        $_SESSION["erreurs"]["chambre"] = "Une chambre doit posséder au minimum un lit !";
        header("Location: http://localhost:8888/Logement/remplir_formulaire.php");
    }
}

include('../parametre_connexion.php');
try {
    $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
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


$nom_image_principale = $_FILES["image1P"]["tmp_name"];
$nom_image2 = $_FILES["image2P"]["tmp_name"];
$nom_image3 = $_FILES["image3P"]["tmp_name"];
$nom_image4 = $_FILES["image4P"]["tmp_name"];
$nom_image5 = $_FILES["image5P"]["tmp_name"];
$nom_image6 = $_FILES["image6P"]["tmp_name"];

$id_photo = 1;

function id_photo($id_photo)
{
    return time() . $id_photo;
}
$extension_img_1 = explode('/',$_FILES['image1P']['type'])[1];
$nouveau_nom_image1 = id_photo($id_photo) . '.' . $extension_img_1;
$id_photo++;
move_uploaded_file($nom_image_principale, "../Ressources/Images/" . $nouveau_nom_image1);

if ($_FILES['image2P']['name']!= '') {
    $extension_img_2 = explode('/',$_FILES['image2P']['type'])[1];
    $nouveau_nom_image2 = id_photo($id_photo) . '.' . $extension_img_2;
    $id_photo++;
    move_uploaded_file($nom_image2, "../Ressources/Images/" . $nouveau_nom_image2);
} else {
    $nom_image2 = null;
    $nouveau_nom_image2 = null;
}

if ($_FILES['image3P']['name']!= '') {
    $extension_img_3 = explode('/',$_FILES['image3P']['type'])[1];
    $nouveau_nom_image3 = id_photo($id_photo) . '.' . $extension_img_3;
    $id_photo++;
    move_uploaded_file($nom_image3, "../Ressources/Images/" . $nouveau_nom_image3);
} else {
    $nom_image3 = null;
    $nouveau_nom_image3 = null;
}

if ($_FILES['image4P']['name']!= '') {
    $extension_img_4 = explode('/',$_FILES['image4P']['type'])[1];
    $nouveau_nom_image4 = id_photo($id_photo) . '.' . $extension_img_4 ;
    $id_photo++;
    move_uploaded_file($nom_image4, "../Ressources/Images/" . $nouveau_nom_image4);
} else {
    $nom_image4 = null;
    $nouveau_nom_image4 = null;
}

if ($_FILES['image5P']['name']!= '') {
    $extension_img_5 = explode('/',$_FILES['image5P']['type'])[1];
    $nouveau_nom_image5 = id_photo($id_photo) . '.' . $extension_img_5 ;
    $id_photo++;
    move_uploaded_file($nom_image5, "../Ressources/Images/" . $nouveau_nom_image5);
} else {
    $nom_image5 = null;
    $nouveau_nom_image5 = null;
}

if ($_FILES['image6P']['name']!= '') {
    $extension_img_6 = explode('/',$_FILES['image6P']['type'])[1];
    $nouveau_nom_image6 = id_photo($id_photo) . '.' . $extension_img_6;
    $id_photo++;
    move_uploaded_file($nom_image6, "../Ressources/Images/" . $nouveau_nom_image6);
} else {
    $nom_image6 = null;
    $nouveau_nom_image6 = null;
}

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
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévisualisez un logement</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../scriptPopup.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>


<body>
    <?php 
        include('../header-footer/choose_header.php');
    ?>

    <main class="MainTableau">
        <?php
        unset($_SESSION['post_logement']);

        $_SESSION['post_logement'] = $_POST;

        $_SESSION['post_logement']['image1P'] = $nouveau_nom_image1;
        $cpt = 1;
        if ($_FILES["image2P"]["name"] != "") {
            $_SESSION['post_logement']['image2P'] = $nouveau_nom_image2;
            $cpt++;
        }
        if ($_FILES["image3P"]["name"] != "") {
            $_SESSION['post_logement']['image3P'] = $nouveau_nom_image3;
            $cpt++;
        }
        if ($_FILES["image4P"]["name"] != "") {
            $_SESSION['post_logement']['image4P'] = $nouveau_nom_image4;
            $cpt++;
        }
        if ($_FILES["image5P"]["name"] != "") {
            $_SESSION['post_logement']['image5P'] = $nouveau_nom_image5;
            $cpt++;
        }
        if ($_FILES["image6P"]["name"] != "") {
            $_SESSION['post_logement']['image6P'] = $nouveau_nom_image6;
            $cpt++;
        }

        $nom = $_POST['nomP'];
        $ville = $_POST['villeP'];
        $code_postal = $_POST['code_postalP'];
        $tarif_de_base = $_POST['tarif_de_baseP'];
        $accroche = $_POST['accrocheP'];
        $description = $_POST['descriptionP'];
        $nature = $_POST['natureP'];
        $type = $_POST['typeP'];
        $nb_chambres = $_POST['nb_chambresP'];
        $nb_lit_simple = $_POST['nb_lit_simpleP'];
        $nb_lit_double = $_POST['nb_lit_doubleP'];
        $nb_sdb = $_POST['nb_sdbP'];
        $surface_maison = $_POST['surface_maisonP'];
        $nb_personne_max = $_POST['nb_personne_maxP'];
        $surface_jardin = $_POST['surface_jardinP'];
        $taxe_sejour = $_POST['taxe_sejourP'];

        $en_ligne = true;
        $id_proprietaire = $_SESSION['id'];
        $charges1 = $_POST['charges1P'];
        $charges2 = $_POST['charges2P'];
        $charges3 = $_POST['charges3P'];
        $nom_image_principale = $_FILES["image1P"]["name"];
        $nom_image2 = $_FILES["image2P"]["name"];
        $nom_image3 = $_FILES["image3P"]["name"];
        $nom_image4 = $_FILES["image4P"]["name"];
        $nom_image5 = $_FILES["image5P"]["name"];
        $nom_image6 = $_FILES["image6P"]["name"];

        if (isset($_POST['balconP'])) {
            $balcon = 1;
            $_SESSION['post_logement']['balconP'] = 1;
        } else {
            $balcon = 0;
        }

        if (isset($_POST['terrasseP'])) {
            $terrasse = 1;
            $_SESSION['post_logement']['terrasseP'] = 1;
        } else {
            $terrasse = 0;
        }

        if (isset($_POST['parking_publicP'])) {
            $parking_public = 1;
            $_SESSION['post_logement']['parking_publicP'] = 1;
        } else {
            $parking_public = 0;
        }

        if (isset($_POST['parking_priveP'])) {
            $parking_privee = 1;
            $_SESSION['post_logement']['parking_priveP'] = 1;
        } else {
            $parking_privee = 0;
        }

        if (isset($_POST['saunaP'])) {
            $sauna = 1;
            $_SESSION['post_logement']['saunaP'] = 1;
        } else {
            $sauna = 0;
        }

        if (isset($_POST['hammamP'])) {
            $hammam = 1;
            $_SESSION['post_logement']['hammamP'] = 1;
        } else {
            $hammam = 0;
        }

        if (isset($_POST['piscineP'])) {
            $piscine = 1;
            $_SESSION['post_logement']['piscineP'] = 1;
        } else {
            $piscine = 0;
        }

        if (isset($_POST['climatisationP'])) {
            $climatisation = 1;
            $_SESSION['post_logement']['climatisationP'] = 1;
        } else {
            $climatisation = 0;
        }

        if (isset($_POST['jacuzziP'])) {
            $jacuzzi = 1;
            $_SESSION['post_logement']['jacuzziP'] = 1;
        } else {
            $jacuzzi = 0;
        }

        if (isset($_POST['televisionP'])) {
            $television = 1;
            $_SESSION['post_logement']['televisionP'] = 1;
        } else {
            $television = 0;
        }

        if (isset($_POST['wifiP'])) {
            $wifi = 1;
            $_SESSION['post_logement']['wifiP'] = 1;
        } else {
            $wifi = 0;
        }

        if (isset($_POST['lave_vaisselleP'])) {
            $lave_vaisselle = 1;
            $_SESSION['post_logement']['lave_vaisselleP'] = 1;
        } else {
            $lave_vaisselle = 0;
        }

        if (isset($_POST['lave_lingeP'])) {
            $lave_linge = 1;
            $_SESSION['post_logement']['lave_lingeP'] = 1;
        } else {
            $lave_linge = 0;
        }

        if (isset($_POST['menage'])) {
            $menage = 1;
            $_SESSION['post_logement']['menageP'] = 1;
        } else {
            $menage = 0;
        }

        if (isset($_POST['navette'])) {
            $navette = 1;
            $_SESSION['post_logement']['navetteP'] = 1;
        } else {
            $navette = 0;
        }

        if (isset($_POST['linge'])) {
            $linge = 1;
            $_SESSION['post_logement']['lingeP'] = 1;
        } else {
            $linge = 0;
        }

        $_SESSION['logement_data'] = [
            'nom' => $nom,
            'ville' => $ville,
            'code_postal' => $code_postal,
            'prix_journalier_adulte' => $taxe_sejour,
            'tarif_de_base' => $tarif_de_base,
            'accroche' => $accroche,
            'description' => $description,
            'nature' => $nature,
            'type' => $type,
            'nb_chambres' => $nb_chambres,
            'nb_lit_simple' => $nb_lit_simple,
            'nb_lit_double' => $nb_lit_double,
            'nb_sdb' => $nb_sdb,
            'surface_maison' => $surface_maison,
            'nb_personne_max' => $nb_personne_max,
            'surface_jardin' => $surface_jardin,
            'taxe_sejour' => $taxe_sejour,
            'balcon' => $balcon,
            'terrasse' => $terrasse,
            'parking_public' => $parking_public,
            'parking_privee' => $parking_privee,
            'sauna' => $sauna,
            'hammam' => $hammam,
            'piscine' => $piscine,
            'climatisation' => $climatisation,
            'jacuzzi' => $jacuzzi,
            'television' => $television,
            'wifi' => $wifi,
            'lave_vaisselle' => $lave_vaisselle,
            'lave_linge' => $lave_linge,
            'menage' => $menage,
            'navette' => $navette,
            'linge' => $linge,
            'charges1' => $charges1,
            'charges2' => $charges2,
            'charges3' => $charges3,
            'image1' => $nouveau_nom_image1,
            'image2' => $nouveau_nom_image2,
            'image3' => $nouveau_nom_image3,
            'image4' => $nouveau_nom_image4,
            'image5' => $nouveau_nom_image5,
            'image6' => $nouveau_nom_image6,
        ];

        if (isset($_SESSION['logement_data'])) {
            $logement_data = $_SESSION['logement_data'];


?>
        <div class="headtabloP"> 
            <h1 class="policetitre"> Prévisualisation des données du logement </h1>
        </div>

        <div class="logpc">
                        <h3 class="logtitre" style="margin-left:1em;"><?php echo $logement_data['accroche'];;?></h3>
                        <div class="logrowb" >
                            <div class="logrowt">
                                <h3 class="policetitre" style="margin-left:1em;"><?php echo $logement_data['nom']; ?></h3>
                            </div>
                        </div>


                        <div class="logrowb">
                            <div class="logcolumn">
                                <div class="slider-container">
                                    <div class="slider">
                                        <?php
                                        for ($i = 1; $i <= 6; $i++) {
                                            if (isset($_SESSION['post_logement']['image' .$i . 'P'] )){?>
                                                <div class="slide">
                                                    <img src="../Ressources/Images/<?php echo $_SESSION['post_logement']['image' . $i . 'P'] ;?>" alt="Image d'un logement">
                                                </div><?php
                                            }
                                        };?>
                                    </div>

                                    <div class="controls">
                                        <button class="left"><img src="../svg/arrow-left.svg" alt="Flèche de gauche"></button>
                                        <ul></ul>
                                        <button class="right"><img src="../svg/arrow-right.svg" alt="Flèche de droite"></button>
                                    </div>
                                </div>
                            </div>
                            <div class="logcolumn logdem">
                                <h3 class="policetitre">Description</h3>
                                <p class="description-detail"><?php echo $logement_data['description']; ?></p>
                                <?php /*<p>Arrivée echo $info['debut_plage_ponctuelle'] Départ echo $info['fin_plage_ponctuelle'] </p>*/ ?> 
                            </div>
                        </div>


                        <div class="logrowb">
                    <div class="logcolumn" >
                        <h3 class="policetitres">Calendrier</h3>
                        <div class="corpsCalendrier" id="">
                            <div class="fondP">
                                <div class="teteCalendrier">
                                    <div class="fleches flechesP">
                                        <svg id="precedent" xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14">
                                            <path fill="#274065" d="m2.828 7 4.95 4.95-1.414 1.415L0 7 6.364.637 7.778 2.05 2.828 7Z"/>
                                        </svg>
                                    </div>
                                    <p class="date_actuelle date_actuelleP"></p>
                                </div>
                                <div class="calendrier">
                                    <ul class="semaines semainesP">
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
                            <div class="fondP">
                                <div class="teteCalendrier">
                                    <p class="date_actuelle date_actuelleP"></p>
                                    <div class="fleches flechesP">
                                        <svg id="suivant" xmlns="http://www.w3.org/2000/svg" width="8" height="14" viewBox="0 0 8 14">
                                            <path fill="#274065" d="m2.828 7 4.95 4.95-1.414 1.415L0 7 6.364.637 7.778 2.05 2.828 7Z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="calendrier">
                                    <ul class="semaines semainesP">
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

                <script src="./scriptCalendrier.js"></script>

        <script>
            numCalendrier += 1;

            calendrier = document.getElementsByClassName("corpsCalendrier");
            calendrier[numCalendrier].id = "calendrier" + numCalendrier;

            //Appel de la fonction pour créer les calendriers
            instancier(numCalendrier);
            afficherCalendrier("inactif", numCalendrier);

            changerDates(numCalendrier, 2);

        </script>

                    <div class="logdem">
                        <div class="logrowb" id="datesPlage">
                            <p class="dateresa demresaP"></p>
                            <p class="dateresa demresaP"></p>
                        </div>
                        <form>
                            <button class="btn-demlognoP" type="submit" disabled>Demander un devis</button>
                            <div class="logrowt">
                                <p class="nuit"><?php echo $logement_data['tarif_de_base'];?> €/nuit</p>
                            </div>
                        </form>
                    </div>
    
                </div>


        <div class="logI">
                <h3 class="policetitres">Informations du logement</h3>
                    <?php

                    } else {
                        echo 'Aucune donnée de logement à prévisualiser.';
                    }?>
                <div class="logrow">
                    <div class="logcp">
                        <h4 class="potitres">Equipements</h4>
                        <p><img src="../svg/tree-fill.svg" alt="Jardin"> jardin   <?php  echo $surface_jardin; ?> m<sup>2</sup></p>
                        <?php
                        $equip = false;
                        if (isset($balcon) && $balcon == true) {
                            ?><p><img src="../svg/balcon.svg" alt="Balcon"><?php  echo 'Balcon'; ?></p><?php
                            $equip = true;
                        }

                        if (isset($terrasse) && $terrasse == true) {
                            ?><p><img src="../svg/terasse.svg" alt="Terasse"><?php  echo 'Terrasse'; ?></p><?php
                            $equip = true;
                        }
                        if (isset($parking_privee) && $parking_privee == true) {
                            ?><p><img src="../svg/PARKING.svg" alt="Parking privée"><?php  echo 'Parking privée'; ?></p><?php
                            $equip = true;
                        }

                        if (isset($parking_public) && $parking_public == true) {
                            ?><p><img src="../svg/PARKING.svg" alt="Parking public"><?php  echo 'Parking public'; ?></p><?php
                            $equip = true;
                        }
                        if (isset($television) && $television == true) {
                            ?><p><img src="../svg/TELEVISION.svg" alt="Télévision"><?php  echo 'Television'; ?></p><?php
                            $equip = true;
                        }
                        if (isset($wifi) && $wifi == true) {
                            ?><p><img src="../svg/WIFI.svg" alt="Wifi"><?php  echo 'Wifi'; ?></p><?php
                            $equip = true;
                        }
                        if (isset($lave_linge) &&  $lave_linge == true) {
                            ?><p><img src="../svg/contrast-drop-2-fill.svg" alt="Lave-linge"><?php  echo 'Lave-linge'; ?></p><?php
                            $equip = true;
                        }
                        if (isset($lave_vaisselle) &&  $lave_vaisselle == true) {
                            ?><p><img src="../svg/CUISINE.svg" alt="Cuisine équipée"><?php  echo 'Cuisine équipée'; ?></p><?php
                            $equip = true;
                        }
                        if (!$equip){
                            ?><p>Aucuns équipements.</p><?php
                        }
                        ?>
                    </div>
                    <hr class="hr">
                    <div class="logcp">
                        <h4 class="potitres">Installations</h4>
                        <?php
                        $install = false;
                        if (isset($climatisation) && $climatisation == true) {
                            ?><p><img src="../svg/windy-line.svg" alt="Climatisation"><?php  echo 'Climatisation'; ?></p><?php
                            $install = true;
                        }
                        if (isset($piscine) && $piscine == true) {
                            ?><p><img src="../svg/PISCINE.svg" alt="Piscine"> <?php  echo 'Piscine'; ?></p><?php
                            $install = true;
                        }

                        if (isset($sauna) &&  $sauna == true) {
                            ?><p><img src="../svg/PISCINE.svg" alt="Sauna"><?php  echo 'Sauna'; ?></p><?php
                            $install = true;
                        }

                        if (isset($hammam) &&  $hammam == true) {
                            ?><p><img src="../svg/PISCINE.svg" alt="Hammam"><?php  echo 'Hammam'; ?></p><?php
                            $install = true;
                        }

                        if (isset($jacuzzi) && $jacuzzi == true) {
                            ?><p><img src="../svg/PISCINE.svg" alt="Jacuzzi"><?php  echo 'Jacuzzi'; ?></p><?php
                            $install = true;
                        }
                        if (!$install){
                            ?><p>Aucunes installations.</p><?php
                        }
                        ?>
                    </div>
                    <hr class="hr">
                    <div class="logcp">
                        <h4 class="potitres">Services</h4>
                        <?php
                            $services = false;
                            if (isset($navette) && $navette == true) {
                                ?><p><img src="../svg/taxi-fill.svg" width="35" height ="35" alt="Taxi"><?php  echo 'Navette ou Taxi'; ?></p><?php
                                $services = true;
                            }
                            if (isset($menage) && $menage == true) {
                                ?><p><img src="../svg/nettoyage.svg" width="35" height="35" alt="Ménage"> <?php  echo 'Menage'; ?></p><?php
                                $services = true;
                            }
                            if (isset($linge) && $linge == true) {
                                ?><p><img src="../svg/t-shirt-air-line.svg" width="35" height ="35" alt="Linge"><?php  echo 'Linge'; ?></p><?php
                                $services = true;
                            }
                            if (!$services){
                                ?><p>Pas de services.</p><?php
                            }
                        ?>
                    </div>
                </div>
                <hr class="hr">
                <div class="logrow">
                    <div class="logcp">
                        <p><img src="../svg/CHAMBRE.svg" alt="Lit simple"> <?php  echo $logement_data['nb_lit_simple'] ?> lit(s) simple(s)</p>
                        <p><img src="../svg/CHAMBRE.svg" alt="Lit double"><?php  echo $logement_data['nb_lit_double'] ?> lit(s) double(s)</p>
                        <p><img src="../svg/ruler.svg" width="24px" height="24px" alt="Surface"><?php echo $logement_data['surface_maison'];?>m<sup>2<sup></p>
                    </div>
                    <div class="logcp">
                        <p><img src="../svg/CHAMBRE.svg" alt="Chambre"><?php  echo $logement_data['nb_chambres'] ?> chambre(s)</p>
                        <p><img src="../svg/SALLE_DE_BAIN.svg" alt="Salle de bain"><?php  echo $logement_data['nb_sdb'] ?> salle(s) de bain</p>
                        <p><img src="../svg/group.svg" width="24px" height="24px" alt="Nombre de personnes"><?php echo $logement_data['nb_personne_max'];?> personnes  </p>
                    </div>
                </div>
        </div>
        <hr class="hrP">
        
        <div class="logcarte">
            <h3 class="policetitre">Localisation</h3>
            <div id = "containerMap">
                <div id="map">
                    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
                </div>
                <p id="message"></p>   
                <p id="adresse"></p>   

                
                <script>
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

                    // Image du marqueur
                    var ownIcon = L.icon({
                        iconUrl: '../svg/map-pin-fill (2).svg',

                        iconSize: [48, 48],
                        iconAnchor: [22, 48],
                        popupAnchor: [3, -24]
                    });

                    //ville à géocoder
                    var commune = "<?php echo $ville;?>";
                    console.log(commune);

                    var opencageUrl = "https://api.opencagedata.com/geocode/v1/json?q=" + encodeURIComponent(commune) + "&key=90a3f846aa9e490d927a787facf78c7e";

                    fetch(opencageUrl)
                        .then(response => response.json())
                        .then(data => {
                            var adresse = document.getElementById('adresse');
                            if (data.results.length > 0) {
                                console.log(data);
                                var communeAdresse = `Adresse : ${data.results[0].formatted}.<br>`;
                                afficherCommuneSurMap(data.results[0].geometry.lat, data.results[0].geometry.lng);
                                adresse.innerHTML = communeAdresse;
                            } else {
                                var messageElement = document.getElementById('message');
                                messageElement.innerHTML = "La ville à afficher n'est pas valide.";
                            }
                        })
                        .catch(error => {
                            console.error("Erreur lors de la requête de géocodage:", error);
                        });
                    
                    function afficherCommuneSurMap(lat, lng) {
                        var map = L.map('map').setView([lat, lng], 9);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map);

                        L.marker([lat, lng], {icon: ownIcon}).addTo(map).bindPopup('Le logement est ici !');
                    }
                
                </script>
            </div>
        </div>
        
        <div class="logrow" style="margin-top:2em;">
            <form method='POST' action='annuler_logement.php' enctype="multipart/form-data">
                <button class="btn-desactive" type='submit'>Annuler</button>
            </form>
            <form method='POST' action='ajouter_logement.php' enctype="multipart/form-data">
                <button class="btn-demlogP" type='submit'>Créer le logement</button>
            </form>
        </div>
    </main>


    <?php
        // appel du footer
        include('../header-footer/choose_footer.php'); 
    ?>
</body>

</html>
<script src="caroussel.js" defer></script>