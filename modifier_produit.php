<?php
session_start();
include 'bdd.php';

$pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Vérification de l’ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID produit invalide.";
    exit;
}

$id = (int)$_GET['id'];

// Traitement de la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Vérifie si une nouvelle image a été téléchargée
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $image, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $id]);
    }

    header("Location: dashboard.php");
    exit;
}

// Récupération des données actuelles du produit
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    echo "Produit introuvable.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Produit</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        form { background: white; padding: 20px; margin-top: 20px; border-radius: 10px; max-width: 600px; margin: auto; }
        input[type="text"], input[type="number"], input[type="file"] { width: 90%; padding: 10px; margin-bottom: 10px; }
        button { padding: 10px 20px; }
        img { max-width: 200px; display: block; margin-bottom: 10px; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Modifier le produit</h2>

<form action="modifier_produit.php?id=<?= $produit['id'] ?>" method="post" enctype="multipart/form-data">
    <label>Nom du produit :</label>
    <input type="text" name="name" value="<?= htmlspecialchars($produit['name']) ?>" required>

    <label>Description :</label>
    <input type="text" name="description" value="<?= htmlspecialchars($produit['description']) ?>" required><br>

    <label>Prix (€) :</label>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($produit['price']) ?>" required>

    <label>Image actuelle :</label>
    <img src="image.php?id=<?= $produit['id'] ?>" alt="Produit actuel">

    <label>Nouvelle image (optionnel) :</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit">Mettre à jour</button>
</form>

</body>
</html>
