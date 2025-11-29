<?php
session_start();

date_default_timezone_set('Europe/Paris');
include 'bdd.php';

$search = $_GET['search'] ?? "";
$type_filter = $_GET['type'] ?? "";
$etat_filter = $_GET['etat'] ?? "";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $priceQuery = $pdo->query("SELECT MIN(price) AS min_price, MAX(price) AS max_price FROM products");
    $priceResult = $priceQuery->fetch(PDO::FETCH_ASSOC);
    $minPrice = $priceResult['min_price'] ?? 0;
    $maxPrice = $priceResult['max_price'] ?? 1100.00;

    $sql = "SELECT id, name, price, description, memoire, etat, type, detail FROM products WHERE 1";
    if (!empty($search)) $sql .= " AND name LIKE :search";
    if (!empty($type_filter)) $sql .= " AND type = :type";
    if (!empty($etat_filter)) $sql .= " AND etat = :etat";
    if (isset($_GET['price_min'], $_GET['price_max'])) {
        $sql .= " AND price BETWEEN :min_price AND :max_price";
    }

    $stmt = $pdo->prepare($sql);
    if (!empty($search)) $stmt->bindValue(':search', "%$search%");
    if (!empty($type_filter)) $stmt->bindValue(':type', $type_filter);
    if (!empty($etat_filter)) $stmt->bindValue(':etat', $etat_filter);
    if (isset($_GET['price_min'], $_GET['price_max'])) {
        $stmt->bindValue(':min_price', $_GET['price_min']);
        $stmt->bindValue(':max_price', $_GET['price_max']);
    }

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur de connexion :" . $e->getMessage();
    exit;
}

if (isset($_POST['add_to_cart'])) {
    $_SESSION['cart'][] = $_POST['product_id'] ?? null;
}

if (isset($_POST['submit_rating'], $_POST['rating_product_id'], $_POST['rating'], $_SESSION['username'])) {
    $username = $_SESSION['username'];
    $product_id = (int) $_POST['rating_product_id'];
    $rating = (int) $_POST['rating'];

    if ($rating >= 1 && $rating <= 5) {
        $stmt = $pdo->prepare("SELECT id FROM ratings WHERE username = ? AND product_id = ?");
        $stmt->execute([$username, $product_id]);

        if ($stmt->rowCount() > 0) {
            $update = $pdo->prepare("UPDATE ratings SET rating = ?, created_at = CURRENT_TIMESTAMP WHERE username = ? AND product_id = ?");
            $update->execute([$rating, $username, $product_id]);
        } else {
            $insert = $pdo->prepare("INSERT INTO ratings (username, product_id, rating) VALUES (?, ?, ?)");
            $insert->execute([$username, $product_id, $rating]);
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Catalogue</title>
        <link rel="icon" type="image/png" href="img/RT.png"/>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
                display: flex;
                padding-top: 60px; /* Décalage pour ne pas que le contenu soit caché sous la navbar */
            }

            .sidebar {
                position: fixed;
                top: 60px; /* Déplace la sidebar sous la navbar */
                left: 0;
                width: 250px;
                background-color: #fff;
                padding: 20px;
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
                height: 100%; /* Fixe la sidebar sur toute la hauteur */
                overflow-y: auto; /* Ajoute un défilement si le contenu dépasse */
            }

            .sidebar input,
            .sidebar select,
            .sidebar button {
                width: 95%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            .product-list {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 30px;
                padding: 20px;
                margin-left: 270px; /* Ajoute un décalage pour la sidebar */
                flex: 1;
            }

            .product-item {
                background-color: #f9f9f9;
                border: 1px solid #ddd;
                padding: 20px;
                text-align: center;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .product-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            }

            .product-img {
                height: 100px;
                margin-bottom: 15px;
            }
            
            .product-item img {
                max-width: 100%;
                max-height: 100%;
                border-bottom: 2px solid #ddd;
                margin-bottom: 10px;
            }

            .product-item h2 {
                font-size: 1.2rem;
                margin: 10px 0;
                color: #333;
            }

            .product-item p {
                font-size: 1rem;
                color: #666;
                margin: 5px 0;
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
                margin: 5px;
            }

            button[type="submit"]:hover {
                background-color: #218838;
                transform: scale(1.05);
            }

            #voirpanier {
                display: inline-block;
                margin-top: 20px;
                font-size: 1.1rem;
                color: #007bff;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            #voirpanier:hover {
                color: #0056b3;
            }

            footer {
                background-color: #f1f1f1;
                padding: 20px;
                text-align: center;
                margin-top: 30px;
            }

            h1 {
                text-align: center;
                margin-top: 40px;
                font-size: 2rem;
                color: #333;
            }

            .img-item {
                max-width: 80px;
                max-height: 80px;
            }

            .rating-form {
                margin-top: 15px;
                font-size: 0.9rem;
                padding: 10px;
                background-color: #f8f9fa;
                border-radius: 5px;
                border: 1px solid #e9ecef;
            }

            .rating-form select {
                padding: 5px;
                margin: 5px;
                border: 1px solid #ccc;
                border-radius: 3px;
            }

            .rating-form button {
                background-color: #ffc107;
                color: #000;
                border: none;
                padding: 5px 10px;
                border-radius: 3px;
                cursor: pointer;
                font-size: 0.9rem;
            }

            .rating-form button:hover {
                background-color: #e0a800;
            }

            .rating-display {
                margin-top: 10px;
                font-size: 0.9rem;
                color: #555;
                font-weight: bold;
            }

            .price-slider {
                margin: 15px 0;
            }

            .price-values {
                display: flex;
                justify-content: space-between;
                font-size: 0.9rem;
                margin-bottom: 10px;
            }

            .login-message {
                font-style: italic;
                color: #6c757d;
                font-size: 0.9rem;
                margin-top: 10px;
            }
        </style>
    </head>
<body>
<?php include 'navbar.php'; ?>
<div class="sidebar">
<form method="get" id="filterForm">
    <!-- Recherche -->
    <input type="text" name="search" placeholder="Rechercher un produit" 
           value="<?= htmlspecialchars($search) ?>" />

    <!-- Filtre Type -->
    <select name="type">
        <option value="">-- Type --</option>
        <option value="ordinateur" <?= ($type_filter === 'ordinateur') ? 'selected' : '' ?>>Ordinateur</option>
        <option value="telephone" <?= ($type_filter === 'telephone') ? 'selected' : '' ?>>Téléphone</option>
        <option value="tablette" <?= ($type_filter === 'tablette') ? 'selected' : '' ?>>Tablette</option>
    </select>

    <!-- Filtre État -->
    <select name="etat">
        <option value="">-- État --</option>
        <option value="neuf" <?= ($etat_filter === 'neuf') ? 'selected' : '' ?>>Neuf</option>
        <option value="occasion" <?= ($etat_filter === 'occasion') ? 'selected' : '' ?>>Occasion</option>
        <option value="reconditionne" <?= ($etat_filter === 'reconditionne') ? 'selected' : '' ?>>Reconditionné</option>
    </select>

    <!-- Filtre Prix -->
    <div class="price-slider">
        <div class="price-values">
            <span>Min: €<?= $minPrice ?></span>
            <span>Max: €<?= $maxPrice ?></span>
        </div>
        <input type="number" name="price_min" min="<?= $minPrice ?>" max="<?= $maxPrice ?>" 
               value="<?= $_GET['price_min'] ?? $minPrice ?>" step="0.01" />
        <input type="number" name="price_max" min="<?= $minPrice ?>" max="<?= $maxPrice ?>" 
               value="<?= $_GET['price_max'] ?? $maxPrice ?>" step="0.01" />
    </div>

    <button type="submit">Appliquer les filtres</button>
</form>

</div>

<div class="product-list">
    <?php foreach ($products as $product): ?>
        <?php
        $isLoggedIn = isset($_SESSION['username']);
        $userRating = null;

        if ($isLoggedIn) {
            $userStmt = $pdo->prepare("SELECT rating FROM ratings WHERE username = ? AND product_id = ?");
            $userStmt->execute([$_SESSION['username'], $product['id']]);
            $userRating = $userStmt->fetchColumn();
        }

        $ratingStmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) as total FROM ratings WHERE product_id = ?");
        $ratingStmt->execute([$product['id']]);
        $ratingData = $ratingStmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="product-item">
            <div class="product-img">
                <a href="detail.php?id=<?= htmlspecialchars($product['id']) ?>">
                <img class="img-item" src="image.php?id=<?= htmlspecialchars($product['id']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                </a>
            </div>
            <h2><a href="detail.php?id=<?= $product['id'] ?>" style="text-decoration:none; color:black"><?= htmlspecialchars($product['name']) ?></a></h2>
            <p><strong>Prix: €<?= htmlspecialchars($product['price']) ?></strong></p>
            <p><?= htmlspecialchars($product['description']) ?></p>
            <form method="post">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button type="submit" name="add_to_cart">Ajouter au panier</button>
            </form>
            <!-- notation utilisateur -->
            <?php if ($isLoggedIn): ?>
                <form method="post" class="rating-form">
                    <input type="hidden" name="rating_product_id" value="<?= $product['id'] ?>">
                    <select name="rating">
                        <option value="">-- Choisir une note --</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>" <?= ($userRating == $i) ? 'selected' : '' ?>><?= $i ?> ★</option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" name="submit_rating">Noter</button>
                </form>
            <?php else: ?>
                <p>Connectez-vous pour noter.</p>
            <?php endif; ?>

            <div class="rating-display">
                <?php
                if ($ratingData && $ratingData['total'] > 0) {
                    $avgRating = round($ratingData['avg_rating'], 1);
                    echo "Note moyenne : $avgRating ★ (" . $ratingData['total'] . " avis)";
                } else {
                    echo "Aucun avis pour ce produit.";
                }
                ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>