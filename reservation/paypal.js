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
            alert("Transaction réussi " + details.payer.name.given_name);
        });
    },
    onError: function (err) {
        console.error('Payement Error:', err);
        alert('Paiement echoué!');
    }
}).render('#paypal-button-container');