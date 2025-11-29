<?php 
include 'bdd.php';

// Définir le fuseau horaire sur Paris
date_default_timezone_set('Europe/Paris');

// Vérifier si la requête est une requête POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer et nettoyer l'email envoyé via le formulaire
    $email = htmlspecialchars(trim($_POST['email']));

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Suppression des tokens expirés
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE expires_at < NOW() - INTERVAL 1 MINUTE");
        $stmt->execute();
        // Vérifier si l'email existe dans la table 'users'
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Générer un token unique pour la réinitialisation du mot de passe
            $token = bin2hex(random_bytes(32));

            // Définir une expiration de 15min pour ce token
            $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // Enregistrer le token dans la table 'password_resets' avec l'e-mail, l'expiration et la date de création
             $conn->prepare("INSERT INTO password_resets (email, token, expires_at, created_at) VALUES (:email, :token, :expires_at, NOW())")
                  ->execute(['email' => $email, 'token' => $token, 'expires_at' => $expiry]);

                  // Créer un lien de réinitialisation basé sur le domaine actuel
                  $resetLink = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/reset_password.php?token=$token";

                  // Prépare le sujet de l'email avec un encodage UTF-8 pour les caractères spéciaux 
                  $subject = "=?UTF-8?B?" . base64_encode("Réinitialisation de votre mot de passe") . "?=";

                  // Prépare le contenu de l'email en HTML
                  $message = "
                  <html>
                  <head>
                    <title>Réinitialisation de votre mot de passe</title>
                    </head>
                    <body>
                        <p style='font-family: Arial;'>Bonjour,</p>
                        <p style='font-family: Arial;'>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
                        <p style='font-family: Arial;'><a href='$resetLink' style='color: blue; text-decoration: underline;'>Réinitialiser mon mot de passe</a></p>
                        <p style='font-family: Arial;'>Ce lien expirera dans 15 minutes.</p>
                        <p style='font-family: Arial;'>Si vous n'avez pas demandé cette réinitialisation, ignorez cet e-mail.</p>
                        <p style='font-family: Arial;'>Cordialement,</p>
                        <p style='font-family: Arial;'>L'équipe R-Tech</p>
                        <img src='https://i.ibb.co/NgsHLzzB/RT.png' style='width:50px;height:50px;'>
                    </body>
                    </html>
                    ";

                    // Configurez les en-têtes de l'e-mail pour supporter HTML et UFT-8
                    $headers = "From: no-reply@" .$_SERVER['HTTP_HOST'] . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                    $headers .= "Content-Transfer-Encoding: 8bit\r\n";

                    // Envoyer l'e-mail et afficher un message approprié
                    if (mail($email, $subject, $message, $headers)) {
                        echo "<p style='color: green;'>Un lien de réinitialisation a été envoyé à votre adresse e-mail. </p>";
                    } else {
                        echo "<p style='color : red;'> Erreur lors de l'envoi de l'e-mail.</p>";
                    } 
        } else {
            // Si aucun compte associé à cet e-mail n'est trouvé
            echo "<p style='color : red;'>Aucun compte trouvé pour cet email.</p>";
        }
    } catch (PDOException $e) {
        // Gérer les erreurs de connexion ou d'éxécution SQL
        echo "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <link rel="icon" type="image/png" href="img/RT.png"/>
    <style>
            body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f7f7f7;
            }

            /* Conteneur principal */
            .login-container {
                max-width: 400px;
                margin: 100px auto;
                background-color: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            /* Titre */
            .form-container h2 {
                text-align: center;
                margin-bottom: 20px;
                font-size: 24px;
                color: #333;
            }

            /* Formulaire */
            .form-container form {
                display: flex;
                flex-direction: column;
            }

            .form-container label {
                margin-bottom: 5px;
                font-size: 14px;
                color: #555;
            }

            .form-container input {
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 16px;
                transition: border-color 0.3s ease;
            }

            .form-container input:focus {
                border-color: #007BFF;
                outline: none;
            }

            .form-container button {
                padding: 10px;
                background-color: #007BFF;
                color: white;
                font-size: 16px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .form-container button:hover {
                background-color: #0056b3;
            }

            /* Messages d'erreur ou succès */
            .error, .success {
                font-size: 14px;
                padding: 10px;
                margin-bottom: 15px;
                text-align: center;
                border-radius: 5px;
            }

            .error {
                background-color: #f8d7da;
                color: #721c24;
                position: fixed;
                top: 100px;
                left: 40px;
            }

            .success {
                background-color: #d4edda;
                color: #155724;
                position: fixed;
                top: 100px;
                left: 40px;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .login-container {
                    width: 90%;
                    padding: 15px;
                }
            }
</style>
</head>
<body>
    <?php include 'navbar.php';?>

    <div class="login-container">
        <div class="form-container">
            <h2>Réinitialisation du mot de passe</h2>
            <form action="reset_password_request.php" method="POST">
                <label for="email">Entrez votre adresse e-mail :</label>
                <input  type="email" id="email" name="email" required>
                <button type="submit">Envoyer</button>
                </form>
        </div>
    </div>

    <?php include 'footer.php';?>
</body>
</html>