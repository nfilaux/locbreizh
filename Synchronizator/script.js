//var derniereClefId = null;
let rowIndex = null; 

$(document).ready(function() {
    // Désactive toutes les cases sauf celles de la ligne sélectionnée
    $('#droitsTable').on('change', 'input[type="checkbox"]', function() {
        var row = $(this).closest('tr');
        rowIndex = row.index();

        if ($(this).prop('checked')) {
            // Case cochée alors on, désactivee les autres cases sauf celles de la ligne sélectionnée
            $('.chkPetiteConsultation, .chkConsultationCalendrier, .chkRendreIndisponible')
                .not(row.find('input[type="checkbox"]')).prop('disabled', true);

            // Utilisation de rowIndex pour récupérer l'index de la ligne sélectionnée
            console.log("Ligne sélectionnée : " + rowIndex);
        } else {
            // Case décochée alors on réactive toutes les cases
            $('.chkPetiteConsultation, .chkConsultationCalendrier, .chkRendreIndisponible')
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
                    var derniereClefId = parseInt($('#droitsTable tr:eq(' + rowIndex + ') td:eq(0)').text());
        
                    $.ajax({
                        type: 'POST',
                        url: 'DroitBack.php',
                        data: {
                            action: 'fonctionSauvegarder',
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
            var table = document.getElementById('droitsTable');
            
            if (document.getElementById('nouvelleClefAPI').innerHTML === "Nouvelle Clef API") {
                var newRow = table.insertRow(table.rows.length);
                var newId = table.rows.length;
                        
                var ClefsAPI = newRow.insertCell(0);
                ClefsAPI.classList.add('nouvelleClef');
                var DroitsPetiteConsultation = newRow.insertCell(1);
                var DroitsConsultationCalendrier = newRow.insertCell(2);
                var DroitsRendreIndisponible = newRow.insertCell(3);
        
                ClefsAPI.innerHTML = "Nouvelle Clef";
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
                $('#droitsTable .nouvelleClef:last').text(nouvelId);
                //document.getElementById('nouvelleClef').innerText = nouvelId;
            }
        
            // Fonction pour générer un ID unique en JavaScript
            function uniqueId() {
                // Date en millisecondes
                return Date.now();
            }
        });
        
