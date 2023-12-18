// Ouvrir la popup
function openPopup(id, overlay) {
    var popup = document.getElementById(id);
    var overlay = document.getElementById(overlay);
    popup.style.display = 'block';
    overlay.style.display = 'block';
    overlay.addEventListener('click', function (event) {
        if (event.target === overlay) {
            closePopup();
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


