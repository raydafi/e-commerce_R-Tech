<?php 
session_start();

include 'bdd.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, name, image, price FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit;
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = $product_id;
    header("Location: cart.php");
    exit;
}

if (isset($_POST['remove_item'])) {
    $item_id = $_POST['item_id'];

    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $key = array_search($item_id, $_SESSION['cart']);

        if  ($key !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }

    header("Location: cart.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panier</title>
        <link rel="icon" type="image/png" href="img/RT.png"/>
        <style>
                        /* Style global */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                margin: 0;
                padding: 0;
            }

            h1 {
                text-align: center;
                font-size: 2rem;
                margin-top: 40px;
                color: #333;
            }

            /* Tableau des produits */
            .tableau {
                margin: 20px auto;
                max-width: 1000px;
                padding: 10px;
                background-color: white;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }

            th, td {
                padding: 15px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #f0f0f0;
                font-weight: bold;
            }

            td img {
                max-width: 100px;
                height: auto;
                border-radius: 5px;
            }

            /* Boutons d'action */
            button {
                padding: 10px 20px;
                font-size: 1rem;
                cursor: pointer;
                border: none;
                border-radius: 5px;
                transition: all 0.3s ease;
            }

            button#add {
                background-color: #28a745;
                color: white;
            }

            button#delete {
                background-color: #dc3545;
                color: white;
            }

            button#vider {
                background-color: #ffc107;
                color: white;
                margin-top: 20px;
            }

            button#finaliser {
                background-color: #007bff;
                color: white;
                margin-top: 20px;
            }

            button:hover {
                transform: scale(1.05);
            }

            button:focus {
                outline: none;
            }

            /* Total panier */
            p {
                text-align: center;
                font-size: 1.2rem;
                font-weight: bold;
                color: #333;
            }

            /* Lien Retourner au catalogue */
            #cata {
                display: block;
                text-align: center;
                margin-top: 30px;
                font-size: 1.1rem;
                color: #007bff;
                text-decoration: none;
            }

            #cata:hover {
                color: #0056b3;
            }

            /* Responsive */
            @media (max-width: 768px) {
                table, th, td {
                    font-size: 0.9rem;
                }

                button {
                    width: 100%;
                    margin: 5px 0;
                }

                .tableau {
                    padding: 10px;
                }
            }

            #vider, #finaliser {
                display: block;
                margin: 0 auto;
            }

        </style>
    </head>
<body>

<?php include 'navbar.php'; ?>

    <h1>Votre panier</h1>
    <div class="tableau">
    <?php if (!empty($cart_items)): ?>
        <table>
            <thead>
                <tr>
                    <th>image</th>
                    <th>Nom de produit</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item_id): ?>
                    <?php $product = array_filter($products, function($prod) use ($item_id) {
                        return $prod['id'] == $item_id;
                    });
                    $product = array_values($product)[0];
                    $total += $product['price'];
                    ?>
                    <tr>
                        <td>
                            <img src="image.php?id=<?php echo $product['id']; ?>" 
                                alt="<?php echo htmlspecialchars($product['name']); ?>" width="100">
                        </td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['price']; ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" id="add" name="add_to_cart">Ajouter</button>
                            </form>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                <button type="submit" id="delete" name="remove_item">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <p style="font-size:larger; font-weight:bolder;">Total: <?php echo $total; ?> €</p>
        <form method="post">
            <button type="submit" id="vider" name="clear_cart">Vider le panier</button>
        </form>

        <form method="get" action="checkout.php">
            <button type="submit" id="finaliser">Finaliser le Panier</button>
        </form>

        <?php else: ?>
            <p>Votre panier est vide</p>
        <?php endif; ?>

        <a href="produits.php" id="cata">Retourner au Catalogue</a>
        <?php include 'footer.php'; ?>

</body>
</html>