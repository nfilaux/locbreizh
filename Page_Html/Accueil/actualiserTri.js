const selectElement = document.querySelector(".triage");

// Récupérer la valeur du paramètre "tri" dans l'URL
var urlParams = new URLSearchParams(window.location.search);
var triValue = urlParams.get("tri");

// Définir la valeur de l'élément <select> en fonction de la valeur du paramètre "tri"
if (triValue) {
    selectElement.value = triValue;
}

selectElement.addEventListener("change", (event) => {
    var urlcourante = document.location.href;

    if (event.target.value === "vide") {
        url = urlcourante.split('?')[0]; // Supprimer tout ce qui suit le "?" dans l'URL
    } else {
        if (urlcourante.indexOf('?') !== -1) {
            // Si oui, vérifier si le paramètre "tri" est déjà présent
            if (urlcourante.indexOf('tri=') !== -1) {
                // Si oui, remplacer la valeur du paramètre "tri" avec la nouvelle valeur
                url = urlcourante.replace(/tri=[^&]*/, 'tri=' + event.target.value);
            } else {
                // Sinon, ajouter le paramètre "tri" à la fin des paramètres existants
                url = urlcourante + `&tri=${event.target.value}`;
            }
        } else {
            // Sinon, il n'y a pas encore de paramètres dans l'URL, ajouter le paramètre "tri"
            url = urlcourante + `?tri=${event.target.value}`;
        }
    }

    window.location.href = url;
});
