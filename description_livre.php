<?php
include('pdo.php');

// Vérifier si l'ID est présent dans l'URL
if (!isset($_GET['id'])) {
    header('Location: catalogue_livre.php');
    exit();
}

$livreId = $_GET['id'];

try {
    // Récupérer les détails complets du livre
    $stmt = $pdo->prepare("SELECT * FROM livre WHERE `id livre` = ?");
    $stmt->execute([$livreId]);
    $livre = $stmt->fetch();
    
    if (!$livre) {
        header('Location: livre.php');
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
    <!-- header -->
    <div class="top-bar">
        <div class="logo">
            <a href="Accueil.html">
                <img src="assets/images/logo.png" alt="logo">
            </a>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Rechercher...">
        </div>
        <div class="icons-header">
            <div class="icons">
                <a href="coup_de_coeur.html">
                    <img src="assets/images/coeur.png" alt="Coup de Coeur">
                </a>
                <a href="panier.html">
                    <img src="assets/images/cart.png" alt="Panier">
                </a>
                <a href="acces_client.html">
                    <img src="assets/images/profil_pictures.png" alt="Profil">
                </a>
            </div>
            <div class="deconnexion">
                <a href="#notificationModal" class="notification-box">Notifications</a>
                <a href="Admin_ou_lecteur.html" class="deconnecte">Déconnexion</a>
            </div>
        </div>
    </div>

    <!-- Modal Notifications -->
    <div id="notificationModal" class="modal-notification">
        <div class="modal-notification-content">
            <a href="#" class="close">&times;</a>
            <p>Voici les notifications</p>
        </div>
    </div>

    <div class="categories">
        <a href="Accueil.html">Accueil</a>
        <a href="blog.html">Blog</a>
        <a href="livre.php">Livre</a>
        <a href="rachats_de_livres.html">Rachats de livres</a>
        <a href="messagerie.html">Messagerie</a>
    </div>

    <!-- Contenu principal -->
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
                <p><strong>Date de publication:</strong> <?= htmlspecialchars($livre['date_publication']) ?></p>
                <p><strong>État:</strong> <?= htmlspecialchars($livre['etat']) ?></p>
                <p><strong>Note:</strong> <?= htmlspecialchars($livre['note']) ?>/5</p>
            </div>
            
            <a href="panier.html" class="buy-button">Ajouter au panier</a>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Catalogue de Livres. Tous droits réservés.</p>
    </footer>
</body>
</html>