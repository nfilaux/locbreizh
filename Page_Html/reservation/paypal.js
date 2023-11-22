paypal.Buttons({
    createOrder: function (data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: '0.01'
                }
            }]
        });
    },
    onApprove: function (data, actions) {
        return actions.order.capture().then(function (details) {
            var currentURL = window.location.href;
            var urlParts = currentURL.split("?");
            var queryString = (urlParts.length > 1) ? urlParts[1] : ""; // Récupère la partie de la requête GET
            var params = new URLSearchParams(queryString);

            // Vérifie si un paramètre spécifique est présent dans la requête GET
            if (params.has("devis")) {
                var paramValue = params.get("devis");
                var lastCharacter = paramValue.slice(-1);
            }
            window.location.href = 'enregistrer_reservation.php?devis=' + lastCharacter;
        });
    },
    onError: function (err) {
        console.error('Payement Error:', err);
        alert('Paiement echoué!');
    }
}).render('#paypal-button-container');