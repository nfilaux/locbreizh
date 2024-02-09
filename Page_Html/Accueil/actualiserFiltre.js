// Sélection de tous les éléments de type radio avec le nom "options"
const checkboxes = document.querySelectorAll('input[type="checkbox"]');
const inputs = document.querySelectorAll('input[type="number"], input[type="text"], input[type="date"], input[type="radio"]');
    
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
        console.log(filtres);
        if (filtres[0]){
            filtres = filtres[0].split(',');
        }
        let filtre = checkbox.value;
        let filtrePresent = filtres.includes(filtre); //tgl timéo

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
var timeoutId;

// Ajout d'un écouteur d'événement input à chaque champ filtrant
inputs.forEach(input => {
    input.addEventListener('change', function(event) {
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
            
        
    });
});

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




