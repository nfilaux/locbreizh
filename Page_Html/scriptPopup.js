// Ouvrir la popup
function openPopup(id, overlay) {
    var popup = document.getElementById(id);
    var overlay = document.getElementById(overlay);
    popup.style.display = 'block';
    overlay.style.display = 'block';
}

// Fermer la popup
function closePopup(id, overlay) {
    var popup = document.getElementById(id);
    var overlay = document.getElementById(overlay);
    popup.style.display = 'none';
    overlay.style.display = 'none';
}

// Ajouter un gestionnaire d'événements pour fermer la pop-up en cliquant à l'extérieur
var overlay = document.getElementById('overlay');
overlay.addEventListener('click', function (event) {
    if (event.target === overlay) {
        closePopup();
    }
});