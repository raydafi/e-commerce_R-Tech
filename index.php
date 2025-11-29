<?php session_start();?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - R-Tech</title>
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

/* Section About */
.about, .eco-responsability, .product-highlights, .customer-testimonials {
    text-align: center;
    padding: 50px 20px;
    background-color: white;
    margin: 20px 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.about h2, .eco-responsability h2, .product-highlights h2, .customer-testimonials h2 {
    font-size: 28px;
    margin-bottom: 20px;
}

.about p, .eco-responsability p {
    font-size: 18px;
    line-height: 1.6;
}

/* Cards pour produits */
.product-cards {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}

.product-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: 30%;
    margin: 10px;
    text-align: center;
}

.product-card img {
    max-width: 100%;
    border-radius: 5px;
    margin-bottom: 15px;
}

.product-card h3 {
    font-size: 22px;
    margin-bottom: 10px;
}

.product-card p {
    font-size: 16px;
    margin-bottom: 20px;
}

.btn {
    background-color: #333;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
}

.btn:hover {
    background-color: #555;
}

/* Section témoignages */
.testimonial {
    font-size: 18px;
    margin: 20px auto;
    max-width: 600px;
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 5px;
}

    </style>
</head>
<body>

<?php include 'navbar.php';?>

<main>
    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenue chez R-Tech</h1>
            <p>Des produits Apple de qualité à des prix réduits, tout en contribuant à la protection de l'environnement.</p>
            <a href="produits.php" class="cta-btn" style="background-color: #4169E1">Découvrez nos produits</a>
        </div>
    </section>

            <section class="product-highlights">
        <h2>Nos Produits</h2>
        <div class="product-cards">
            <div class="product-card">
                <img src="img/iphone15pro.jpg" height="150px" alt="iPhone Reconditionné">
                <h3>iPhone Reconditionné</h3>
                <p>Profitez de la performance d'un iPhone comme neuf, à prix réduit.</p>
                <a href="produits.php" class="btn">Voir les produits</a>
            </div>
            <div class="product-card">
                <img src="img/mbp20.jpg" height="150px" alt="MacBook Reconditionné">
                <h3>MacBook Reconditionné</h3>
                <p>Des MacBook puissants et abordables, parfaits pour toutes vos tâches.</p>
                <a href="produits.php" class="btn">Voir les produits</a>
            </div>
            <div class="product-card">
                <img src="img/watch.jpg" height="150px" alt="Apple Watch Reconditionnée">
                <h3>Apple Watch Reconditionnée</h3>
                <p>Suivez votre activité et votre santé avec une Apple Watch comme neuve.</p>
                <a href="produits.php" class="btn">Voir les produits</a>
            </div>
            <div class="product-card">
                <img src="img/ipad.png" height="150px" alt="iPad Reconditionné">
                <h3>iPad reconditionné</h3>
                <p>Découvrez la puissance d’un iPad quasiment neuf, à prix avantageux.</p>
                <a href="produits.php" class="btn">Voir les produits</a>
            </div>
        </div>
    </section>

    <section class="about">
        <h2>Qui sommes-nous ?</h2>
        <p>Chez R-Tech, nous nous engageons à vous offrir une expérience unique dans l'achat de produits Apple reconditionnés. Notre mission est simple : vous permettre de profiter des meilleures technologies Apple tout en réduisant l'empreinte écologique. Tous nos produits sont soigneusement testés et remis à neuf par nos experts, garantissant une qualité équivalente aux produits neufs, mais à des prix bien plus abordables.</p>

        <p>En choisissant d'acheter des produits reconditionnés, vous participez activement à la réduction des déchets électroniques et à la lutte contre l'obsolescence programmée. Vous donnez une nouvelle vie à des appareils de qualité, contribuant ainsi à la préservation de notre planète. C’est un choix qui fait sens pour vous, pour les générations futures, et pour la planète.</p>
    </section>

    <section class="eco-responsability">
        <h2>Engagés pour l'environnement</h2>
        <p>Nous savons que l'achat de produits Apple peut avoir un impact environnemental important. C’est pourquoi nous avons fait le choix de proposer uniquement des produits reconditionnés, en parfait état de fonctionnement. Chaque appareil que nous remettons à neuf est inspecté, testé et mis à jour pour s’assurer qu’il respecte les standards les plus élevés.</p>

        <p>Le reconditionnement permet de réduire le gaspillage des ressources naturelles et de limiter l'extraction des matériaux pour la fabrication de nouveaux produits. En achetant sur notre plateforme, vous contribuez à la diminution des déchets électroniques et à la préservation des ressources, tout en ayant accès à des produits performants et à un prix accessible.</p>

        <p>Nous nous engageons à offrir à nos clients une transparence totale sur l'état de nos produits. Chaque appareil reconditionné est accompagné d'un rapport détaillé sur son historique et son état avant la vente, vous offrant ainsi une tranquillité d’esprit et la certitude de faire un choix éco-responsable.</p>
    </section>

    <section class="customer-testimonials">
        <h2>Avis de nos clients</h2>
        <div class="testimonial">
            <p>"Je suis tellement content d'avoir acheté un iPhone reconditionné chez R-Tech. Non seulement l'appareil est comme neuf, mais en plus, je contribue à l'environnement !" – <strong>Julien, Paris</strong></p>
        </div>
        <div class="testimonial">
            <p>"Le processus d'achat était super facile, et je suis ravi de ma nouvelle Apple Watch ! Je recommande vivement cette plateforme !" – <strong>Claire, Lyon</strong></p>
        </div>
    </section>
</main>

<?php include 'footer.php';?>

</body>
</html>
