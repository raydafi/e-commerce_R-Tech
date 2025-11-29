<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charte de confidentialité</title>
    <link rel="icon" type="image/png" href="img/RT.png"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 15px;
            text-align: center;
        }
        section {
            padding: 20px;
            margin: 20px;
            background-color: #fff;
            border-radius: 8px;
        }
        h1, h2 {
            color: #333;
        }
        p {
            margin-bottom: 15px;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            margin-bottom: 10px;
        }
        footer {
            text-align: center;
            background-color: #333;
            color: #fff;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        #back-to-top {
            position: fixed;
            bottom: 70px;
            right: 30px;
            display: none;
            background-color: #333;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include 'navbar.php';?>
    
    <section>
        <h2>Introduction</h2>
        <p>Cette charte de confidentialité explique comment nous recueillons, utilisons et protégeons vos informations personnelles lorsque vous utilisez notre site web. En accédant à notre site, vous acceptez les pratiques décrites dans cette charte.</p>
    </section>

    <section>
        <h2>1. Collecte d'informations personnelles</h2>
        <p>Nous collectons les informations personnelles que vous nous fournissez directement lorsque vous vous inscrivez, remplissez des formulaires ou interagissez avec notre site. Ces informations peuvent inclure :</p>
        <ul>
            <li>Nom</li>
            <li>Adresse e-mail</li>
            <li>Adresse postale</li>
            <li>Numéro de téléphone</li>
            <li>Informations de paiement</li>
        </ul>
    </section>

    <section>
        <h2>2. Utilisation des informations</h2>
        <p>Les informations que nous collectons peuvent être utilisées pour :</p>
        <ul>
            <li>Fournir nos services et produits</li>
            <li>Améliorer notre site web et nos services</li>
            <li>Vous contacter pour des mises à jour, promotions et autres informations pertinentes</li>
            <li>Traiter vos paiements</li>
        </ul>
    </section>

    <section>
        <h2>3. Protection des données</h2>
        <p>Nous mettons en place des mesures de sécurité pour protéger vos informations personnelles contre tout accès non autorisé, utilisation ou divulgation. Cependant, aucune méthode de transmission ou de stockage de données n'est totalement sécurisée, et nous ne pouvons garantir la sécurité absolue de vos informations.</p>
    </section>

    <section>
        <h2>4. Partage des informations</h2>
        <p>Nous ne vendons ni ne partageons vos informations personnelles avec des tiers à des fins commerciales, sauf dans les cas suivants :</p>
        <ul>
            <li>Avec des prestataires de services qui nous aident à fournir nos services</li>
            <li>En cas de demande légale ou pour se conformer à la loi</li>
        </ul>
    </section>

    <section>
        <h2>5. Vos droits</h2>
        <p>Vous avez le droit de :</p>
        <ul>
            <li>Accéder à vos informations personnelles que nous détenons</li>
            <li>Corriger ou mettre à jour vos informations personnelles</li>
            <li>Demander la suppression de vos informations personnelles</li>
            <li>Vous opposer à l'utilisation de vos informations à certaines fins</li>
        </ul>
    </section>

    <section>
        <h2>6. Modifications de cette charte</h2>
        <p>Nous nous réservons le droit de modifier cette charte de confidentialité à tout moment. Les modifications seront publiées sur cette page, et nous vous encouragerons à la consulter régulièrement.</p>
    </section>

    <section>
        <h2>7. Contact</h2>
        <p>Si vous avez des questions concernant cette charte de confidentialité ou si vous souhaitez exercer vos droits, vous pouvez nous contacter à :</p>
        <p>Email : <a href="mailto:rayandafi28@gmail.com">rayandafi28@gmail.com</a></p>
    </section>

    <div id="back-to-top">↑</div>
    <?php include 'footer.php';?>
    <script>
        // Show or hide the back-to-top button
        window.onscroll = function() {
            var backToTop = document.getElementById('back-to-top');
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        };

        // Scroll to the top when the button is clicked
        document.getElementById('back-to-top').onclick = function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
    </script>
</body>
</html>
