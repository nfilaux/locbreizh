//recupération des element du html qu'on vas remplir d'information
dateActuelle = document.querySelectorAll(".date_actuelle");
baliseJour = document.querySelectorAll(".jours");
precedentSuivant = document.querySelectorAll(".fleches svg");
datesPlage = document.querySelectorAll("#datesPlage .dateresa");
boutonsDates = document.querySelectorAll(".logdem form input");

//constante pour les mois de l'année
const tabMois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

//création de dates qui vont êtres utilisé pour le premier et deuxieme calendrier
date = new Date();
anneeActuelle = date.getFullYear();
moisActuel = date.getMonth();
date2 = new Date(anneeActuelle, moisActuel + 1);
anneeActuelle2 = date2.getFullYear();
moisActuel2 = date2.getMonth();

//fonction pour actualiser le calendrier en fonction des dates
function afficherCalendrier() {
    //création des dates importantes du calendrier de gauche
    premierJourMois = new Date(anneeActuelle, moisActuel, 0).getDay();
    derniereDateMois = new Date(anneeActuelle, moisActuel + 1, 0).getDate();
    derniereJourMois = new Date(anneeActuelle, moisActuel, derniereDateMois - 1).getDay();
    derniereDateMoisAvant = new Date(anneeActuelle, moisActuel, 0).getDate();
    texteListe = "";

    //création des dates importantes du calendrier de droite
    premierJourMois2 = new Date(anneeActuelle2, moisActuel2, 0).getDay();
    derniereDateMois2 = new Date(anneeActuelle2, moisActuel2 + 1, 0).getDate();
    derniereJourMois2 = new Date(anneeActuelle2, moisActuel2, derniereDateMois2 - 1).getDay();
    derniereDateMoisAvant2 = new Date(anneeActuelle2, moisActuel2, 0).getDate();
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
        idJour = (moisActuel + 1) + '/' + (k + 1) + '/' + anneeActuelle;
        texteListe += '<li onclick="changerJour(this.id)" id=' + idJour + ' class="normal">' + i + '</li>';
        k++;
        nbJours++;
    }

    for (i = derniereJourMois; nbJours < 42; i++) {
        texteListe += '<li class="inactif">' + (i - derniereJourMois + 1) + '</li>';
        nbJours++;
    }


    //création du calendrier de droite
    for (i = premierJourMois2 - 1; i >= 0; i--) {
        texteListe2 += '<li class="inactif">' + (derniereDateMoisAvant2 - i) + '</li>';
        nbJours++;
    }

    for (i = 1; i <= derniereDateMois2; i++) {
        idJour = (moisActuel2 + 1) + '/' + (k + 1 - derniereDateMois) + '/' + anneeActuelle2;
        texteListe2 += '<li onclick="changerJour(this.id)" id=' + idJour + ' class="normal">' + i + '</li>';
        k++;
        nbJours++;
    }

    for (i = derniereJourMois2; nbJours < 84; i++) {
        texteListe2 += '<li class="inactif">' + (i - derniereJourMois2 + 1) + '</li>';
        nbJours++;
    }

    //affiche les mois/années des calendriers
    dateActuelle[0].innerHTML = (tabMois[moisActuel] + ' ' + anneeActuelle);
    baliseJour[0].innerHTML = texteListe;
    dateActuelle[1].innerHTML = (tabMois[moisActuel2] + ' ' + anneeActuelle2);
    baliseJour[1].innerHTML = texteListe2;
}

//Appel de la fonction pour créer les calendriers
afficherCalendrier();

//permet de créer une selection
function selection(debut, fin) {
    changerJour(debut);
    changerJour(fin);
}

//création d'une selection d'une semaine à partir d'aujourd'hui
premierID = date.toLocaleDateString('en-US');
dernierIDJour = new Date(date.getTime() + (60 * 60 * 24 * 6) * 1000);
dernierID = dernierIDJour.toLocaleDateString('en-US');
selection(premierID, dernierID);
changerDates();

//permet de changer les dates des calendrier et de les actualiser quand on appuie sur les flèches
precedentSuivant.forEach(element => {
    element.addEventListener("click", () => {
        //change les mois en fonction de si on appuie sur precedent ou suivant
        if (element.id === "precedent") {
            moisActuel = moisActuel - 1;
            moisActuel2 = moisActuel2 - 1;
        }
        else {
            moisActuel = moisActuel + 1;
            moisActuel2 = moisActuel2 + 1;
        }
        //recrée toutes les en fonctions de nouveau mois et affiche les calendriers actualisés
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
function changerJour(elem) {
    //recupération de l'élément et réinitialistaion du calendrier
    element = document.getElementById(elem);
    nbActif = document.getElementsByClassName("actif").length;
    entreDeux = document.getElementsByClassName("entreDeux");
    nbEntreDeux = entreDeux.length;
    for (i = 0; i < nbEntreDeux; i++) {
        entreDeux[0].className = "normal";
    }
    //cas où l'élément n'est pas une date de début ou de fin de palge
    if (element.className === "normal" || element.className === "entreDeux") {
        //cas ou il n'y as aucune dates de sélectionner
        if (nbActif == 0) {
            premierID = element.id;
            dernierID = element.id;
            element.className = "actif";
        }
        //cas ou il il y a une ou deux dates de sélectionner
        else {
            let dateElem = new Date(element.id).getTime();
            let datePremier = new Date(premierID).getTime();
            let dateDernier = new Date(dernierID).getTime();
            milieu = (dateDernier + datePremier) / 2;
            //détermine si le nouveau jour seras le début ou la fin de la plage
            if (dateElem < milieu) {
                if (nbActif > 1) {
                    document.getElementById(premierID).className = "normal";
                }
                premierID = element.id;
                element.className = "actif";
            }
            else {
                if (nbActif > 1) {
                    document.getElementById(dernierID).className = "normal";
                }
                dernierID = element.id;
                element.className = "actif";
            }
            //active la zone de selection entre les deux dates
            datePremier = new Date(premierID).getTime();
            dateDernier = new Date(dernierID).getTime();
            listeJours = document.querySelectorAll(".jours li");
            for (jour of listeJours) {
                let dateJour = new Date(jour.id).getTime();
                if (dateJour < dateDernier && dateJour > datePremier) {
                    jour.className = "entreDeux";
                }
            }
        }
    }
    //désactive le jour si on clique dessus
    else if (element.className === "actif") {
        if (nbActif == 2) {
            if (element.id === premierID) {
                premierID = dernierID;
            }
            else {
                dernierID = premierID;
            }
        }
        element.className = "normal";
    }
    changerDates();
}

//change les dates se trouvant à coté du calendrier et dans le formulaire pour demander un devis
function changerDates() {
    //change format du premier ID
    if (premierID.split('/')[1].length == 1) {
        newPId = '0' + premierID.split('/')[1];
    }
    else {
        newPId = premierID.split('/')[1];
    }
    if (premierID.split('/')[0].length == 1) {
        newPId += '/0' + premierID.split('/')[0] + '/' + premierID.split('/')[2];
    }
    else {
        newPId += '/' + premierID.split('/')[0] + '/' + premierID.split('/')[2];
    }

    //change format du dernier ID
    if (dernierID.split('/')[1].length == 1) {
        newDId = '0' + dernierID.split('/')[1];
    }
    else {
        newDId = dernierID.split('/')[1];
    }
    if (dernierID.split('/')[0].length == 1) {
        newDId += '/0' + dernierID.split('/')[0] + '/' + dernierID.split('/')[2];
    }
    else {
        newDId += '/' + dernierID.split('/')[0] + '/' + dernierID.split('/')[2];
    }
    //envoie les dates dans le HTML pour l'affichage et pour l'envoie de devis
    datesPlage[0].innerHTML = "<p>Arrivée</p><p>" + newPId + "</p>";
    datesPlage[1].innerHTML = "<p>Départ</p><p>" + newDId + "</p>";
    if (boutonsDates[0]) {
        newPId = newPId.split('/')[2] + "-" + newPId.split('/')[1] + "-" + newPId.split('/')[0];
        newDId = newDId.split('/')[2] + "-" + newDId.split('/')[1] + "-" + newDId.split('/')[0];
        boutonsDates[0].value = newPId;
        boutonsDates[1].value = newDId;
    }
}