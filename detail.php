<?php
session_start();

include 'bdd.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $product_id = (int) $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo "Produit non trouvé.";
            exit;
        }
        $isFavorite = false;
        if (isset($_SESSION['username'])) {
            $user = $_SESSION['username'];
            $favStmt = $pdo->prepare(
                "SELECT COUNT(*) FROM favoris WHERE user_id = :user_id AND product_id = :product_id"
            );
            $favStmt->execute(['user_id' => $user, 'product_id' => $product_id]);
            $isFavorite = $favStmt->fetchColumn() > 0;
        }
    } else {
        echo "ID invalide.";
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="img/RT.png"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .product-details {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .product-details img {
            max-width: 300px;
            height: auto;
            border-radius: 8px;
            margin-right: 30px;
        }

        .product-details h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 15px;
        }

        .product-details p {
            font-size: 1rem;
            color: #666;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .product-details .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 20px;
        }

        .product-details button {
            background-color: #4169E1;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .product-details button:hover {
            background-color:rgb(45, 87, 212);
            transform: scale(1.05);
        }

        footer {
            background-color: #f1f1f1;
            padding: 20px;
            text-align: center;
            margin-top: 30px;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            padding-top: 60px;  /* Ajuste en fonction de la hauteur de ta navbar */
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;  /* Pour que la navbar soit toujours au-dessus du contenu */
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        button[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button[type="submit"]:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
        .favorite-icon {
            width:30px;
            height:30px;
            background: url('img/emptyheart.png') no-repeat center;
            background-size: contain;
            cursor:pointer;
        }
        .favorite-icon.added {
            background:url('img/filledheart.png') no-repeat center;
            background-size: contain;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="product-details">
        <div class="image-container">
        <img src="image.php?id=<?= $product['id'] ?>" alt="<?= htmlspecialchars($product['name']); ?>">
        </div>
        <div class="info">
            <h1><?= htmlspecialchars($product['name']); ?></h1>
            <?php if (isset($_SESSION['username'])): ?>
                <div class="favorite-icon" id="favorite-icon" data-product-id="<?php echo $product_id; ?>"></div>
                <?php endif; ?>
            <p class="price">Prix: <?= htmlspecialchars($product['price']); ?> €</p>
            <p><?= htmlspecialchars($product['description']); ?></p>
            <p><strong>Mémoire:</strong> <?= htmlspecialchars($product['memoire']); ?></p>
            <p><strong>État:</strong> <?= htmlspecialchars($product['etat']); ?></p>
            <p><strong>Description :</strong> <?= htmlspecialchars($product['detail']); ?></p>
                <br>
            <a href="produits.php"><button>Revenir au Catalogue</button></a>
        </div>
    </div>
</div>

<?php include 'footer.php'?>
        <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const favoriteIcon = document.getElementById('favorite-icon');
                    
                    if (favoriteIcon) {
                        favoriteIcon.addEventListener('click', function () {
                        const productId = favoriteIcon.getAttribute('data-product-id');

                        fetch('add_to_favorites.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ product_id: productId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                favoriteIcon.classList.toggle('added');
                            } else {
                                alert('Erreur lors de l\'ajout aux favoris.');
                            }
                        })
                        .catch(error => console.error('Erreur:', error));
                    });
                }
            });
        </script>
</body>
</html>
