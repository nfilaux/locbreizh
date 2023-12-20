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

            // Split the URL at '?' to separate the query string
            var urlParts = currentURL.split("?");
            var queryString = (urlParts.length > 1) ? urlParts[1] : ""; // Get the query string part

            // Parse the query string to get parameters
            var params = new URLSearchParams(queryString);

            // Check if "devis" parameter exists in the URL
            if (params.has("devis")) {
                // Retrieve the value of the "devis" parameter
                var paramValue = params.get("devis");
                // Redirect or perform actions using this value
                window.location.href = 'enregistrer_reservation.php?devis=' + paramValue;
            }
        });
    },
    onError: function (err) {
        console.error('Payement Error:', err);
        alert('Paiement echoué!');
    }
}).render('#paypal-button-container');