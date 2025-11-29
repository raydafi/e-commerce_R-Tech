<?php
session_start();

// Générer le texte du CAPTCHA
$_SESSION['captcha'] = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 5);

// Créer une image
$img = imagecreate(100, 30);

// Définir les couleurs
$bg = imagecolorallocate($img, 255, 255, 255); // blanc pour l'arrière-plan
$textcolor = imagecolorallocate($img, mt_rand(0, 55), mt_rand(55, 155), mt_rand(155, 255)); // couleur du texte

// Ajouter du texte au CAPTCHA avec une légère rotation
$angle = mt_rand(-5, 5); // Angle de rotation aléatoire
$font = __DIR__ . '/fonts/28dayslater.ttf'; // Assure-toi que le chemin est correct
imagettftext($img, 23, $angle, 3, 30, $textcolor, $font, $_SESSION['captcha']);

// Ajouter du bruit (lignes ou points) pour rendre le CAPTCHA plus difficile à résoudre automatiquement
for ($i = 0; $i < 10; $i++) {
    $linecolor = imagecolorallocate($img, mt_rand(100, 255), mt_rand(100, 255), mt_rand(100, 255));
    imageline($img, mt_rand(0, 100), mt_rand(0, 30), mt_rand(0, 100), mt_rand(0, 30), $linecolor);
}

// Définir le type d'image et l'envoyer au navigateur
header('Content-Type: image/png');
imagepng($img);

// Libérer la mémoire utilisée par l'image
imagedestroy($img);
?>
