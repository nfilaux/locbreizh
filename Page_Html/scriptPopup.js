// Ouvrir la popup
function openPopup() {
    var popup = document.getElementById('popup');
    var overlay = document.getElementById('overlay');
    popup.style.display = 'block';
    overlay.style.display = 'block';
}

// Fermer la popup
function closePopup() {
    var popup = document.getElementById('popup');
    var overlay = document.getElementById('overlay');
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