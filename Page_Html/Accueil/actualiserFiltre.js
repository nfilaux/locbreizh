var prixMinInput = document.getElementById('prix_min');
var prixMaxInput = document.getElementById('prix_max');
var lieuInput = document.getElementById('lieu');
var proprietaireInput = document.getElementById('proprietaire');
var personneInput = document.getElementById('personne');

// PRIX MINIMUM
prixMinInput.addEventListener("input",handlePriceInputChange)

// PRIX MAX
prixMaxInput.addEventListener("input",handlePriceInputChange)

// LIEU
lieuInput.addEventListener("input", function() { modifierChamp(lieuInput); });

// PROPRIETAIRE
proprietaireInput.addEventListener("input", function() { modifierChamp(proprietaireInput); });

// VOYAGEUR
personneInput.addEventListener("input", function() { modifierChamp(personneInput); });

function handlePriceInputChange() {
    lieuInput.disabled = true;
    proprietaireInput.disabled = true;

    if (prixMinInput.value !== '' || prixMaxInput.value !== '') {
        lieuInput.disabled = true;
        proprietaireInput.disabled = true;
        personneInput.disabled = true;
    } else {
        lieuInput.disabled = false;
        proprietaireInput.disabled = false;
        personneInput.disabled = false;
    }
}

function modifierChamp(champModifie) {
    var champs = [prixMinInput, prixMaxInput, lieuInput, proprietaireInput, personneInput];

    // Désactiver tous les champs sauf celui modifié
    champs.forEach(function(champ) {
        if (champ !== champModifie) {
            champ.disabled = true;
        }
    });

    champModifie.disabled = false;

    // Réactiver tous les champs si le champ modifié est vidé
    if ((champModifie.value === '')||(champModifie.value == 0)) {
        champs.forEach(function(champ) {
            champ.disabled = false;
        });
    }
}


