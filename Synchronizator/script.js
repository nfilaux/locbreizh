//var derniereClefId = null;
let rowIndex = null; // Déclarer rowIndex ici

$(document).ready(function() {
    // Désactiver toutes les cases sauf celles de la ligne sélectionnée
    $('#droitsTable').on('change', 'input[type="checkbox"]', function() {
        var row = $(this).closest('tr');
        rowIndex = row.index(); // Récupérer l'index de la ligne

        if ($(this).prop('checked')) {
            // Case cochée, désactiver les autres cases sauf celles de la ligne sélectionnée
            $('.chkGrandeConsultation, .chkPetiteConsultation, .chkConsultationCalendrier, .chkRendreIndisponible')
                .not(row.find('input[type="checkbox"]')).prop('disabled', true);

            // Utilisez rowIndex comme nécessaire
            console.log("Ligne sélectionnée : " + rowIndex);
        } else {
            // Case décochée, réactiver toutes les cases
            $('.chkGrandeConsultation, .chkPetiteConsultation, .chkConsultationCalendrier, .chkRendreIndisponible')
                .prop('disabled', false);
        }
    });
});


        $(document).ready(function() {
            $('#boutonSauvegarder').on('click', function() {
                if ($('.nouvelleClef').text() != "Nouvelle Clef") {
                    alert('La nouvelle clef a bien été enregistrée.');

                    document.getElementById('nouvelleClefAPI').innerHTML = "Nouvelle Clef API";

                    alert(rowIndex);
        
                    // Récupérer les valeurs des cases à cocher
                    var grandeConsultation = $('#droitsTable tr:eq(' + rowIndex + ') td:eq(1) input').is(':checked');
                    var petiteConsultation = $('#droitsTable tr:eq(' + rowIndex + ') td:eq(2) input').is(':checked');
                    var consultationCalendrier = $('#droitsTable tr:eq(' + rowIndex + ') td:eq(3) input').is(':checked');
                    var rendreIndisponible = $('#droitsTable tr:eq(' + rowIndex + ') td:eq(4) input').is(':checked');
                    var derniereClefId = parseInt($('#droitsTable tr:eq(' + rowIndex + ') td:eq(0)').text());

                    alert(derniereClefId);
        
                    $.ajax({
                        type: 'POST',
                        url: 'DroitBack.php',
                        data: {
                            action: 'fonctionSauvegarder',
                            grandeConsultation: grandeConsultation,
                            petiteConsultation: petiteConsultation,
                            consultationCalendrier: consultationCalendrier,
                            rendreIndisponible: rendreIndisponible,
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

    if (document.getElementById('nouvelleClefAPI').innerHTML === "Nouvelle Clef API") {

                
        var table = document.getElementById('droitsTable');
        var newRow = table.insertRow(table.rows.length);
        var newId = table.rows.length;

                
        var ClefsAPI = newRow.insertCell(0);
        ClefsAPI.classList.add('nouvelleClef');
        var DroitsGrandeConsultation = newRow.insertCell(1);
        var DroitsPetiteConsultation = newRow.insertCell(2);
        var DroitsConsultationCalendrier = newRow.insertCell(3);
        var DroitsRendreIndisponible = newRow.insertCell(4);

        ClefsAPI.innerHTML = "Nouvelle Clef";
        DroitsGrandeConsultation.innerHTML = '<input type="checkbox" name="grandeConsultation" class="chkGrandeConsultation">';
        DroitsPetiteConsultation.innerHTML = '<input type="checkbox" name="petiteConsultation" class="chkPetiteConsultation">';
        DroitsConsultationCalendrier.innerHTML = '<input type="checkbox" name="consultationCalendrier" class="chkConsultationCalendrier">';
        DroitsRendreIndisponible.innerHTML = '<input type="checkbox" name="rendreIndisponible" class="chkRendreIndisponible">';

        document.getElementById('nouvelleClefAPI').innerHTML = "Générer Clef";
    } else {
        genererClef();
    }

    function genererClef() {
        // Générer un ID unique en utilisant une fonction JavaScript et l'insérer dans le champ de texte
        var nouvelId = uniqueId();
        //derniereClefId = nouvelId;
        $('#droitsTable .nouvelleClef:last').text(nouvelId);
        //document.getElementById('nouvelleClef').innerText = nouvelId;
    }

    // Fonction pour générer un ID unique en JavaScript
    function uniqueId() {
        
        // Date en millisecondes
        return Date.now();
    }

});

/*$(document).ready(function() {
    // Désactiver toutes les cases sauf celles de la ligne sélectionnée
    $('#droitsTable').on('change', 'input[type="checkbox"]', function() {
        var row = $(this).closest('tr');
        
        if ($(this).prop('checked')) {
            // Case cochée, désactiver les autres cases sauf celles de la ligne sélectionnée
            $('.chkGrandeConsultation, .chkPetiteConsultation, .chkConsultationCalendrier, .chkRendreIndisponible')
                .not(row.find('input[type="checkbox"]')).prop('disabled', true);
        } else {
            // Case décochée, réactiver toutes les cases
            $('.chkGrandeConsultation, .chkPetiteConsultation, .chkConsultationCalendrier, .chkRendreIndisponible')
                .prop('disabled', false);
        }
    });
});*/


