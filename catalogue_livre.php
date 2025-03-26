<?php
include('pdo.php');
// Récupération des livres dans la base de données
try {
    $stmt = $pdo->prepare("SELECT `id livre`, genre, titre, `prenom auteur`, `nom auteur`, img FROM livre ORDER BY genre, titre");
    $stmt->execute();
    $livres = $stmt->fetchAll();
    
} catch (PDOException $e) {
    die("Erreur lors de la récupération des livres : " . $e->getMessage());
}

// Récupération des genres distincts
$stmtGenres = $pdo->prepare("SELECT DISTINCT genre FROM livre ORDER BY genre");
$stmtGenres->execute();
$genres = $stmtGenres->fetchAll(PDO::FETCH_COLUMN);

// Organiser les livres par genre
$livresParGenre = [];
foreach ($livres as $livre) {
    $livresParGenre[$livre['genre']][] = $livre;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de Livres</title>
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
        <a href="livre.php" class="active">Livre</a>
        <a href="rachats_de_livres.html">Rachats de livres</a>
        <a href="messagerie.html">Messagerie</a>
    </div>

    <!-- Contenu principal -->
    <header>
        <h1>Catalogue de Livres</h1>
        <nav class="genre-navigation">
            <ul>
                <?php foreach ($genres as $genre) : ?>
                    <li><a href="#<?= htmlspecialchars(strtolower(str_replace(' ', '-', $genre))) ?>">
                        <?= htmlspecialchars($genre) ?>
                    </a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </header>

    <main>
        <?php foreach ($livresParGenre as $genre => $livres) : ?>
            <section id="<?= htmlspecialchars(strtolower(str_replace(' ', '-', $genre))) ?>" class="category">
                <h2><?= htmlspecialchars($genre) ?></h2>
                <div class="book-row">
                    <?php foreach ($livres as $livre) : ?>
                        <a href="description_livre.php?id=<?= htmlspecialchars($livre['id livre']) ?>"> 
                            <div class="book">
                                <div class="book-image-container">
                                    <?php if (!empty($livre['img'])) : ?>
                                        <?php $imagePath = 'image_livre/' . basename($livre['img']); ?>
                                        <img src="<?= htmlspecialchars($imagePath) ?>" 
                                             alt="Couverture de <?= htmlspecialchars($livre['titre']) ?>"
                                             class="book-cover"
                                             onerror="this.onerror=null;this.src='image_livre/placeholder.jpg';">
                                    <?php else : ?>
                                        <img src="image_livre/placeholder.jpg" 
                                             alt="Couverture non disponible"
                                             class="book-cover">
                                    <?php endif; ?>
                                </div>
                                <div class="book-content">
                                    <div class="book-title"><?= htmlspecialchars($livre['titre']) ?></div>
                                    <div class="book-author">
                                        <?= htmlspecialchars($livre['prenom auteur']) ?> 
                                        <?= htmlspecialchars($livre['nom auteur']) ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Catalogue de Livres. Tous droits réservés.</p>
    </footer>
</body>
</html>