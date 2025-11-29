<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            color: white;
            text-align: center;
            font-family: Arial, sans-serif;
            padding: 10px 20px;
            z-index: 1000;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }

        .footer a {
            color: white;
            text-decoration: none;
            padding: 0 10px;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Add some margin to the body to avoid overlap with the footer */
        body {
            margin-bottom: 50px; /* Adjust based on footer height */
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .footer p {
                font-size: 12px;
            }

            .footer a {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

    <!-- Content of the page goes here -->

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> R-Tech. Tous droits réservés.</p>
        <p>
            <a href="privacy.php">Politique de confidentialité</a> |
            <a href="terms.php">Conditions d'utilisation</a>
        </p>
    </div>

</body>
</html>
