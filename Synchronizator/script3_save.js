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
    // Fonction pour désactiver les autres cases de la ligne lorsqu'on coche la case "droitRendredisponible"
    function disableOtherCheckboxes(row) {
        var rendreDisponibleCheckbox = row.find('input[name="rendreDisponible"]');
        row.find('input[type="checkbox"]').not(rendreDisponibleCheckbox).prop('disabled', rendreDisponibleCheckbox.prop('checked'));
    }

    // Attache l'événement aux cases "droitRendredisponible" existantes
    $('#droitsTable input[name="droitRendredisponible"]').each(function() {
        disableOtherCheckboxes($(this).closest('tr'));
    });

    // Attache l'événement aux nouvelles lignes ajoutées
    $('#nouvelleClefAPI').click(function() {
        var table = $('#droitsTable');
        var newRow = $('<tr class="NouvelleClef">' +
            '<td>Nouvelle Clef</td>' +
            '<td><input type="checkbox" name="petiteConsultation"></td>' +
            '<td><input type="checkbox" name="consultationCalendrier"></td>' +
            '<td><input type="checkbox" name="rendreIndisponible"></td>' +
            '<td><input type="checkbox" name="rendreDisponible"></td>' +
            '</tr>');
        table.append(newRow);
        disableOtherCheckboxes(newRow); // Appel de la fonction pour désactiver les autres cases de la ligne
    });

    // Attache l'événement de changement pour désactiver les autres cases de la ligne
    $('#droitsTable').on('change', 'input[name="droitRendredisponible"]', function() {
        disableOtherCheckboxes($(this).closest('tr'));
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

document.getElementById('nouvelleClefAPI').addEventListener('click', function() {
    var table = document.getElementById('droitsTable');
    
    if (document.getElementById('nouvelleClefAPI').innerHTML == "Nouvelle Clef API") {
        var newRow = table.insertRow(table.rows.length);
        console.log("test") ;
        var ClefsAPI = newRow.insertCell(0);
        ClefsAPI.classList.add('nouvelleClef');
        var DroitsPetiteConsultation = newRow.insertCell(1);
        var DroitsConsultationCalendrier = newRow.insertCell(2);
        var DroitsRendreIndisponible = newRow.insertCell(3);
        var DroitsRendreDisponible = newRow.insertCell(4);

        ClefsAPI.innerHTML = "Nouvelle Clef originelle";
        DroitsPetiteConsultation.innerHTML = '<input type="checkbox" name="petiteConsultation" class="chkPetiteConsultation">';
        DroitsConsultationCalendrier.innerHTML = '<input type="checkbox" name="consultationCalendrier" class="chkConsultationCalendrier">';
        DroitsRendreIndisponible.innerHTML = '<input type="checkbox" name="rendreIndisponible" class="chkRendreIndisponible">';
        DroitsRendreDisponible.innerHTML = '<input type="checkbox" name="rendreDisponible" class="chkRendreDisponible">';

        document.getElementById('nouvelleClefAPI').innerHTML = "Générer Clef";
    } else {
        genererClef();
    }

    function genererClef() {
        // Générer un ID unique en utilisant une fonction JavaScript et l'insérer dans le champ de texte
        var nouvelId = uniqueId();
        $('#droitsTable .nouvelleClef:last').text(nouvelId);
        //document.getElementById('nouvelleClef').innerText = nouvelId;
    }

    // Fonction pour générer un ID unique en JavaScript
    function uniqueId() {
        // Date en millisecondes
        return Date.now();
    }
});
        
