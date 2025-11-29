<?php session_start();?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'achat - R-Tech</title>
    <link rel="icon" type="image/png" href="img/RT.png"/>
    <style>
        /* Style général */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

/* Navbar */
.navbar {
    background-color: black;
    color: white;
    padding: 15px;
    text-align: center;
    font-family: Arial, sans-serif;
}

.navbar a {
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    margin: 0 10px;
}

/* Section Hero */
.hero {
    background-image: url('img/hero.jpg');
    background-size: cover;
    background-position: center;
    color: white;
    text-align: center;
    padding: 100px 20px;
}

.hero-content h1 {
    font-size: 48px;
    margin-bottom: 20px;
}

.hero-content p {
    font-size: 20px;
    margin-bottom: 30px;
}

.cta-btn {
    background-color: #333;
    color: white;
    padding: 15px 30px;
    text-decoration: none;
    font-size: 18px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.cta-btn:hover {
    background-color: #555;
}

/* Confirmation Section */
.confirmation {
    text-align: center;
    padding: 50px 20px;
    background-color: white;
    margin: 20px 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.confirmation h2 {
    font-size: 28px;
    margin-bottom: 20px;
}

.confirmation p {
    font-size: 18px;
    margin-bottom: 30px;
}

.btn-back {
    background-color: #4169E1;
    color: white;
    padding: 15px 30px;
    text-decoration: none;
    font-size: 18px;
    border-radius: 5px;
}

.btn-back:hover {
    background-color: #333;
}

/* Footer */
footer {
    background-color: black;
    color: white;
    text-align: center;
    padding: 15px;
    font-family: Arial, sans-serif;
}
    </style>
</head>
<body>

<?php include 'navbar.php';?>

<main>
    <section class="confirmation">
        <h2>Merci pour votre achat !</h2>
        <p>Votre commande a été validée avec succès. Nous préparons votre produit pour l'expédition.</p>
        <p>Vous recevrez un email de confirmation dans les prochaines minutes avec les détails de votre achat.</p>
        <a href="index.php" class="btn-back">Retour à l'accueil</a>
    </section>
</main>

<?php include 'footer.php';?>

</body>
</html>
