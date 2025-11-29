<?php
session_start();
include 'bdd.php';

if (isset($_SESSION['username'])){
    header('Location: index.php');
    exit; 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérification du CAPTCHA
    if ($_POST['captcha'] != $_SESSION['captcha']) {
        echo "<p class='error'>Captcha invalide, veuillez réessayer.</p>";
    } else {
        // Si le CAPTCHA est valide, procéder à la vérification des identifiants
        $email = htmlspecialchars(trim($_POST['email']));
        $password = htmlspecialchars(trim($_POST['password']));

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user['password'])) {
                    $_SESSION['username'] = $user['username'];
                    echo "<p style='color: green;'>Connexion réussie !</p>";
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<p class='error'>Mot de passe incorrect.</p>";
                }
            } else {
                echo "<p class='error'>Email non trouvé.</p>";
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }

        $conn = null;
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="icon" type="image/png" href="img/RT.png"/>
    <style>
        /* Styles globaux */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        /* Formulaire de connexion */
        .signup-container {
            max-width: 400px;
            margin: 100px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .signup-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .signup-container form {
            display: flex;
            flex-direction: column;
        }

        .signup-container label {
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }

        .signup-container input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .signup-container input:focus {
            border-color: #007BFF;
            outline: none;
        }

        .signup-container button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .signup-container button:hover {
            background-color: #0056b3;
        }

        .signup-container p {
            text-align: center;
            font-size: 14px;
            color: #555;
        }

        .register-link {
            color: #007BFF;
            text-decoration: none;
        }

        .register-link:hover {
            text-decoration: underline;
        }

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
            position:fixed;
            top:100px;
            left:40px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            position:fixed;
            top:100px;
            left:40px;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .signup-container {
                padding: 15px;
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <?php include 'navbar.php';?>
    <div class="signup-container">
        <h2>Connexion</h2>

        <form action="connexion.php" method="POST">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <label for="captcha">Captcha :</label>
            <img src="captcha/captcha.php" alt="Captcha Image">
            <input type="text" name="captcha" placeholder="Entrez le captcha ici." required>
            <button type="submit">Se connecter</button>
        </form>
        <a href="reset_password_request.php" class="forgot-password-link">Mot de passe oublié?</a>
        <p>Pas encore inscrit ? <a href="inscription.php" class="register-link">Inscrivez-vous ici !</a></p>
        <p><a href="index.php" class="register-link">Accéder en tant qu'invité</a></p>
    </div>
    <?php include 'footer.php';?>
</body>
</html>
