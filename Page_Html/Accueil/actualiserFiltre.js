// Sélection de tous les éléments de type radio avec le nom "options"
const checkboxes = document.querySelectorAll('input[type="checkbox"]');
const inputs = document.querySelectorAll('input[type="number"], input[type="text"], input[type="radio"]');
const inputsDate = document.querySelectorAll('input[type="date"]');
    
// Fonction pour ajouter des paramètres à une URL
function ajouterParametreUrl(url, parametre, valeur) {
    let separateur = (url.indexOf('?') !== -1) ? '&' : '?';
    return url + separateur + parametre + '=' + encodeURIComponent(valeur);
}

// Fonction pour rediriger vers une nouvelle URL
function redirigerVersNouvelleUrl(url) {
    window.location.href = url;
}

function ajouterOuRemplacerParametresUrl(url, parametres) {
    let urlObj = new URL(url);
    for (let [parametre, valeur] of Object.entries(parametres)) {
        // Vérifie si la valeur du paramètre n'est pas vide
        if (valeur) {
            urlObj.searchParams.set(parametre, valeur);
        } else {
            // Si la valeur est vide, supprime le paramètre de l'URL
            urlObj.searchParams.delete(parametre);
        }
    }
    if (Object.entries(parametres) == ''){
        urlObj.searchParams.delete("filtre");
    }
    return urlObj.toString();
}


// Fonction pour récupérer tous les filtres à partir de l'URL
function obtenirFiltres() {
    let urlParams = new URLSearchParams(window.location.search);
    return urlParams.getAll('filtre');
}

// Ajout d'un écouteur d'événement change à chaque case à cocher
checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function(event) {
        let filtres = obtenirFiltres();
        
        if (filtres[0]){
            filtres = filtres[0].split(',');
        }
        let filtre = checkbox.value;
        let filtrePresent = filtres.includes(filtre);

        // Si la case est cochée et le filtre n'est pas déjà présent dans l'URL
        if (checkbox.checked && !filtrePresent) {
            filtres.push(filtre);
        // Si la case est décochée et le filtre est présent dans l'URL
        } else if (!checkbox.checked && filtrePresent) {
            let index = filtres.indexOf(filtre);
            filtres.splice(index, 1);
        }

        // Si aucun filtre n'est présent, retirez le paramètre 'filtre' de l'URL
        let parametres = filtres.length > 0 ? {'filtre': filtres.join(',')} : {};
        let nouvelleUrl = ajouterOuRemplacerParametresUrl(window.location.href, parametres);
        // Redirection vers la nouvelle URL
        redirigerVersNouvelleUrl(nouvelleUrl);
    });
});




// Stockage des valeurs des champs de saisie dans un tableau 'filtresInput'
var filtresInput = {};

// Ajout d'un écouteur d'événement change à chaque champ filtrant autre que les dates dans la barre de recherche
inputs.forEach(input => {input.addEventListener('change', () => {ajouterFiltreURL(input);});});

// Ajout d'un écouteur d'événement blur à chaque champ date filtrant dans la barre de recherche
inputsDate.forEach(input => {input.addEventListener('blur', () => {ajouterFiltreURL(input);});});

function ajouterFiltreURL(input){
    let date = new Date().toLocaleDateString();
    let tabDate = date.split("/");
    let dateFormat = tabDate[2] + "-" + tabDate[1] + "-" + tabDate[0];
    if (!(input.value=="") && !(input.value==0) && !(input.value==dateFormat)){
        let parametres = obtenirFiltres();

        // Traitement des champs de saisie
        let valeur = input.value;
        let parametre = input.name;

        if (valeur) {
            parametres[parametre] = valeur;
        } else {
            delete parametres[parametre];
        }

        // Reconstruire l'URL avec les paramètres mis à jour
        let nouvelleUrl = ajouterOuRemplacerParametresUrl(window.location.href, parametres);
        // Redirection vers la nouvelle URL
        redirigerVersNouvelleUrl(nouvelleUrl);
    } else {
        return "AHAH YA RIEN";
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner le champ personne
    var personne = document.getElementById('personne');

    // Limiter le nombre de personnes à une valeur minimale de 0
    personne.min = 0;

    // Empêcher la saisie de nombres négatifs dans le champ personne
    personne.addEventListener('input', function() {
        if (personne.value < 0) {
            personne.value = 0;
        }
    });
});


// Sélection des boutons de suppression de filtres
const btns_supF = document.querySelectorAll(".btn-supF");

// Fonction pour supprimer un paramètre spécifique de l'URL tout en conservant les autres paramètres
function supprimerParametreUrl(parametre) {
    let url = window.location.href;
    let urlObj = new URL(url);
    let params = new URLSearchParams(urlObj.search);

    // Suppression du paramètre spécifique au filtre en question
    params.delete(parametre);

    // Reconstruction de l'URL avec les paramètres mis à jour
    urlObj.search = params.toString();

    return urlObj.toString();
}

// Ajout d'un écouteur d'événement à chaque bouton de suppression de filtre
btns_supF.forEach(btn => {
    btn.addEventListener("click", function() {
        let params = new URLSearchParams(window.location.search);
        // Récupération du nom du filtre à supprimer
        let filterName = btn.querySelector("span:first-child").getAttribute("id");

        let nouvelleUrl = null;
        //Cas exceptionnel pour les prix et les dates
        if (filterName === 'prix') {
            if (params.has('prix_min') || params.has('prix_max')) {
                params.delete('prix_min');
                params.delete('prix_max');
                nouvelleUrl = window.location.pathname + '?' + params.toString();
            }
        } else if (filterName === 'date') {
            if (params.has('date1') || params.has('date2')) {
                params.delete('date1');
                params.delete('date2');
                nouvelleUrl = window.location.pathname + '?' + params.toString();
            }
        } else {
            // Suppression du paramètre correspondant de l'URL
            params.delete(filterName);
            nouvelleUrl = window.location.pathname + '?' + params.toString();
        }

        // Redirection vers la nouvelle URL sans le paramètre supprimé
        window.location.href = nouvelleUrl;
    });
});

// Sélection des boutons de suppression de filtres
const btns_sup_checkboxes = document.querySelectorAll(".btn-sup-checkboxes");

// Ajout d'un écouteur d'événement à chaque bouton de suppression de filtre
btns_sup_checkboxes.forEach(btn => {
    btn.addEventListener("click", function() {
        let id = (btn.id).replace("BIS", "");
        let checkbox = document.getElementById(id);
        checkbox.checked = false;

        let filtres = obtenirFiltres();
        
        if (filtres[0]){
            filtres = filtres[0].split(',');
        }
        let filtre = checkbox.value;
        let filtrePresent = filtres.includes(filtre);

        // Si la case est cochée et le filtre n'est pas déjà présent dans l'URL
        if (checkbox.checked && !filtrePresent) {
            filtres.push(filtre);
        // Si la case est décochée et le filtre est présent dans l'URL
        } else if (!checkbox.checked && filtrePresent) {
            let index = filtres.indexOf(filtre);
            filtres.splice(index, 1);
        }

        // Si aucun filtre n'est présent, retirez le paramètre 'filtre' de l'URL
        let parametres = filtres.length > 0 ? {'filtre': filtres.join(',')} : {};
        let nouvelleUrl = ajouterOuRemplacerParametresUrl(window.location.href, parametres);
        
        // Redirection vers la nouvelle URL
        redirigerVersNouvelleUrl(nouvelleUrl);
    });
});

