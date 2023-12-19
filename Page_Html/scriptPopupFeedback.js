
// Ouverture et fermeture de la popup

function openPopupFeedback(id, overlayId) {
    let popup = document.getElementById(id);
    let overlay = document.getElementById(overlayId);

    popup.style.display = 'block';
    overlay.style.display = 'block';

    overlay.addEventListener('click', function (event) {
        if (event.target === overlay) {
            closePopup(id, overlayId);
        }
    });
}

function closePopupFeedback(id, overlayId) {
    let popup = document.getElementById(id);
    let overlay = document.getElementById(overlayId);

    popup.style.display = 'none';
    overlay.style.display = 'none';
}
