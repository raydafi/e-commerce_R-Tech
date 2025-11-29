<?php
require 'bdd.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo "ID manquant.";
    exit;
}

$id = (int) $_GET['id'];

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Vérifie si c'est une image JPEG ou PNG selon ce que tu as stocké
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_buffer($finfo, $row['image']);
        header("Content-Type: " . $type);
        echo $row['image'];
    } else {
        http_response_code(404);
        echo "Image non trouvée.";
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur : " . $e->getMessage();
}
?>
