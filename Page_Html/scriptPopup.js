// Ouvrir la popup
function openPopup(id, overlay) {
    var popup = document.getElementById(id);
    var overlayPopup = document.getElementById(overlay);
    popup.style.display = 'block';
    overlayPopup.style.display = 'block';
    overlayPopup.addEventListener('click', function (event) {
        if (event.target === overlayPopup) {
            closePopup(id, overlay);
        }
    });
}

// Fermer la popup
function closePopup(id, overlay) {
    var popup = document.getElementById(id);
    var overlay = document.getElementById(overlay);
    popup.style.display = 'none';
    overlay.style.display = 'none';
}

