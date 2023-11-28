// Ouvrir la popup
function openMdpPopup() {
    var popup = document.getElementById('mdpPopup');
    var overlay = document.getElementById('overlay');
    popup.style.display = 'block';
    overlay.style.display = 'block';
}

// Fermer la popup
function closeMdpPopup() {
    var popup = document.getElementById('mdpPopup');
    var overlay = document.getElementById('overlay');
    popup.style.display = 'none';
    overlay.style.display = 'none';
}

// Ajouter un gestionnaire d'événements pour fermer la pop-up en cliquant à l'extérieur
var overlay = document.getElementById('overlay');
overlay.addEventListener('click', function (event) {
    if (event.target === overlay) {
        closeMdpPopup();
    }
});
