document.addEventListener("DOMContentLoaded", function() {
    var boutton = document.getElementById('afficher-plus-avis');

    function afficherAvis() {
        var avisCache = document.querySelectorAll('.box-avis.hidden');
        avisCache.forEach(function(avis) {
            avis.classList.remove('hidden');
        });

        boutton.style.display = 'none';
    }

    boutton.addEventListener('click', afficherAvis);
});