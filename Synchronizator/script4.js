//var derniereClefId = null;
let rowIndex = null;
alert("he ho");

$(document).ready(function() {
    // Désactive toutes les cases sauf celles de la ligne sélectionnée
    $('#droitsTable').on('change', 'input[type="checkbox"]', function() {
        var row = $(this).closest('tr');
        rowIndex = row.index();
        if ($(this).prop('checked')) {
            // Case cochée alors on, désactivee les autres cases sauf celles de la ligne sélectionnée
            $('.chkPetiteConsultation, .chkConsultationCalendrier, .chkRendreIndisponible, .chkRendreDisponible')
                .not(row.find('input[type="checkbox"]')).prop('disabled', true);

            // Utilisation de rowIndex pour récupérer l'index de la ligne sélectionnée
            console.log("Ligne sélectionnée : " + rowIndex);
        } else {
            // Case décochée alors on réactive toutes les cases
            $('.chkPetiteConsultation, .chkConsultationCalendrier, .chkRendreIndisponible, .chkRendreDisponible')
                .prop('disabled', false);
        }
    });
});

$(document).ready(function() {
    $('#boutonSauvegarder').on('click', function() {
        if ($('.nouvelleClef').text() != "Nouvelle Clef") {
            alert('La nouvelle clef a bien été enregistrée.');

            document.getElementById('nouvelleClefAPI').innerHTML = "Nouvelle Clef API";

            // Récupérer les valeurs des cases à cocher
            var petiteConsultation = $('#droitsTable tr:eq(' + rowIndex + ') td:eq(1) input').is(':checked');
            var consultationCalendrier = $('#droitsTable tr:eq(' + rowIndex + ') td:eq(2) input').is(':checked');
            var rendreIndisponible = $('#droitsTable tr:eq(' + rowIndex + ') td:eq(3) input').is(':checked');
            var rendreDisponible = $('#droitsTable tr:eq(' + rowIndex + ') td:eq(4) input').is(':checked');
            var derniereClefId = parseInt($('#droitsTable tr:eq(' + rowIndex + ') td:eq(0)').text());

            $.ajax({
                type: 'POST',
                url: 'DroitBack.php',
                data: {
                    action: 'fonctionSauvegarder',
                    petiteConsultation: petiteConsultation,
                    consultationCalendrier: consultationCalendrier,
                    rendreIndisponible: rendreIndisponible,
                    rendreDisponible: rendreDisponible,
                    derniereClefId: derniereClefId,
                },
                success: function(response) {
                    console.log('Réponse du serveur :', response);
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX :', status, error);
                },
                complete: function() {
                    console.log('Requête AJAX terminée');
                },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                }
            });
        } else {
            alert('Veuillez créer une nouvelle clef avant de l\'enregistrer');
        }
    });
});

$(document).ready(function() {
    // Fonction pour créer une nouvelle ligne
    function createNewRow() {
        // Crée une nouvelle ligne dans la table
        var newRow = $('<tr class="NouvelleClef">' +
            '<td>Nouvelle Clef originelle</td>' +
            '<td><input type="checkbox" name="petiteConsultation" class="chkPetiteConsultation"></td>' +
            '<td><input type="checkbox" name="consultationCalendrier" class="chkConsultationCalendrier"></td>' +
            '<td><input type="checkbox" name="rendreIndisponible" class="chkRendreIndisponible"></td>' +
            '<td><input type="checkbox" name="rendreDisponible" class="chkRendreDisponible"></td>' +
            '</tr>');
        $('#droitsTable').append(newRow);

        // Désactive les autres cases de la ligne si "rendreDisponible" est coché
        newRow.find('input[name="rendreDisponible"]').on('change', function() {
            var checkboxes = newRow.find('input[type="checkbox"]').not($(this));
            checkboxes.prop('checked', false);
            checkboxes.prop('disabled', $(this).prop('checked'));
        });
    }

    // Attache l'événement au bouton "Nouvelle Clef API"
    $('#nouvelleClefAPI').on('click', function() {
        if ($(this).text() === "Nouvelle Clef API") {
            createNewRow();

            // Change le texte du bouton
            $(this).text("Générer Clef");
        } else {
            // Appelle la fonction pour générer la clé
            genererClef();
        }
    });
});

// Fonction pour générer une nouvelle clé
function genererClef() {
    // Génère un ID unique en utilisant une fonction JavaScript
    var nouvelId = uniqueId();
    var newRow = $('#droitsTable .NouvelleClef');
    newRow.find('td:first').text(nouvelId);

    // Effectue d'autres actions nécessaires ici, si nécessaire

    alert('Nouvelle clef générée : ' + nouvelId);
}

// Fonction pour générer un ID unique en JavaScript
function uniqueId() {
    // Date en millisecondes
    return Date.now();
}



        
