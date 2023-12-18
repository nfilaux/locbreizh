//recupération des element du html qu'on vas remplir d'information
dateActuelle = document.querySelectorAll(".date_actuelle");
baliseJour = document.querySelectorAll(".jours");
precedentSuivant = document.querySelectorAll(".fleches svg");
datesPlage = document.querySelectorAll("#datesPlage .dateresa");
boutonsDates = document.querySelectorAll(".logc form input");

//constante pour les mois de l'année
const tabMois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

//création de dates qui vont êtres utilisé pour le premier et deuxieme calendrier
date = new Date();
anneeActuelle = date.getFullYear();
moisActuel = date.getMonth();
date2 = new Date(anneeActuelle, moisActuel+1);
anneeActuelle2 = date2.getFullYear();
moisActuel2 = date2.getMonth();

//fonction pour actualiser le calendrier en fonction des dates
function afficherCalendrier(){
    premierJourMois = new Date(anneeActuelle, moisActuel, 0).getDay();
    derniereDateMois = new Date(anneeActuelle, moisActuel + 1, 0).getDate();
    derniereJourMois = new Date(anneeActuelle, moisActuel, derniereDateMois-1).getDay();
    derniereDateMoisAvant = new Date(anneeActuelle, moisActuel, 0).getDate();
    texteListe = "";

    premierJourMois2 = new Date(anneeActuelle2, moisActuel2, 0).getDay();
    derniereDateMois2 = new Date(anneeActuelle2, moisActuel2 + 1, 0).getDate();
    derniereJourMois2 = new Date(anneeActuelle2, moisActuel2, derniereDateMois2-1).getDay();
    derniereDateMoisAvant2 = new Date(anneeActuelle2, moisActuel2, 0).getDate();
    texteListe2 = "";

    nbJours = 0;
    k = 0;

    for (i = premierJourMois-1; i >= 0; i--){
        texteListe += '<li class="inactif">' + (derniereDateMoisAvant-i) + '</li>';
        nbJours++;
    }

    for (i = 1; i <= derniereDateMois; i++){
        idJour = (k+1) + '/' + (moisActuel+1) + '/' + anneeActuelle;
        texteListe += '<li onclick="changerJour(' + (k+1) + ')" id=' + idJour +' class="normal">' + i + '</li>';
        k++;
        nbJours++;
    }

    for (i = derniereJourMois; nbJours<42; i++){
        texteListe += '<li class="inactif">' + (i-derniereJourMois+1) + '</li>';
        nbJours++;
    }


    for (i = premierJourMois2-1; i >= 0; i--){
        texteListe2 += '<li class="inactif">' + (derniereDateMoisAvant2-i) + '</li>';
        nbJours++;
    }

    for (i = 1; i <= derniereDateMois2; i++){
        idJour = (k+1-derniereDateMois) + '/' + (moisActuel2+1) + '/' + anneeActuelle2;
        texteListe2 += '<li onclick="changerJour('+ (k+1) +')" id=' + idJour +' class="normal">' + i + '</li>';
        k++;
        nbJours++;
    }

    for (i = derniereJourMois2; nbJours<84; i++){
        texteListe2 += '<li class="inactif">' + (i-derniereJourMois2+1) + '</li>';
        nbJours++;
    }
    
    dateActuelle[0].innerHTML = (tabMois[moisActuel] + ' ' + anneeActuelle);
    baliseJour[0].innerHTML = texteListe;
    dateActuelle[1].innerHTML = (tabMois[moisActuel2] + ' ' + anneeActuelle2);
    baliseJour[1].innerHTML = texteListe2;
}

//Appel de la fonction pour créer les calendriers
afficherCalendrier();

//permet de créer une selection
function selection(debut, fin){
    changerJour(debut);
    changerJour(fin);
}

//création d'une selection d'une semaine à partir d'aujourd'hui
premierID = (date.getDate());
dernierID = (date.getDate()+6);
selection(premierID, dernierID);
changerDates();

//permet de changer les dates des calendrier et de les actualiser quand on appuie sur les flèches
precedentSuivant.forEach(element => {
    element.addEventListener("click", () => {
        if (element.id === "precedent"){
            moisActuel = moisActuel-1;
            moisActuel2 = moisActuel2-1;
        }
        else{
            moisActuel = moisActuel+1;
            moisActuel2 = moisActuel2+1;
        }
        date = new Date(anneeActuelle, moisActuel);
        anneeActuelle = date.getFullYear();
        moisActuel = date.getMonth();
        date2 = new Date(anneeActuelle2, moisActuel2);
        anneeActuelle2 = date2.getFullYear();
        moisActuel2 = date2.getMonth();
        afficherCalendrier();
        selection(premierID, dernierID);
        changerDates();
    })
});

//permet de changer les styles des jours en fonction de la selection
function changerJour(elem){
    if (elem <= derniereDateMois){
        idElem =  elem.toString() + '/' + (moisActuel+1) + '/' + anneeActuelle;
        element = document.getElementById(idElem);
    }
    else{
        idElem =  (elem-derniereDateMois).toString() + '/' + (moisActuel2+1) + '/' + anneeActuelle2;
        element = document.getElementById(idElem);
    }
    nbActif = document.getElementsByClassName("actif").length;
    entreDeux = document.getElementsByClassName("entreDeux");
    nbEntreDeux = entreDeux.length;
    for (i=0; i < nbEntreDeux; i++){
        entreDeux[0].className = "normal";
    }
    
    if (element.className === "normal" || element.className === "entreDeux"){
        if (nbActif == 0){
            premierID = element.id;
            dernierID = element.id;
            element.className = "actif";
        }
        else{
            //milieu = (parseInt(premierID.split('/')[0])+parseInt(dernierID.split('/')[0]))/2
            nbDuId = (element.id).split('/')[0];
            if (nbDuId < parseInt(premierID.split('/')[0]) && (element.id).split('/')[1] == parseInt(premierID.split('/')[1])){
                if (nbActif > 1){
                    document.getElementById(premierID).className = "normal";
                }
                premierID = element.id;
                element.className = "actif";
            }
            else{
                if (nbActif > 1){
                    document.getElementById(dernierID).className = "normal";
                }
                dernierID = element.id;
                element.className = "actif";
            }
            trouve = false;
            for (i = 1; i < 84; i++){
                if (i <= derniereDateMois){
                    comparaison = i + '/' + (moisActuel+1) + '/' + anneeActuelle;
                }
                else{
                    comparaison = (i-derniereDateMois) + '/' + (moisActuel2+1) + '/' + anneeActuelle2;
                }
                if (comparaison === dernierID){
                    trouve = false;
                }
                if (trouve){
                    if (i <= derniereDateMois){
                        document.getElementById(i.toString() + '/' + (moisActuel+1) + '/' + anneeActuelle).className = "entreDeux";
                    }
                    else{
                        document.getElementById((i-derniereDateMois).toString() + '/' + (moisActuel2+1) + '/' + anneeActuelle2).className = "entreDeux";
                    }
                }
                if (comparaison === premierID){
                    trouve = true;
                }
            }
        }
    }
    else if (element.className === "actif"){
        if (nbActif == 2){
            if (element.id === premierID){
                premierID = dernierID;
            }
            else{
                dernierID = premierID;
            }
        }
        element.className = "normal";
    }
    changerDates();
}

function changerDates(){
    newPId = premierID;
    if (premierID.split('/')[0].length == 1){
        if (premierID.split('/')[1].length == 1){
            newPId = '0' + premierID.split('/')[0] + '/0' + premierID.split('/')[1] + '/' + premierID.split('/')[2];
        }
        else{
            newPId = '0' + premierID.split('/')[0] + '/' + premierID.split('/')[1] + '/' + premierID.split('/')[2];
        }
    }
    newDId = dernierID;
    if (dernierID.split('/')[0].length == 1){
        if (dernierID.split('/')[1].length == 1){
            newDId = '0' + dernierID.split('/')[0] + '/0' + (dernierID.split('/')[1]).toString() + '/' + dernierID.split('/')[2];
        }
        else{
            newDId = '0' + dernierID.split('/')[0] + '/' + premierID.split('/')[1] + '/' + premierID.split('/')[2];
        }
    }
    datesPlage[0].innerHTML = "<p>Arrivée</p><p>" + newPId + "</p>";
    datesPlage[1].innerHTML = "<p>Départ</p><p>" + newDId + "</p>";
    boutonsDates[0].value = newPId.replaceAll("/", '-') ;
    boutonsDates[1].value = newDId.replaceAll("/", '-') ;
}