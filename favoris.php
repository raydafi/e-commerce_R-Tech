<?php
session_start();

if (!isset($_SESSION['username'])) {
    die("L'utilisateur n'est pas connecté.");
}

$user_id = $_SESSION['username'];

include 'bdd.php';

try {
    if (!isset($pdo)) {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    $stmt = $pdo->prepare("
    SELECT p.id, p.name, p.image, p.price, p.type
    FROM favoris f
    JOIN products p ON f.product_id = p.id 
    WHERE f.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Favoris</title>
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

.product-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
    padding: 20px;
    margin-left: 270px; /* Ajoute un décalage pour la sidebar, si nécessaire */
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
}

.product-item a {
    text-decoration: none;
    color: #333;
    transition: color 0.3s ease;
}

.product-item a:hover {
    color: #007bff;
}

.favorite-icon {
    width:30px;
            height:30px;
            background: url('img/filledheart.png') no-repeat center;
            background-size: contain;
            cursor:pointer;
            border-radius:50%;
}

.favorite-icon.added {
    background:url('img/filledheart.png') no-repeat center;
    background-size: contain;
}

.favorite-icon.removing {
    background:url('img/emptyheart.png') no-repeat center;
    background-size: contain;
}


h1 {
    text-align: center;
    margin-top: 40px;
    font-size: 2rem;
    color: #333;
}

footer {
    background-color: #f1f1f1;
    padding: 20px;
    text-align: center;
    margin-top: 30px;
}

p {
    font-size: 1.1rem;
    text-align: center;
    color: #666;
}

            </style>
    </head>

<body>
    <?php include 'navbar.php';?>
    <br>
    <h1>Mes favoris</h1>

     <div class="product-list">
        <?php if (empty($favoris)): ?>
            <p><br>Aucun favori trouvé.</p>
            <?php else: ?>
                <?php foreach ($favoris as $favori): ?>
                    <div class="product-item">
                        <img src="<?php echo htmlspecialchars($favori['image']); ?>" alt="<?php echo htmlspecialchars($favori['name']); ?>">
                        <h2>
                            <a href="detail.php?id=<?php echo htmlspecialchars($favori['id']); ?>">
                                <?php echo htmlspecialchars($favori['name']); ?>
                            </a>
                        </h2>
                        <p>Prix : <?php echo htmlspecialchars($favori['price']); ?></p>
                        <div class="favorite-icon" data-product-id="<?php echo htmlspecialchars($favori['id']); ?>"></div>
                    </div>
                <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const favoriteIcons = document.querySelectorAll('.favorite-icon');

            favoriteIcons.forEach(icon => {
                icon.addEventListener('click', function () {
                    const productId = icon.getAttribute('data-product-id');

                    fetch('add_to_favorites.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({product_id: productId})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.action === 'removed') {
                                icon.classList.remove('added');
                                icon.classList.add('removing');

                                setTimeout(() => {
                                    icon.parentElement.remove();
                                }, 1000);
                            } else if (data.action === 'added') {
                                icon.classList.add('added');
                                icon.classList.remove('removing');
                            }
                        } else {
                            alert(data.message || 'Erreur lors de la modification des favoris.');
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
                });
            });
        });
    </script>
</body>
</html>