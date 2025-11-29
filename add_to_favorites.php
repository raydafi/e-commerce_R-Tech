<?php
session_start();
include 'bdd.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
    exit;
}

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
    exit;
}

$user_id = $_SESSION['username'];

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'ID de la vidéo manquant.']);
    exit;
}

try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        $is_favorite = $stmt->fetchColumn() > 0;

        if ($is_favorite) {
            $stmt = $pdo->prepare("DELETE FROM favoris WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
            echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Produit retirée des favoris.']);
        } else {
            $stmt = $pdo->prepare("INSERT INTO favoris (user_id, product_id) VALUES (:user_id, :product_id)");
            $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
            echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Produit ajouté aux favoris.']);
        }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
}