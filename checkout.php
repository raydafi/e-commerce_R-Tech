<?php 
session_start();

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart_items)) {
    header("Location: cart.php");
    exit;
}
if (!isset($_SESSION['username'])){
    header('Location: connexion.php');
    exit; 
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Finalisation de l'achat</title>
        <link rel="icon" type="image/png" href="img/RT.png"/>
        <style>
            /* Style global */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            h1 {
                text-align: center;
                font-size: 2rem;
                margin-top: 40px;
                color: #333;
            }

            /* Section de finalisation de l'achat */
            .finaliserachat {
                width: 100%;
                max-width: 700px;
                margin: 0 auto;
                padding: 20px;
                background-color: white;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                margin-top: 20px;
            }

            /* Texte des informations panier */
            .finaliserachat p {
                font-size: 1.1rem;
                color: #333;
                margin-bottom: 20px;
            }

            /* Formulaire */
            form {
                display: flex;
                flex-direction: column;
            }

            /* Champs de texte */
            input[type="text"],
            select {
                padding: 10px;
                font-size: 1rem;
                margin: 10px 0;
                border: 1px solid #ddd;
                border-radius: 5px;
            }

            input[type="text"]:focus,
            select:focus {
                border-color: #007bff;
                outline: none;
            }

            /* Bouton finaliser */
            button {
                padding: 10px 20px;
                font-size: 1.1rem;
                cursor: pointer;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                transition: all 0.3s ease;
                margin-top: 20px;
            }

            button:hover {
                background-color: #0056b3;
                transform: scale(1.05);
            }

            button:focus {
                outline: none;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .finaliserachat {
                    padding: 15px;
                }

                button {
                    width: 100%;
                }

                h1 {
                    font-size: 1.5rem;
                }
            }

            /* Champ bancaire caché */
            .bank-details {
                display: none;
                margin-top: 15px;
            }

            .bank-details input {
                margin-top: 10px;
            }
        </style>
 <script>
    function togglePaymentFields() {
        var paymentMethod = document.getElementById("payment").value;
        var bankDetails = document.getElementById("bank-details");
        var paypalMessage = document.getElementById("paypal-message");
        var form = document.getElementById("payment-form");

        if (paymentMethod === "credit_cart") {
            bankDetails.style.display = "block";
            paypalMessage.style.display = "none";
            // Empêcher la redirection PayPal si la carte est sélectionnée
            form.action = "confirmation.php"; // Action vers le script de traitement des informations bancaires
        } else if (paymentMethod === "paypal") {
            bankDetails.style.display = "none";
            paypalMessage.style.display = "block";
            // Rediriger vers PayPal si PayPal est sélectionné
            form.action = "https://www.paypal.com/cgi-bin/webscr"; // URL pour le paiement PayPal
            form.target = "_blank"; // Ouvre PayPal dans un nouvel onglet
        }
    }

    // Initialiser les bons comportements lors du chargement de la page
    window.onload = function() {
        togglePaymentFields();
    };
</script>
    </head>
    <body>
        <?php include 'navbar.php' ?>
        <br><br><br>
        <h1>Finalisation de l'achat</h1>
        <div class="finaliserachat">
        <?php if (!empty($cart_items)): ?>
            <?php if (count($cart_items) > 1): ?>
            <p>Vous avez <?php echo count($cart_items); ?> articles dans votre panier.</p>

            <form method="post" action="confirmation.php">
                <label for="address">Adresse de livraison : </label><br>
                <input type="text" id="address" name="address" required>

                <label for="payment">Méthode de paiement : </label><br>
                <select id="payment" name="payment_method" onchange="togglePaymentFields()">
                    <option value="credit_cart">Carte de crédit</option>
                    <option value="paypal">PayPal</option>
                </select><br>

                <div id="bank-details" class="bank-details">
                    <label for="card-number">Numéro de carte :</label><br>
                    <input type="text" id="card-number" name="card_number" required>
                    <br>
                    <label for="expiry-date">Date d'expiration :</label><br>
                    <input type="text" id="expiry-date" name="expiry_date" required>
                    <br>
                    <label for="cvv">Code CVV :</label><br>
                    <input type="text" id="cvv" name="cvv" required>
                </div>

                <div id="paypal-message" style="display: none;">
                    <p>Vous serez redirigé vers PayPal pour finaliser votre paiement.</p>
                </div>

                <button type="submit">Finaliser l'achat</button>
            </form>
            <?php elseif (count($cart_items) == 1): ?>
            <p>Vous avez <?php echo count($cart_items); ?> article dans votre panier.</p>

            <form method="post" action="confirmation.php">
                <label for="address">Adresse de livraison : </label><br>
                <input type="text" id="address" name="address" required>

                <label for="payment">Méthode de paiement : </label><br>
                <select id="payment" name="payment_method" onchange="togglePaymentFields()">
                    <option value="credit_cart">Carte de crédit</option>
                    <option value="paypal">PayPal</option>
                </select><br>

                <div id="bank-details" class="bank-details">
                    <label for="card-number">Numéro de carte :</label><br>
                    <input type="text" id="card-number" name="card_number" required>

                    <label for="expiry-date">Date d'expiration :</label><br>
                    <input type="text" id="expiry-date" name="expiry_date" required>

                    <label for="cvv">Code CVV :</label><br>
                    <input type="text" id="cvv" name="cvv" required>
                </div>

                <div id="paypal-message" style="display: none;">
                    <p>Vous serez redirigé vers PayPal pour finaliser votre paiement.</p>
                </div>

                <button type="submit">Finaliser l'achat</button>
            </form>
        </div>
        <?php else: ?>
            <p>Votre panier est vide. </p>
        <?php endif; ?>
        <?php endif; ?>
    </body>
</html>
