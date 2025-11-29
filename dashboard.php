<?php
session_start();
include 'bdd.php';

$pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Gestion de l'ajout de produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = file_get_contents($_FILES['image']['tmp_name']);
    
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $image]);
}

// Suppression produit
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: dashboard.php");
    exit;
}

// Récupération des produits
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestion Produits</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: center; }
        form { background: white; padding: 20px; margin-top: 20px; border-radius: 10px; max-width: 600px; margin: auto; }
        input[type="text"], input[type="number"],textarea, input[type="file"] { width: 90%; padding: 10px; margin-bottom: 10px; }
        button { padding: 10px 20px; }
        .img-class { width: 100px; height: auto; }
    </style>
</head>
<body>
<?php include 'navbar.php' ?><br><br><br><br><br>
<h1>Tableau de bord - Gestion des produits</h1>

<form action="dashboard.php" method="post" enctype="multipart/form-data">
    <h2>Ajouter un produit</h2>
    <input type="text" name="name" placeholder="Nom du produit" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="number" step="0.01" name="price" placeholder="Prix (€)" required>
    <input type="file" name="image" accept="image/*" required>
    <button type="submit" name="ajouter">Ajouter le produit</button>
</form>

<h2>Produits disponibles</h2>
<table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><img class="img-class" src="image.php?id=<?= $product['id'] ?>" alt="Produit"></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= htmlspecialchars($product['description']) ?></td>
            <td><?= number_format($product['price'], 2) ?> €</td>
            <td>
                <a href="modifier_produit.php?id=<?= $product['id'] ?>" style="text-decoration:none;color:blue;">Modifier</a> |
                <a href="dashboard.php?delete=<?= $product['id'] ?>" onclick="return confirm('Supprimer ce produit ?')" style="text-decoration:none;color:red;">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
