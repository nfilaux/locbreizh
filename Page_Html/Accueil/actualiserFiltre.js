// Sélection de tous les éléments de type radio avec le nom "options"
const radios = document.querySelectorAll('input[type="radio"][name="typeH"]');
const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    
// Fonction pour ajouter des paramètres à une URL
function ajouterParametreUrl(url, parametre, valeur) {
    const separateur = (url.indexOf('?') !== -1) ? '&' : '?';
    return url + separateur + parametre + '=' + encodeURIComponent(valeur);
}

// Fonction pour rediriger vers une nouvelle URL
function redirigerVersNouvelleUrl(url) {
    window.location.href = url;
}

function ajouterOuRemplacerParametresUrl(url, parametres) {
    const urlObj = new URL(url);
    for (const [parametre, valeur] of Object.entries(parametres)) {
        // Vérifie si la valeur du paramètre n'est pas vide
        if (valeur) {
            urlObj.searchParams.set(parametre, valeur);
        } else {
            // Si la valeur est vide, supprime le paramètre de l'URL
            urlObj.searchParams.delete(parametre);
        }
    }
    return urlObj.toString();
}


// Fonction pour récupérer tous les filtres à partir de l'URL
function obtenirFiltres() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.getAll('filtre');
}

// Ajout d'un écouteur d'événement change à chaque case à cocher
checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function(event) {
        let filtres = obtenirFiltres();
        filtres = filtres[0].split(',');
        const filtre = checkbox.value;
        const filtrePresent = filtres.includes(filtre); //tgl timéo

        // Si la case est cochée et le filtre n'est pas déjà présent dans l'URL
        if (checkbox.checked && !filtrePresent) {
            filtres.push(filtre);
        // Si la case est décochée et le filtre est présent dans l'URL
        } else if (!checkbox.checked && filtrePresent) {
            const index = filtres.indexOf(filtre);
            filtres.splice(index, 1);
        }

        // Si aucun filtre n'est présent, retirez le paramètre 'filtre' de l'URL
        const parametres = filtres.length > 0 ? {'filtre': filtres.join(',')} : {};
        const nouvelleUrl = ajouterOuRemplacerParametresUrl(window.location.href, parametres);
        // Redirection vers la nouvelle URL
        redirigerVersNouvelleUrl(nouvelleUrl);
    });
});




// Ajout d'un écouteur d'événement change à chaque bouton radio
radios.forEach(radio => {
    radio.addEventListener('change', function(event) {
        const parametres = {
            'filtre': radio.value
        };
        const nouvelleUrl = ajouterOuRemplacerParametresUrl(window.location.href, parametres);
        redirigerVersNouvelleUrl(nouvelleUrl);
    });
});
