<?php
include('pdo.php');

// Vérifier si l'ID est présent dans l'URL
if (!isset($_GET['id'])) {
    header('Location: Stock_admin.php');
    exit();
}

$livreId = $_GET['id'];

try {
    // Récupérer les détails complets du livre
    $stmt = $pdo->prepare("SELECT * FROM livre WHERE id_livre = ?");
    $stmt->execute([$livreId]);
    $livre = $stmt->fetch();
    
    if (!$livre) {
        header('Location: Stock_admin.php');
        exit();
    }
    
} catch (PDOException $e) {
    die("Erreur lors de la récupération du livre : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($livre['titre']) ?> - Description</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin">
        <div class="top-bar">
            <div class="logo">
                <a href="Accueil.php">
                    <img src="Icon\logo.png" alt="Logo">
                </a>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Rechercher...">
            </div>
            <div class="icons-header-admin">
                <div class="icons">
                    <div class="deconnexion">
                        <a href="Admin_ou_lecteur.php" class="deconnecte">Déconnexion</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="categories">
            <a href="Accueil_admin.php">Accueil</a>
            <a href="Stock_admin.php" class="active">Stock</a>
            <a href="nouveau_livre_admin.php">Nouveaux livres</a>
            <a href="rachat_de_livre_admin.php">Rachats de livres</a>
            <a href="vitrine_admin.php">Vitrine</a>
        </div>

        <main class="book-detail-container">
        <div class="book-detail-image">
            <?php if (!empty($livre['img'])) : ?>
                <?php $imagePath = 'image_livre/' . basename($livre['img']); ?>
                <img src="<?= htmlspecialchars($imagePath) ?>" 
                     alt="Couverture de <?= htmlspecialchars($livre['titre']) ?>"
                     onerror="this.onerror=null;this.src='image_livre/placeholder.jpg';">
            <?php else : ?>
                <img src="image_livre/placeholder.jpg" 
                     alt="Couverture non disponible">
            <?php endif; ?>
        </div>
        
        <div class="book-detail-info">
            <h1><?= htmlspecialchars($livre['titre']) ?></h1>
            <p class="book-author"><?= htmlspecialchars($livre['prenom auteur']) ?> <?= htmlspecialchars($livre['nom auteur']) ?></p>
            <p class="book-genre"><strong>Genre:</strong> <?= htmlspecialchars($livre['genre']) ?></p>
            
            <div class="book-summary">
                <h2>Résumé</h2>
                <p><?= nl2br(htmlspecialchars($livre['resume'] ?? 'Aucune description disponible pour ce livre.')) ?></p>
            </div>
            
            <div class="book-meta">
                <p><strong>Stock disponible :</strong> <?= htmlspecialchars($livre['stock']) ?></p>
                <p><strong>Date de publication:</strong> <?= htmlspecialchars($livre['date_publication']) ?></p>
                <p><strong>État:</strong> <?= htmlspecialchars($livre['etat']) ?></p>
                <p><strong>Note:</strong> <?= htmlspecialchars($livre['note']) ?>/5</p>
            </div>
        </main>
    </div>
</body>
</html>