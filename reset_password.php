<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'bdd.php';

    // Récupérer et nettoyer les données du formulaire
    $token = htmlspecialchars(trim($_POST['token'])); // Le token unique pour réinitialisation
    $new_password = trim($_POST['new_password']); // Nouveau mot de passe
    $confirm_password = trim($_POST['confirm_password']); // Confirmation du mot de passe

    // Validation des mots de passe
    if ($new_password !== $confirm_password) {
        echo "<p style='color: red;'>Le mot de passe ne correspondent pas.</p>";
        exit; // Arrêter l'éxécution si le mdp est en désaccord
    }
    if (strlen($new_password) < 8) {
        echo "<p style='color: red;'>Le mot de passe doit contenir au moins 8 caractères.</p>";
        exit; // Arrêter l'execution si le mdp est trop court
    }

    try {
        
        // Connexion à la base de données avec PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier que le token est valide et non expiré
        $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()");
        $stmt->bindParam(':token', $token); // Lier le paramètre :token
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Si le token est valide, récuperer l'adresse e-mail associée
            $email = $stmt->fetchColumn();

            // Hacher le nouveau mdp pour le stockage
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Maj le mdp de l'utilisateur dans la bdd
            $conn->prepare("UPDATE users SET password = :password WHERE email = :email")
                 ->execute(['password' => $hashed_password, 'email' => $email]);

            // Supprimer le token utilisé pour éviter sa réutilisation
            $conn->prepare("DELETE FROM password_resets WHERE token = :token")
                 ->execute(['token' => $token]);

            // Rediriger l'utilisateur vers la page de connexion après succès
            header('Location: connexion.php');
            exit;
        } else {
            // Message d'erreur si le token est invalide ou expiré
            echo "<p style = 'color:red;'>Le lien est invalide ou expiré.</p>";
        }
    } catch (PDOException $e) {
        // Afficher une erreur en cas de problème avec la bdd
        echo "Erreur : " . $e->getMessage();
    }
} else if (isset($_GET['token'])) {
    // Si le token est passé dans l'URL, le récupérer et le sécuriser 
    $token = htmlspecialchars($_GET['token']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
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

        /* Formulaire */
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

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
            <form action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">
                <label for="new_password">Nouveau mot de passe :</label>
                <input type="password" id="new_password" name="new_password" required>
                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <button type="submit">Réinitialiser</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php';?>
</body>
</html>