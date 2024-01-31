var prixMinInput = document.getElementById('prix_min');
var prixMaxInput = document.getElementById('prix_max');
var dateInput = document.getElementById('date');


// PRIX MINIMUM
prixMinInput.addEventListener("input",handlePriceInputChange)

// PRIX MAX
prixMaxInput.addEventListener("input",handlePriceInputChange)

// Date
dateInput.addEventListener("input", function() { modifierChamp(dateInput); });


function handlePriceInputChange() {
    dateInput.disabled = true;

    if (prixMinInput.value !== '' || prixMaxInput.value !== '') {
        dateInput.disabled = true; 
    } else {
        dateInput.disabled = false;
    }
}

function modifierChamp(champModifie) {
    var champs = [prixMinInput, prixMaxInput, dateInput];

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


