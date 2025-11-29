<?php 
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : []; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'bdd.php';

$favoris_exist = false;

if (isset($_SESSION['username'])) {
    try {
        if (!isset($pdo)) {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['username']]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $favoris_exist = true;
        }
    } catch (PDOException $e) {
        error_log("Erreur : " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Styles globaux */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Navbar fixe */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 10px 20px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        /* Logo */
        .navbar-logo {
            height: 60px;
        }

        /* Conteneur des liens de la navbar */
        .navbar-right {
            display: flex;
            align-items: center;
            margin-right: 50px;
        }

        /* Liens de la navbar */
        .navbar-right a {
            margin-right: 15px;
            color: black;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center; /* Alignement des éléments du panier */
        }

        .navbar-right a:hover {
            color: #333;
        }

        /* Menu mobile - hamburger */
        .navbar-toggle {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            height: 25px;
            width: 30px;
            cursor: pointer;
        }

        .navbar-toggle div {
            background-color: white;
            height: 4px;
            width: 100%;
            border-radius: 5px;
        }

        /* Menu mobile responsive */
        @media (max-width: 768px) {
            .navbar-right {
                display: none;
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
                position: absolute;
                top: 80px;
                left: 0;
                background-color: white;
                padding: 20px;
                border-radius: 5px;
                z-index: 999;
            }

            .navbar-right.active {
                display: flex;
            }

            .navbar-right a {
                margin-right: 0;
                margin-bottom: 15px;
                width: 100%;
                text-align: left;
            }

            /* Affichage du bouton hamburger */
            .navbar-toggle {
                display: flex;
            }
        }

        /* Navbar avec fond opaque (100%) par défaut */
        .navbar {
            background-color: rgba(255, 255, 255, 1); /* 100% opaque au départ */
            transition: background-color 0.3s ease; /* Transition douce pour le changement de transparence */
        }

        /* Si la classe 'scrolled' est ajoutée, on rend la navbar plus transparente */
        .navbar.scrolled {
            background-color: rgba(255, 255, 255, 0.7); /* 70% opaque */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optionnel : ombre douce */
        }

        /* Compteur du panier */
        .count {
            background-color: red;
            color: white;
            border-radius: 50%;
            height: 30px;
            width: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: 10px;
            font-size: 0.9rem;
        }
        .store {
            width:30px;
            height:30px;
            background: url('img/store.png') no-repeat center;
            background-size: contain;
            cursor:pointer;
            transition: 0.3s ease;
        }
        .store:hover{
            width:30px;
            height:30px;
            background: url('img/store2.png') no-repeat center;
            background-size: contain;
        }
        .cartlogo {
            width:30px;
            height:30px;
            background: url('img/cart.png') no-repeat center;
            background-size: contain;
            cursor:pointer;
            transition: 0.3s ease;
        }
        .cartlogo:hover {
            width:30px;
            height:30px;
            background: url('img/cart2.png') no-repeat center;
            background-size: contain;
        }
        .dashboard {
            width:30px;
            height:30px;
            background: url('img/dashboardwhite.png') no-repeat center;
            background-size: contain;
            cursor:pointer;
            transition: 0.3s ease;
        }
        .dashboard:hover {
            width:30px;
            height:30px;
            background: url('img/dashboardblack.png') no-repeat center;
            background-size: contain;
        }
        .home {
            width:30px;
            height:30px;
            background: url('img/home.png') no-repeat center;
            background-size: contain;
            cursor:pointer;
            transition: 0.3s ease;
        }
        .home:hover {
            width:30px;
            height:30px;
            background: url('img/home2.png') no-repeat center;
            background-size: contain;
        }
        .profil {
            width:30px;
            height:30px;
            background: url('img/profil.png') no-repeat center;
            background-size: contain;
            cursor:pointer;
            transition: 0.3s ease;
        }
        .profil:hover {
            width:30px;
            height:30px;
            background: url('img/profil2.png') no-repeat center;
            background-size: contain;
        }
        .logout {
            width:30px;
            height:30px;
            background: url('img/logout.png') no-repeat center;
            background-size: contain;
            cursor:pointer;
            transition: 0.3s ease;
        }
        .logout:hover {
            width:30px;
            height:30px;
            background: url('img/logout2.png') no-repeat center;
            background-size: contain;
        }
        .login {
            width:30px;
            height:30px;
            background: url('img/login.png') no-repeat center;
            background-size: contain;
            cursor:pointer;
            transition: 0.3s ease;
        }
        .login:hover {
            width:30px;
            height:30px;
            background: url('img/login2.png') no-repeat center;
            background-size: contain;
        }

.dropdown-content {
    display: none;
    position: absolute;
    right: 0; /* Alignement du menu avec l'icône */
    background-color: white;
    min-width: 180px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    padding: 10px 0;
    transition: opacity 0.3s ease, transform 0.3s ease;
    opacity: 0;
    transform: translateY(10px);
    z-index: 10;
}

.dropdown-content a {
    display: block;
    color: black;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s ease;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

/* Activer le dropdown */
.dropdown.active .dropdown-content {
    display: block;
    opacity: 1;
    transform: translateY(0);
}
.navbar-right a {
    margin-right: 10px; /* Réduit l'écart entre les icônes */
    display: flex;
    align-items: center;
}

.navbar-right a:last-child {
    margin-right: 0; /* Pas de marge sur le dernier élément */
}
.home {
    margin-right: 5px; /* Réduit l'espace entre Home et les autres icônes */
}

    </style>
</head>
<body>
<div class="navbar">
    <!-- Logo -->
    <div class="navbar-left">
        <a href="index.php"><img src="img/RT.png" alt="Logo" class="navbar-logo" style="border-radius:50%"></a>
    </div>

    <!-- Menu hamburger pour les petits écrans -->
    <div class="navbar-toggle" id="navbar-toggle">
        <div></div>
        <div></div>
        <div></div>
    </div>

    <!-- Liens de navigation -->
    <div class="navbar-right" id="navbar-right">
        <?php if(!isset($_SESSION['username'])): ?>
            <a href="produits.php"><div class="store"></div></a>
            <a href="cart.php" style="display: flex;"><div class="cartlogo"></div>

            <div class="dropdown">
            <div class="login"></div>
            <div class="dropdown-content">
                <a href="inscription.php">S'inscrire</a>
                <a href="connexion.php">Se connecter</a>
            </div>
            </div>
        <?php else: ?>
            <a href="dashboard.php"><div class="dashboard"></div></a>
            <a href="produits.php"><div class="store"></div></a>
            <a href="favoris.php" class="cart-link">
                <img src="img/<?php echo $favoris_exist ? 'filledheart.png' : 'emptyheart.png'; ?>" alt="Favoris" class="cart-icon">
        </a>
            <a href="cart.php" style="display: flex;"><div class="cartlogo"></div>
                <?php if (count($cart_items) > 0): ?> <div class="count"><?php echo count($cart_items); ?></div> <?php endif ?>
            </a>
            <a href="compte.php"><div class="profil"></div></a>
            <a href="deconnexion.php"><div class="logout"></div></a>
        <?php endif; ?>
    </div>
</div>

<script>
    // Script pour activer le menu hamburger
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarRight = document.getElementById('navbar-right');

    navbarToggle.addEventListener('click', () => {
        navbarRight.classList.toggle('active');
    });
</script>

<script>
    // Fonction pour ajuster la transparence de la navbar en fonction du défilement
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        const scrollPosition = window.scrollY;
        
        // Calcul de l'opacité en fonction du défilement
        const opacity = Math.min(scrollPosition / 500, 0.3); // 500px de scroll pour atteindre 70% de transparence

        // Appliquer l'opacité calculée à la navbar
        navbar.style.backgroundColor = `rgba(255, 255, 255, ${1 - opacity})`; // Le fond devient de plus en plus transparent au fur et à mesure

        // Si l'utilisateur a scrollé plus de 50px, on applique une ombre (optionnelle)
        if (scrollPosition > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    document.addEventListener('DOMContentLoaded', function () {
        const dropdown = document.querySelector('.dropdown');
        const dropdownContent = document.querySelector('.dropdown-content');

        dropdown.addEventListener('click', function (event) {
            event.stopPropagation(); // Empêche la fermeture immédiate
            dropdown.classList.toggle('active');
        });

        // Fermer si on clique ailleurs
        document.addEventListener('click', function (event) {
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    });
</script>
</body>
</html>
