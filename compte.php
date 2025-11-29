<?php
session_start();
include 'bdd.php';

if (!isset($_SESSION['username'])) {
    header("Location: connexion.php");
    exit();
}

$username = $_SESSION['username'];

 try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname",$dbusername,$dbpassword);
    $conn-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Utilisateur introuvable.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $newUsername = htmlspecialchars(trim($_POST['username']));
        $newEmail = htmlspecialchars(trim($_POST['email']));
        $newPassword = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

        try {
            $sql = "UPDATE users SET username = :username, email = :email" . ($newPassword ? ", password = :password" : "") . " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $newUsername);
            $stmt->bindParam(':email', $newEmail);
            if ($newPassword) {
                $stmt->bindParam(':password', $newPassword);
            }
            $stmt->bindParam(':id', $user['id']);
            $stmt->execute();

            $_SESSION['username'] = $newUsername;
            $_SESSION['email'] = $newEmail;

            echo "<p style='color: green;'>Information mises à jour avec succès.</p>";
        } catch (PDOException $e) {
            echo "Erreur :" / $e->getMessage();
        }
    }
 } catch (PDOException $e) {
    echo "Erreur :" . $e->getMessage();
 }

 $conn = null;
 ?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Mon Profil</title>
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
        }

        .success {
            background-color: #d4edda;
            color: #155724;
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
        <h2>Mon Profil</h2>
        <form action="compte.php" method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email :</label>
            <input type="text" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

            <label for="password">Nouveau mot de passe :</label>
            <input type="password" id="passord" name="password">

            <button type="submit" id="modifbtn">Mettre à jour</button>
</form>
<a class="register-link" href="index.php" style="margin-top:10px;text-align:center">Revenir à l'accueil</a>
</div>
    <?php include 'footer.php';?>
</body>
</html>