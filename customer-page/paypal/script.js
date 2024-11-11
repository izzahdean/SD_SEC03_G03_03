const paypal_sdk_url = "https://www.paypal.com/sdk/js";
const client_id = "AZ0hXyRIjA08ZtVkluPK7VtF2N3lM-WIheD5A_YQtskgVBAGP9QCAhdVofs3tBFPO-C-DRgz2ViB_ST8";
const currency = "USD";
const intent = "capture";

// Function to load PayPal SDK dynamically
let url_to_head = (url) => {
    return new Promise(function(resolve, reject) {
        let script = document.createElement('script');
        script.src = url;
        script.onload = function() {
            resolve();
        };
        script.onerror = function() {
            reject('Error loading PayPal SDK script.');
        };
        document.head.appendChild(script);
    });
};

// Load PayPal SDK with proper parameters
url_to_head(`${paypal_sdk_url}?client-id=${client_id}&enable-funding=venmo&currency=${currency}&intent=${intent}`)
.then(() => {
    // Hide loading spinner and show content
    document.getElementById("loading").classList.add("hide");
    document.getElementById("content").classList.remove("hide");

    let alerts = document.getElementById("alerts");

    // Render PayPal button
    paypal.Buttons({
        style: {
            shape: 'rect',
            color: 'gold',
            layout: 'vertical',
            label: 'paypal'
        },

        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: { value: '100.00' } // Set transaction amount
                }]
            });
        },

        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alerts.innerHTML = `<div class='ms-alert ms-action'>Thank you ${details.payer.name.given_name} for your payment of $100.00!</div>`;
            });
        },

        onCancel: function(data) {
            alerts.innerHTML = `<div class="ms-alert ms-action2 ms-small"><span class="ms-close"></span><p>Order cancelled!</p></div>`;
        },

        onError: function(err) {
            console.error("PayPal Button Error:", err);
            alerts.innerHTML = `<div class="ms-alert ms-action2 ms-small"><span class="ms-close"></span><p>An error occurred!</p></div>`;
        }
    }).render('#paypal-button-container'); // Render PayPal button into the specified container
})
.catch((error) => {
    console.error("Error loading PayPal SDK:", error);
});
